<?php

namespace Drupal\media_upload\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use \Drupal\node\Entity\Node;
use Drupal\redirect\Entity\Redirect;
use Drupal\Core\Url;
use Drupal\Core\Routing\TrustedRedirectResponse;

// use Drupal\Core\Url;
// use Drupal\Core\Routing;
// use Drupal\Core\Ajax\AjaxResponse;
// use Drupal\Core\Ajax\HtmlCommand;
// use Drupal\Core\Ajax\InvokeCommand;
// use Drupal\Core\Ajax\AlertCommand;
// use Drupal\Core\Ajax\ReplaceCommand;
// use Drupal\Core\Ajax\OpenModalDialogCommand;
// use Drupal\Core\Form\FormState;
// use Drupal\Core\Link;

/**
 * Provides the form for adding countries.
 */
class AddTemplateForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_template_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $paket_id = null) {
    // $user = User::load(\Drupal::currentUser()->id());
    // dd($user->get('mail')->value);

    $form['template_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Template name'),
	    '#attributes' => array(
        'class' => ['txt-class'],
       ),
      '#default_value' => '',
    ];

    $form['template_category'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Pilih category'),
      '#type' => 'entity_autocomplete',
      '#target_type' => 'taxonomy_term',
      '#selection_settings' => [
        'target_bundles' => array('template_category'),
      ],
    ];

    $form['homepage_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Homepage label'),
	    '#attributes' => array(
        'class' => ['txt-class'],
       ),
      '#default_value' => '',
    ];

    $form['homepage_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Homepage title'),
	    '#attributes' => array(
        'class' => ['txt-class'],
       ),
      '#default_value' => '',
    ];

    $form['homepage_description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Homepage description'),
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

    return $form;

  }
  
   /**
   * {@inheritdoc}
   */
  public function validateForm(array & $form, FormStateInterface $form_state) {
    //print_r($form_state->getValues());exit;

    // clean form data
    $form_data = $form_state->getValues();
    foreach ($form_data as $key => $value) {
      $form_value = trim(strip_tags($value));
      $form_state->setValue($key, $form_value);
    }

    $form_data = $form_state->getValues();

    $template_name = $form_data['template_name'];
    $homepage_title = $form_data['homepage_title'];


    if (empty($template_name)) {
      $form_state->setErrorByName('template_name', $this->t("Template name is required"));
      return;
    }
    else if (empty($homepage_title)) {
      $form_state->setErrorByName('homepage_title', $this->t("Homepage title is required"));
      return;
    }
    // $form_state->setErrorByName('paket_id', $this->t($paket_id));
    // $form_state->setErrorByName('page_label', $this->t($page_label));
    // $form_state->setErrorByName('page_title', $this->t($page_title));
    // $form_state->setErrorByName('page_description', $this->t($page_description));
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) {

    $form_data = $form_state->getValues();

    $template_name = $form_data['template_name'];
    $homepage_label = $form_data['homepage_label'];
    $homepage_title = $form_data['homepage_title'];
    $homepage_description = $form_data['homepage_description'];
    $template_category = $form_data['template_category'];

    // save data template project
    if(!empty($template_category)){
      $template = Node::create([
        'type'        => 'template',
        'title'       => $template_name,
        'field_tem_category' => [
          'target_id' => $template_category
        ]
      ]);
    }
    else{
      $template = Node::create([
        'type'        => 'template',
        'title'       => $template_name
      ]);
    }
    $template->save();

    // save data pages for landing page
    $template_page = Node::create([
      'type'        => 'template_page',
      'title' => $homepage_title,
      'field_tem_page_template_id' => $template->id(),
      'field_tem_page_label' => $homepage_label,
      'field_tem_page_slug' => 'home',
      'field_tem_page_description' => $homepage_description,
      'field_tem_page_type' => 1,
      'field_tem_page_blocks' => '[]',
    ]);
    $template_page->save();

    $url_redirect = Url::fromRoute('<front>')->setAbsolute()->toString();
    $url_redirect = $url_redirect."builder/builder.php?project=".$template->uuid();

    $response = new TrustedRedirectResponse($url_redirect);
    $form_state->setResponse($response);
  }

}
  
