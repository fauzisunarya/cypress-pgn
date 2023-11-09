<?php

namespace Drupal\media_upload\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\redirect\Entity\Redirect;
use Drupal\Core\Url;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\media_upload\Form\Helper;

/**
 * Provides the form for adding countries.
 */
class AddLandingPageForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_landing_page_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // validate query string type (landing page type) in url
    $resultValidate = Helper::validate_landing_page_type('add');
    if ($resultValidate['status'] === false) {
      // redirect to include "type" in url
      Drupal::service('media_upload.page_helper')->redirect($resultValidate['data']['redirect']);
    }

    // setting page theme used
    $form['#theme'] = 'landing_add_form';

    $form['landing_form_type'] = array(
      '#type' => 'hidden',
      '#value' => 'add',
    );
    $form['#attributes'] = array('autocomplete' => 'off');

    $form['landing_page_name'] = [
      '#type' => 'textfield',
      '#required' => true,
      '#title' => $this->t('Landing page name'),
	    '#attributes' => array(
        'class' => ['txt-class'],
       ),
      '#default_value' => '',
    ];

    $form['landing_page_description'] = [
      '#type' => 'textarea',
      '#required' => true,
      '#title' => $this->t('Landing page description'),
	    '#attributes' => array(
        'class' => ['txt-class'],
       ),
      '#default_value' => '',
    ];

    // $form['landing_page_logo'] = [
    //   '#type' => 'managed_file',
    //   '#title' => $this->t('Landing page logo'),
    //   '#upload_validators' => array(
    //     'file_validate_extensions' => array('gif png jpg jpeg'),
    //     'file_validate_size' => array(25600000),
    //   ),
    //   '#theme' => 'image_widget',
    //   '#preview_image_style' => 'medium',
    //   '#upload_location' => 'public://images/landing_logo/'
    // ];

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
        array('class' => 'add-landing-field-catalog form-required') // added text "Product Catalog" with css before
      ),
    );
    $form['landing_product'][1]['#attributes'] = array(
      'class' => array(
        'product-catalog-list',
      ),
    );
    $form['landing_product'][1]['name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Pilih product catalog'),
      '#title_display' => 'invisible',
      '#attributes' => array('class' => ['input-product-catalog'])
    );

    $form['addRow'] = array(
      '#type' => 'button',
      '#value' => t('Add Product Catalog'),
      '#attributes' => array(
        'id' =>  'add-row',
        'style' => 'width:30%;margin-left:13px;border: 1px solid #ccc !important'
      ),
    );

    $form['page_label'] = [
      '#type' => 'textfield',
      '#required' => true,
      '#title' => $this->t('Page label'),
	    '#attributes' => array(
        'class' => ['txt-class'],
       ),
      '#default_value' => '',
    ];

    $form['page_title'] = [
      '#type' => 'textfield',
      '#required' => true,
      '#title' => $this->t('Page title'),
	    '#attributes' => array(
        'class' => ['txt-class'],
       ),
      '#default_value' => '',
    ];

    $form['page_description'] = [
      '#type' => 'textarea',
      '#required' => true,
      '#title' => $this->t('Page description'),
	    '#attributes' => array(
        'class' => ['txt-class'],
       ),
      '#default_value' => '',
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['Save'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Submit') ,
    ];

    $form['landing_subdomain'] = [
      '#custom_value' => '',
      '#custom_domain' => '.' . str_replace(['http://', 'https://'], '', $_ENV['APP_URL'])
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
    $page_label = $form_data['page_label'];
    $page_title = $form_data['page_title'];
    $page_description = $form_data['page_description'];

    // save data landing page project
    $landing = Node::create([
      'type'        => 'landing',
      'title'       => $landing_page_name,
      'field_lan_website_logo'        => $form_data['landing_page_logo'][0],
      'field_lan_website_description' => $landing_page_description,
    ]);
    $landing->save();

    // save data pages for landing page
    $landing_page = Node::create([
      'type'        => 'landing_page',
      'field_page_landing_id' => $landing->id(),
      'field_page_type'       => 1,
      'field_website_page_label' => $page_label,
      'title' => $page_title,
      'field_website_page_description' => $page_description,
      'field_website_page_slug' => 'home',
      'field_workflow_status' => 'workflow_status_pending'
    ]);
    $landing_page->save();

    $url_redirect = Url::fromRoute('<front>')->setAbsolute()->toString();
    $url_redirect = $url_redirect."builder/builder.php?project=".$landing->uuid();

    $response = new TrustedRedirectResponse($url_redirect);
    $form_state->setResponse($response);
  }

}

