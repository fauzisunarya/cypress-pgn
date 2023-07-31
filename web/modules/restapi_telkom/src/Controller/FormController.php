<?php

namespace Drupal\restapi_telkom\Controller;

use Contact8;
use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;

class FormController extends ControllerBase{

  // For : /restapi/v1/form/list
  public function list(Request $request)
  {
    $entity = \Drupal::entityTypeManager()->getStorage('node');

    $page         = 1;//$request->query->get('page') ?? 1;
    $perpage      = 10;//$request->query->get('perpage') ?? 10;
    // include template html or not
    $with_template = $request->query->get('with_template') ?? '';
    $with_template = !empty($request->query->get('with_template')) && $with_template==='true' ? true : false;

    // load landing page "for marketplace". 
    // uuid in local, dev, prod are same (using export node which identified by uuid)
    $landing = $entity->loadByProperties(['uuid'=> $_ENV['FORM_EMBEDDED_LANDING_ID']]);
    $landing = current($landing);

    if (empty($landing)) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'data not found',
        'data'    => []
      ], 400);
    }

    $ids = $entity->getQuery()
      ->condition('status', 1)
      ->condition('type', 'landing_custom_form')#type = bundle id (machine name)
      ->condition('field_lcf_landing_ref', $landing->id()) //list saved templates for landing page
      ->range(($page-1)*$perpage, $perpage)
      ->execute();

    // get asset js & css
    $templateCssJs = [
      'css' => \Drupal::service('restapi_telkom.form_template_helper')->getCss(),
      'js_head' => \Drupal::service('restapi_telkom.form_template_helper')->getJs('head'),
      'js_bottom' => \Drupal::service('restapi_telkom.form_template_helper')->getJs('bottom')
    ];

    // get default form
    $default_forms = array_map(function($form) use($with_template, $templateCssJs){
      $result = [
        'id' => $form['form_id'],
        'name' => $form['form_name'],
      ];
      if ($with_template) {
        $result = array_merge($result, $this->getFormHtml(\Drupal::service('uuid')->generate(), $result['id'], 'Form Berlangganan', 'Silahkan isi form di bawah ini', $templateCssJs));
      }
      return $result;
    }, \Drupal::service('media_upload.landingform_helper')->get_default_forms());

    // get custom form
    $custom_forms = array_map(function($form) use($with_template, $templateCssJs){
      $result = [
        'id' => $form->id(),
        'name' => $form->label(),
      ];
      if ($with_template) {
        $result = array_merge($result, $this->getFormHtml(\Drupal::service('uuid')->generate(), $form->id(), 'Form Berlangganan', 'Silahkan isi form di bawah ini', $templateCssJs));
      }
      return $result;
    }, $entity->loadMultiple($ids));

    return \Drupal::service('restapi_telkom.app_helper')->response([
      'status'     => 'success',
      'message'    => 'success to retrieve data',
      'data'       => array_values(array_merge($default_forms, $custom_forms))
    ]);
  }

  // For : /restapi/v1/form/{id}
  public function detail(Request $request, $id) {

    $title = $request->query->get('title') ?? '';
    $description = $request->query->get('description') ?? '';

    $form = [];

    // this is default form for telkom cms, the id is the string text, the id of custom form is integer
    // ex : psb-mydita-basic
    if (! preg_match("/^\d+$/", $id)) {
      $result = \Drupal::service('media_upload.landingform_helper')->get_default_forms($id);
      if (!empty($result)) {
        $form['id'] = $id;
        $form['name'] = $result['form_name']; 
      }
    }
    else {

      // form id is number
      $custom_form = Node::load($id);
      if ($custom_form && $custom_form->bundle()==='landing_custom_form') {
        $form['id'] = $id;
        $form['name'] = $custom_form->label();
      }
    }

    // validate form
    if (empty($form)) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'invalid form id',
        'data'    => []
      ], 400);
    }

    $templateCssJs = [
      'css' => \Drupal::service('restapi_telkom.form_template_helper')->getCss(),
      'js_head' => \Drupal::service('restapi_telkom.form_template_helper')->getJs('head'),
      'js_bottom' => \Drupal::service('restapi_telkom.form_template_helper')->getJs('bottom')
    ];

    // generate html
    $form = array_merge($form, $this->getFormHtml(\Drupal::service('uuid')->generate(), $form['id'], $title, $description, $templateCssJs));

    return \Drupal::service('restapi_telkom.app_helper')->response([
      'status'     => 'success',
      'message'    => !empty($form['template']) ? 'success to retrieve data' : 'failed generate html',
      'data'       => !empty($form['template']) ? $form : null
    ]);
  }

  private function getFormHtml($formUuid, $formId, $formTitle='', $formDescription='', array $template = []) {
    return \Drupal::service('restapi_telkom.form_template_helper')->getFormHtml($formUuid, $formId, $formTitle, $formDescription, $template);
  }
   
}