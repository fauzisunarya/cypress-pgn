<?php

namespace Drupal\media_upload\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\redirect\Entity\Redirect;
use Drupal\Core\Url;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\media_upload\Form\Helper;
use Drupal;

/**
 * Provides the form for adding countries.
 */
class EditLandingPageForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'edit_landing_page_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $landing_id = null) {
    
    // load landing data
    $entity = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $landing_id]);
    $entity = current($entity);

    // validate query string type (landing page type) in url
    $current_landing_page_type = (int) $entity->field_lan_landing_type->getString();
    $resultValidate = Helper::validate_landing_page_type('edit', $current_landing_page_type);
    if ($resultValidate['status'] === false) {
      // redirect to include "type" in url
      Drupal::service('media_upload.page_helper')->redirect($resultValidate['data']['redirect']);
    }

    $catalog_data  = [];
    $total_catalog = 1;

    if ($current_landing_page_type === $resultValidate['data']['landing_page_type_id']) {
      // load catalog, if landing type in Database same with query stirng
      // if different ? reset catalog (not load any catalog)
      $catalog_data  = $entity->field_lan_product_catalog->getValue();
      $total_catalog = count($catalog_data) < 1 ? 1 : count($catalog_data);
    }

    if (!$entity->field_lan_website_logo->isEmpty()) :
      $imageURL = $entity->field_lan_website_logo->getString();

      if (str_contains($imageURL, 's3')) {
        $findS3 = \Drupal::service('restapi_telkom.minio_helper')->getFileByURI($imageURL, 'original', 'info');

        $imageURL = $findS3['status'] 
          ? "{$_ENV['APP_URL']}/restapi/v1/media_render/{$findS3['data']['uuid']}" 
          : $imageURL;
      };
    endif;

    // setting page theme used
    $form['#theme'] = 'landing_edit_form';
    $form['#attributes'] = array('autocomplete' => 'off');

    $form['landing_form_type'] = array(
      '#type' => 'hidden',
      '#value' => 'edit', 
    );

    $form['landing_id'] = array(
      '#type' => 'hidden',
      '#value' => $landing_id, 
    );

    $form['landing_page_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Landing page name'),
	    '#attributes' => array(
        'class' => ['txt-class'],
       ),
      '#default_value' => $entity->label(),
    ];

    $form['landing_page_description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Landing page description'),
	    '#attributes' => array(
        'class' => ['txt-class'],
       ),
      '#default_value' => $entity->field_lan_website_description->getString(),
    ];

    $form['landing_logo_url'] = array(
      '#type' => 'hidden',
      '#value' => !empty($imageURL) ? $imageURL : '', 
    );

    // list term in taxonomy "landing_page_type"
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'landing_page_type']);
    $landing_page_type = [];
    foreach ($terms as $term) {
        $landing_page_type[$term->id()] = $term->label();
    }

    $form['#attached']['library'][] = 'media_upload/landing-form';

    $form['landing_page_type'] = array(
      '#type' => 'select',
      '#title' => $this->t('Landing Page Type'),
      '#options' => $landing_page_type,
      '#default_value' => $resultValidate['data']['landing_page_type_id']
    );

    $form['landing_product'] = array(
      '#type' => 'table',
      '#title' => $this->t('Landing Product Catalog'),
      '#header' => array(
        $this->t('Product Catalog')
      ),
    );

    for ($i = 0; $i < $total_catalog; $i++) :
      if (empty($catalog_data[$i]['target_id'])) {
        $form['landing_product'][$i+1]['#attributes'] = array(
          'class' => array(
            'product-catalog-list',
          ),
        );
        $form['landing_product'][$i+1]['name'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('Pilih product catalog'),
          '#title_display' => 'invisible',
          '#attributes' => array('class' => ['input-product-catalog'])
        );
      } else {
        $node = Node::load($catalog_data[$i]['target_id']);

        $form['landing_product'][$i+1]['#attributes'] = array(
          'class' => array(
            'product-catalog-list',
          ),
        );
        $form['landing_product'][$i+1]['name'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('Pilih product catalog'),
          '#title_display' => 'invisible',
          '#attributes' => array('class' => ['input-product-catalog']),
          '#default_value' => "{$node->label()} ({$node->id()})",
        );
      }
    endfor;

    $form['landing_subdomain'] = [
      '#custom_value' => $entity->field_lan_subdomain->getString(),
      '#custom_domain' => '.' . str_replace(['http://', 'https://'], '', $_ENV['APP_URL'])
    ];

    $form['landing_domain'] = [
      '#custom_value' => $entity->field_lan_domain->getString()
    ];

    return $form;

  }
  
   /**
   * {@inheritdoc}
   */
  public function validateForm(array & $form, FormStateInterface $form_state) {
    $form_data = $form_state->getValues();

    $landing_page_name = $form_data['landing_page_name'];
    $landing_logo = $form_data['landing_page_logo'];
    $page_title = $form_data['page_title'];

    if (empty($landing_page_name)) {
      $form_state->setErrorByName('landing_page_name', $this->t("Landing page name is required"));
      return;
    };

    if (empty($landing_logo)) {
      $form_state->setErrorByName('landing_page_logo', $this->t("Landing page logo is required"));
      return;
    };
    
    if (empty($page_title)) {
      $form_state->setErrorByName('page_title', $this->t("Page title is required"));
      return;
    };
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) {
    $form_data = $form_state->getValues();

    $landing_page_name = $form_data['landing_page_name'];
    $landing_page_description = $form_data['landing_page_description'];
    $landing_page_logo = $form_data['landing_page_logo'];
    $landing_page_product = $form_data['landing_product'];

    // save data landing page project
    $landing = Node::create([
      'type'        => 'landing',
      'title'       => $landing_page_name,
      'field_lan_website_logo'    => $form_data['landing_page_logo'][0],
      'field_lan_product_catalog' => array_map(function($res){
        return ['target_id' => $res];
      }, $landing_page_product),
      'field_lan_website_description' => $landing_page_description,
    ]);
    $landing->save();

    $url_redirect = Url::fromRoute('<front>')->setAbsolute()->toString();
    $url_redirect = $url_redirect."builder/builder.php?project=".$landing->uuid();

    $response = new TrustedRedirectResponse($url_redirect);
    $form_state->setResponse($response);
  }

}
  
