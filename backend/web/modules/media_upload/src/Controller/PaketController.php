<?php
namespace Drupal\media_upload\Controller;

use CTA15;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\File\FileSystemInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Feature12;
use Robo\Task\File\Replace;
use Symfony\Component\HttpFoundation\Response;

/**
 * Process Paket
 */
class PaketController {

  /**
   * get list image (media image)
   * 
   * This function is called from ajax [post] ajax/paket/get_media in detail paket page (image tab) and media page. 
   * This method have parameter in post data, ex: paket_id
   */
  public function get_image(){

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $query = $query->condition('status', 1)
      ->condition('type', 'paket_media')#type = bundle id (machine name)
      ->condition('field_workflow_status', 'workflow_status_approve');

    if (!empty($_POST['paket_id'])) {
      $query = $query->condition('field_media_paket_ref', $_POST['paket_id'] );
    }
    if (!empty($_POST['category_id'])) {
      $query = $query->condition('field_media_category.entity.tid', $_POST['category_id'], 'IN');
    }

    $search = !empty($_POST['search']) ? trim(strip_tags($_POST['search'])) : '';
    if (!empty($search)){
      $or_condition = $query->orConditionGroup()
                  ->condition('title', "%{$search}%", 'LIKE')
                  ->condition('field_tags', "%{$search}%", 'LIKE')
                  ->condition('field_media_description', "%{$search}%", 'LIKE');
      $query = $query->condition($or_condition);
    }

    // for pagination
    $perpage = 21;
    $request_page = !empty($_POST['request_page']) ? (int)$_POST['request_page'] : 1 ;

    $countQuery = clone $query;
    $total_data = $countQuery->count()->execute();
    $total_page = ceil($total_data/$perpage) > 0 ? (int) ceil($total_data/$perpage) : 1;

    // validate request page value
    if ($request_page!==false && $request_page>$total_page) {
      $request_page = $total_page;
    }
    elseif ($request_page!==false && $request_page<1) {
      $request_page = 1 ;
    }

    // show available page (show optional 10 page: 1-10 or 11-20 or etc)
    $start_page = ($request_page%10 === 0) ? ($request_page - 9) :  $request_page - (($request_page % 10) -1);
    $end_page = $start_page + 9;
    $available_pages = [];
    for ($i=$start_page; $i<=$end_page ; $i++) { 
      if ($i>$total_page) {
        break;
      }
      $available_pages[] = $i;
    }

    $pagination = [];
    $pagination['show'] = $total_page<=1 ? false : true; //for showing pagination or not. if false, there is no pagination showed in front end
    $pagination['current_page'] = $request_page; //the response page
    $pagination['total_page'] = $total_page;
    $pagination['available_pages'] = $available_pages;
    $pagination['next'] = $request_page === $total_page ? false : true; //for action in frontend that this response has the next page
    $pagination['prev'] = $request_page === 1 ? false : true; //for action in frontend that this response has the previous page
    
    // execute query result (with pagination)
    $media_arrobj = $entity->loadMultiple($query->sort('created' , 'DESC')->range(($request_page-1)*$perpage, $perpage)->execute());

    // define the result array
    $return = [
      'status' => true,
      'message' => 'complete',
      'category' => [], // for general (when there is empty content). each content also have category with checked boolean
      'content' => [],
      'pagination' => $pagination,
      'paket_id' => !empty($_POST['paket_id']) ? (int)$_POST['paket_id'] : null,  //for returning the request info: paket id
      'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null //for returning the request info: category id
    ];

    // general category option
    $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'paket_media']);
    foreach ($terms as $term) {
        $return['category'][$term->id()] = [
          'id' => $term->id(),
          'element_id' => str_replace(' ','',strtolower($term->label())),
          'label' => $term->label(),
          'name' => '',
          'checked' => false
        ];
    }

    // get the list media (detail)
    foreach ($media_arrobj as $key => $media_obj) {
      
      $paket_media = [
        'id' => $key,
        'title' => $media_obj->title->getString(),
        'redirect' => !$media_obj->field_media_redirect_to->isEmpty() ? $media_obj->field_media_redirect_to->getString() : '',
        'category' => [],
        'description' => $media_obj->field_media_description->getValue()[0]['value'],
        'image' => []
      ];
      
      // category option
      $paket_media['category'] = $return['category'];

      // selected category
      foreach ($media_obj->field_media_category->referencedEntities() as $category) {
        $paket_media['category'][$category->id()]['checked'] = true; //change the checked boolean
        $paket_media['category'][$category->id()]['name'] = 'category['.$category->id().']'; //change the checked boolean
      }
      
      $list_image = $media_obj->field_media_image->getValue();

      foreach ($list_image as $image) :
        $image_file = File::load($image['target_id']);

        if ($image_file) {
          $image_uri = $image_file->getFileUri();

          if (str_contains($image_uri, 's3')) {
            $image_url = "{$_ENV['APP_URL']}/restapi/v1/media_render/{$image_file->uuid()}";
          }
          else{
            $image_url = \Drupal::request()->getSchemeAndHttpHost() . \Drupal::service('file_url_generator')->generateString($image_uri);
          }

          $image = [
            'alt' => '',
            'url' => $image_url,
          ];

          $paket_media['image'][] = $image;
        };
      endforeach;

      $return['content'][] = $paket_media;
    }
    
    return new JsonResponse($return);
  }

  public function get_image_bundle(){

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $query = $query->condition('status', 1)
      ->condition('type', 'paket_media_bundle'); #type = bundle id (machine name)

    if (!empty($_POST['category_id'])) {
      $query = $query->condition('field_pmb_category.entity.tid', $_POST['category_id'], 'IN');
    }

    $search = !empty($_POST['search']) ? trim(strip_tags($_POST['search'])) : '';
    if (!empty($search)){
      $or_condition = $query->orConditionGroup()
                  ->condition('title', "%{$search}%", 'LIKE')
                  ->condition('field_tags', "%{$search}%", 'LIKE')
                  ->condition('field_pmb_description', "%{$search}%", 'LIKE');
      $query = $query->condition($or_condition);
    }

    // for pagination
    $perpage = 20;
    $request_page = !empty($_POST['request_page']) ? (int)$_POST['request_page'] : 1 ;

    $countQuery = clone $query;
    $total_data = $countQuery->count()->execute();
    $total_page = ceil($total_data/$perpage) > 0 ? (int) ceil($total_data/$perpage) : 1;
    
    // validate request page value
    if ($request_page!==false && $request_page>$total_page) {
      $request_page = $total_page;
    }
    elseif ($request_page!==false && $request_page<1) {
      $request_page = 1 ;
    }

    // show available page (show optional 10 page: 1-10 or 11-20 or etc)
    $start_page = ($request_page%10 === 0) ? ($request_page - 9) :  $request_page - (($request_page % 10) -1);
    $end_page = $start_page + 9;
    $available_pages = [];
    for ($i=$start_page; $i<=$end_page ; $i++) { 
      if ($i>$total_page) {
        break;
      }
      $available_pages[] = $i;
    }

    $pagination = [];
    $pagination['show'] = $total_page<=1 ? false : true; //for showing pagination or not. if false, there is no pagination showed in front end
    $pagination['current_page'] = $request_page; //the response page
    $pagination['total_page'] = $total_page;
    $pagination['available_pages'] = $available_pages;
    $pagination['next'] = $request_page === $total_page ? false : true; //for action in frontend that this response has the next page
    $pagination['prev'] = $request_page === 1 ? false : true; //for action in frontend that this response has the previous page

    // execute query result (with pagination)
    $media_arrobj = $entity->loadMultiple($query->sort('created' , 'DESC')->range(($request_page-1)*$perpage, $perpage)->execute());

    // define the result array
    $return = [
      'status' => true,
      'message' => 'complete',
      'category' => [], // for general (when there is empty content). each content also have category with checked boolean
      'content' => [],
      'pagination' => $pagination,
      'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null //for returning the request info: category id
    ];

    // general category option
    $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'media_bundle']);
    foreach ($terms as $term) {
        $return['category'][$term->id()] = [
          'id' => $term->id(),
          'label' => $term->label(),
          'checked' => false
        ];
    }

    // get the list media (detail)
    foreach ($media_arrobj as $media_bundle) {
      
      $media = [
        'id' => $media_bundle->id(),
        'title' => $media_bundle->title->getString(),
        'category' => [],
        'description' => count($media_bundle->field_pmb_description->getValue())===1 ? $media_bundle->field_pmb_description->getValue()[0]['value'] : '',
        'media' => [],
        'link' => \Drupal::service('media_upload.workflow_helper')->get_url_alias($media_bundle->id())
      ];
      
      // category option
      $media['category'] = $return['category'];

      // selected category
      foreach ($media_bundle->field_pmb_category->referencedEntities() as $category) {
        $media['category'][$category->id()]['checked'] = true; //change the checked boolean
      }
      
      // each media in bundle
      foreach ($media_bundle->field_pmb_media_ref->referencedEntities() as $value) {

        $each_media = [
          'title' => $value->title->getString(),
          'redirect' => !$value->field_media_redirect_to->isEmpty() ? $value->field_media_redirect_to->getString() : '',
          'description' => count($value->field_media_description->getValue())===1 ?  $value->field_media_description->getValue()[0]['value'] : '',
          'image' => []
        ];
        
        // list image for each media
        foreach ($value->field_media_image->getValue() as $image) :
          $image_file = File::load($image['target_id']);
  
          if ($image_file) {
            $image = [
              'alt' => '',
              'url' => "{$_ENV['APP_URL']}/restapi/v1/media_render/{$image_file->uuid()}",
            ];
            $each_media['image'] = $image;
          };
        endforeach;

        $media['media'][] = $each_media;
      }


      $return['content'][] = $media;
    }
    
    return new JsonResponse($return);
  }

  /**
   * save image (media image)
   * 
   * This function is called from ajax [post] ajax/paket/save_media in detail paket page (image tab) and media page. 
   * This method must have parameter in post data, ex: media_id
   */
  public function save_image(){
    if (empty($_POST['media_id'])) {
      return new JsonResponse('media_id is required', 422);
    }
    elseif(!isset($_POST['title']) || !isset($_POST['category']) || !isset($_POST['description'])){
      return new JsonResponse('field title, category, and description is required', 422);
    }

    $paket_media = \Drupal::entityTypeManager()->getStorage('node')->load($_POST['media_id']);

    if ($paket_media===null) {
      return new JsonResponse('invalid media id', 422);
    }
    elseif ($paket_media->type->entity->get('type')!=='paket_media') {
      return new JsonResponse('invalid media id', 422);
    }

    $paket_media->title = $_POST['title'];
    $paket_media->field_media_redirect_to = $_POST['redirect'];
    $paket_media->field_media_description = $_POST['description'];

    if (empty($_POST['category'])) {
      $paket_media->field_media_category = [];
    }
    else{
      $submitted_category = explode(',',$_POST['category']);
      $paket_media->field_media_category = array_map(function($value){
        return [
          'target_id' => $value
        ];
      }, $submitted_category);
    }

    // Make this change a new revision
    $paket_media->setNewRevision(TRUE);
    $paket_media->revision_log = '';
    $paket_media->setRevisionCreationTime(REQUEST_TIME);
    $paket_media->setRevisionUserId(\Drupal::currentUser()->id());

    $paket_media->save();

    $return = [
      'status' => 'success',
      'message' => 'Data Updated'
    ];

    return new JsonResponse($return);
  }

  /**
   * get list video (media video)
   * 
   * This function is called from ajax [post] ajax/paket/get_video in detail paket page (video tab) and media page. 
   * This method have parameter in post data, ex: paket_id
   */
  public function get_video(){

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $query = $query->condition('status', 1)
      ->condition('type', 'paket_video')#type = bundle id (machine name)
      ->condition('field_workflow_status', 'workflow_status_approve');

    if (!empty($_POST['paket_id'])) {
      $query = $query->condition('field_video_paket_ref', $_POST['paket_id'] );
    }
    if (!empty($_POST['category_id'])) {
      $query = $query->condition('field_video_category.entity.tid', $_POST['category_id'], 'IN');
    }

    $search = !empty($_POST['search']) ? trim(strip_tags($_POST['search'])) : '';
    if (!empty($search)){
      $or_condition = $query->orConditionGroup()
                  ->condition('title', "%{$search}%", 'LIKE')
                  ->condition('field_tags', "%{$search}%", 'LIKE')
                  ->condition('field_video_description', "%{$search}%", 'LIKE');
      $query = $query->condition($or_condition);
    }

    // for pagination
    $perpage = 21;
    $request_page = !empty($_POST['request_page']) ? (int)$_POST['request_page'] : 1 ;
    
    $countQuery = clone $query;
    $total_data = $countQuery->count()->execute();
    $total_page = ceil($total_data/$perpage) > 0 ? (int) ceil($total_data/$perpage) : 1;

    // validate request page value
    if ($request_page!==false && $request_page>$total_page) {
      $request_page = $total_page;
    }
    elseif ($request_page!==false && $request_page<1) {
      $request_page = 1 ;
    }

    // show available page (show optional 10 page: 1-10 or 11-20 or etc)
    $start_page = ($request_page%10 === 0) ? ($request_page - 9) :  $request_page - (($request_page % 10) -1);
    $end_page = $start_page + 9;
    $available_pages = [];
    for ($i=$start_page; $i<=$end_page ; $i++) { 
      if ($i>$total_page) {
        break;
      }
      $available_pages[] = $i;
    }

    $pagination = [];
    $pagination['show'] = $total_page<=1 ? false : true; //for showing pagination or not. if false, there is no pagination showed in front end
    $pagination['current_page'] = $request_page; //the response page
    $pagination['total_page'] = $total_page;
    $pagination['available_pages'] = $available_pages;
    $pagination['next'] = $request_page === $total_page ? false : true; //for action in frontend that this response has the next page
    $pagination['prev'] = $request_page === 1 ? false : true; //for action in frontend that this response has the previous page

    // execute query result (with pagination)
    $video_arrobj = $entity->loadMultiple($query->sort('created' , 'DESC')->range(($request_page-1)*$perpage, $perpage)->execute());

    // define the result array
    $return = [
      'status' => true,
      'message' => 'complete',
      'category' => [], // for general (when there is empty content). each content also have category with checked boolean
      'content' => [],
      'pagination' => $pagination,
      'paket_id' => !empty($_POST['paket_id']) ? (int)$_POST['paket_id'] : null,  //for returning the request info: paket id
      'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null //for returning the request info: category id
    ];

    // general category option
    $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'paket_video']);
    foreach ($terms as $term) {
        $return['category'][$term->id()] = [
          'id' => $term->id(),
          'element_id' => 'video-cat-'.str_replace(' ','',strtolower($term->label())),
          'label' => $term->label(),
          'name' => '',
          'checked' => false
        ];
    }

    // get the list media (detail)
    foreach ($video_arrobj as $key => $video_obj) {
      
      $paket_video = [
        'id' => $key,
        'title' => $video_obj->title->getString(),
        'category' => [],
        'description' => $video_obj->field_video_description->getString(),
        'video' => []
      ];
      
      // category option
      $paket_video['category'] = $return['category'];

      // selected category
      foreach ($video_obj->field_video_category->referencedEntities() as $category) {
        $paket_video['category'][$category->id()]['checked'] = true; //change the checked boolean
        $paket_video['category'][$category->id()]['name'] = 'category['.$category->id().']'; //change the checked boolean
      }
      
      // file video
      $list_video = $video_obj->field_video_file_video->getValue();

      foreach ($list_video as $video) :
        $video_file = File::load($video['target_id']);

        if ($video_file) {
          $video_uri = $video_file->getFileUri();

          if (str_contains($video_uri, 's3')) {
            $findS3 = \Drupal::service('restapi_telkom.minio_helper')->getFileByURI($video_uri, '', 'video');

            $video_url = $findS3['status'] ? $findS3['data'] : $video_uri;
          }
          else{
            $video_url = \Drupal::request()->getSchemeAndHttpHost() . \Drupal::service('file_url_generator')->generateString($video_uri);
          };

          $video = [
            'alt' => '',
            'url' => $video_url,
            'type' => 'file'
          ];

          $paket_video['video'][] = $video;
        };
      endforeach;

      // youtube video
      $youtube_video = trim($video_obj->field_video_youtube_video->getString());
      $is_valid_iframe = preg_match("/^<iframe.+<\/iframe>$/", $youtube_video);
      if ($is_valid_iframe) {
        $paket_video['video'][] = [
          'iframe' => $youtube_video,
          'type' => 'iframe'
        ];
      }

      $return['content'][] = $paket_video;
    }
    
    return new JsonResponse($return);
  }

  /**
   * save video (media video)
   * 
   * This function is called from ajax [post] ajax/paket/save_video in detail paket page (video tab) and media page. 
   * This method must have parameter in post data, ex: media_id
   */
  public function save_video(){
    if (empty($_POST['video_id'])) {
      return new JsonResponse('video_id is required', 422);
    }
    elseif(!isset($_POST['title']) || !isset($_POST['category']) || !isset($_POST['description'])){
      return new JsonResponse('field title, category, and description is required', 422);
    }

    $paket_video = \Drupal::entityTypeManager()->getStorage('node')->load($_POST['video_id']);

    if ($paket_video===null) {
      return new JsonResponse('invalid video id', 422);
    }
    elseif ($paket_video->type->entity->get('type')!=='paket_video') {
      return new JsonResponse('invalid video id', 422);
    }

    $paket_video->title = $_POST['title'];
    $paket_video->field_video_description = $_POST['description'];

    if (empty($_POST['category'])) {
      $paket_video->field_video_category = [];
    }
    else{
      $submitted_category = explode(',',$_POST['category']);
      $paket_video->field_video_category = array_map(function($value){
        return [
          'target_id' => $value
        ];
      }, $submitted_category);
    }

    // Make this change a new revision
    $paket_video->setNewRevision(TRUE);
    $paket_video->revision_log = '';
    $paket_video->setRevisionCreationTime(REQUEST_TIME);
    $paket_video->setRevisionUserId(\Drupal::currentUser()->id());

    $paket_video->save();

    $return = [
      'status' => 'success',
      'message' => 'Data Updated'
    ];

    return new JsonResponse($return);
  }

  /**
   * Setting Template Pricing save data
   */
  public function save_setting_template_pricing(){
    if (empty($_POST['paket_id'])) {
      return new JsonResponse('paket_id is required', 422);
    }

    $paket = \Drupal::entityTypeManager()->getStorage('node')->load($_POST['paket_id']);

    if ($paket===null) {
      return new JsonResponse('invalid paket id', 422);
    }
    elseif ($paket->type->entity->get('type')!=='paket') {
      return new JsonResponse('invalid paket id', 422);
    }

    $setting_template_pricing = json_decode($paket->field_pkt_setting_temp_pricing->getString(), true);

    if (!is_array($setting_template_pricing)) {
      $setting_template_pricing = [];
    }

    // format = ['setting_id_1'=>'value_1', 'setting_id_2'=>'value_2', etc]
    foreach ($_POST['data'] as $setting) {
      $setting_template_pricing[$setting['id']] = $setting['value'];
    }

    // save the updated data
    $paket->field_pkt_setting_temp_pricing = json_encode($setting_template_pricing);
    $paket->save();

    $return = [
      'status' => 'success',
      'message' => 'Data Updated'
    ];

    return new JsonResponse($return);
  }

  public function store_dummy_data(){
    // return new JsonResponse( [] );

    ob_start();
    include_once "output MI_DIGITAL.txt";
    $data = ob_get_contents();
    ob_end_clean();

    $data = json_decode($data, true)['data'];
    // print_r($data);

    foreach ($data as $paket) {

      $addonlist = $paket['addonList'];
      $addon_to_attach = $this->get_attached_addon($addonlist);

      $list_paket = $paket['packageXml'];
      foreach ($list_paket as $item_paket) {
        $xml_paket = $item_paket['XML_PAKET']; // it's long
        $md5_xml_paket = md5($xml_paket); // it's short, so compare string with md5

        $entity = \Drupal::entityTypeManager()->getStorage('node');
        $query = $entity->getQuery();

        $ids = $query->condition('status', 1)
          ->condition('type', 'paket')#type = bundle id (machine name)
          ->condition('field_pkt_package_id', $item_paket['PACKAGE_ID'])
          #->sort('created', 'ASC') #sorted
          #->pager(15) #limit 15 items
          ->execute();

        $id = reset($ids);

        // if paket already exist
        if ($id!==false) {
          $paket_data = \Drupal::entityTypeManager()->getStorage('node')->load($id);
          
          if ($paket_data->field_pkt_md5_xml_paket->getString() !== $md5_xml_paket) {
            // now, do nothing, because its dummy, data can't be updated

          }
        }
        else{
          // create new paket
          $id_addon = [];
          foreach ($addon_to_attach as $addon) {
            $id_addon[] = $addon['id'];
          }

          $master_data = [
            'title' => $item_paket['FLAG'],
            'sub_title' => '',
            'speed' => $item_paket['SPEED'],
            'flag' => $item_paket['FLAG'],
            'promo_text' => '',
            'xml_paket' => $xml_paket,
            'md5_xml_paket' => $md5_xml_paket,
            'detail_voice' => $item_paket['DETAIL_VOICE'],
            'detail_inet' => $item_paket['DETAIL_INET'],
            'price_voice' => $item_paket['PRICE_VOICE'],
            'price_internet' => $item_paket['PRICE_INTERNET'],
            'price_total' => $item_paket['PRICE_TOTAL'],
            'billing_period' => '',
            'tipe_paket' => $item_paket['TIPE_PAKET'],
            'kuota' => $item_paket['KUOTA'],
            'flag_json' => $item_paket['FLAG_JSON'],
            'trans_type' => $item_paket['TRANS_TYPE'],
            'service' => $item_paket['SERVICE'],
            'indihome_indicator' => $item_paket['INDIHOMEINDICATOR'],
            'package_id' => $item_paket['PACKAGE_ID'],
            'package_detail' => str_replace('|',"\n", $item_paket['PACKAGE_DETAILS']),
            'addon_list' => $addon_to_attach // in edit paket, this will be showed as string (convert array to string)
          ];

          // info: addon list have default custom value as empty string and can be filled with string text area (as description for addon list)
          // see : telkom_cms_preprocess_page()
          $master_data_edited = [
            'field' => [
              'title' =>[
                'showname' => 'TITLE',
                'hidden' => false
              ],
              'sub_title' =>[
                'showname' => 'Sub Title',
                'hidden' => false
              ],
              'flag' =>[
                'showname' => 'FLAG',
                'hidden' => false
              ],
              'speed' =>[
                'showname' => 'SPEED',
                'hidden' => false
              ],
              'promo_text' =>[
                'showname' => 'Promo Text',
                'hidden' => false
              ],
              'xml_paket' =>[
                'showname' => 'XML PAKET',
                'hidden' => true
              ],
              'detail_voice' =>[
                'showname' => 'DETAIL VOICE',
                'hidden' => false
              ],
              'detail_inet' =>[
                'showname' => 'DETAIL INTERNET',
                'hidden' => false
              ],
              'price_voice' =>[
                'showname' => 'PRICE VOICE',
                'hidden' => false
              ],
              'price_internet' =>[
                'showname' => 'PRICE INTERNET',
                'hidden' => false
              ],
              'price_total' =>[
                'showname' => 'PRICE TOTAL',
                'hidden' => false
              ],
              'billing_period' =>[
                'showname' => 'Periode Pembayaran',
                'hidden' => false
              ],
              'tipe_paket' =>[
                'showname' => 'TIPE PAKET',
                'hidden' => false
              ],
              'kuota' =>[
                'showname' => 'KUOTA',
                'hidden' => false
              ],
              'flag_json' =>[
                'showname' => 'FLAG JSON',
                'hidden' => false
              ],
              'trans_type' =>[
                'showname' => 'TRANS TYPE',
                'hidden' => false
              ],
              'service' =>[
                'showname' => 'SERVICE',
                'hidden' => false
              ],
              'indihome_indicator' =>[
                'showname' => 'INDIHOME INDICATOR',
                'hidden' => false
              ],
              'package_detail' =>[
                'showname' => 'PACKAGE DETAIL',
                'hidden' => false
              ],
              'addon_list' =>[
                'showname' => 'ADDON',
                'hidden' => false,
                'value' => '' // this is to store and then show custom description for list addon. field_pkt_addon_list is still store the referenced to content type "addon"
              ]
            ]
          ];

          $json_master_data_edited = json_encode($master_data_edited);

          $json_master_data = json_encode($master_data);


          $data_to_insert = [
            'type' => 'paket',
            'title' => $item_paket['FLAG'],
            'field_pkt_sub_title' => '',
            'field_pkt_speed' => $item_paket['SPEED'],
            'field_pkt_flag' => $item_paket['FLAG'],
            'field_pkt_promo_text' => '',
            'field_pkt_xml_paket' => $xml_paket,
            'field_pkt_md5_xml_paket' => $md5_xml_paket,
            'field_pkt_detail_voice' => $item_paket['DETAIL_VOICE'],
            'field_pkt_detail_inet' => $item_paket['DETAIL_INET'],
            'field_pkt_price_voice' => $item_paket['PRICE_VOICE'],
            'field_pkt_price_internet' => $item_paket['PRICE_INTERNET'],
            'field_pkt_price_total' => $item_paket['PRICE_TOTAL'],
            'field_pkt_billing_period' => 'bulan',
            'field_pkt_tipe_paket' => $item_paket['TIPE_PAKET'],
            'field_pkt_kuota' => $item_paket['KUOTA'],
            'field_pkt_flag_json' => $item_paket['FLAG_JSON'],
            'field_pkt_trans_type' => $item_paket['TRANS_TYPE'],
            'field_pkt_service' => $item_paket['SERVICE'],
            'field_pkt_indihome_indicator' => $item_paket['INDIHOMEINDICATOR'],
            'field_pkt_package_id' => $item_paket['PACKAGE_ID'],
            'field_pkt_package_detail' => str_replace('|',"\n", $item_paket['PACKAGE_DETAILS']),
            'field_pkt_addon_list' => $id_addon,
            'field_pkt_master_data' => $json_master_data,
            'field_pkt_master_data_edited' => $json_master_data_edited,
            'field_workflow_status' => 'workflow_status_approve'
          ];

          $paket_data = Node::create($data_to_insert);
        }
        $paket_data->save();
      }
    }

    // print_r($addonlist);


    return new JsonResponse( $data );
  }

  public function update_dummy_data(){

    // update to store: sub title, promo text, billing periode

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();

    $ids = $query->condition('status', 1)
      ->condition('type', 'paket')#type = bundle id (machine name)
      ->execute();
    
    $paket_obj = $entity->loadMultiple($ids);
    foreach ($paket_obj as $paket) {

      // do nothing if data already updated
      $updated = false;

      // update master data
      $master_data = json_decode($paket->field_pkt_master_data->getString(), true);
      $new_master_data = [];
      foreach ($master_data as $key => $value) {
        if ($key==='sub_title') {
          $updated = true;
          break;
        }

        if ($key==='speed') {
          $new_master_data['sub_title'] = ''; //add sub title before speed
        }
        elseif ($key==='xml_paket') {
          $new_master_data['promo_text'] = '';
        }
        elseif ($key==='tipe_paket') {
          $new_master_data['billing_period'] = '';
        }
        $new_master_data[$key] = $value;
      }

      // continue if data already exists
      if ($updated) {
        continue;
      }

      $paket->field_pkt_master_data = json_encode($new_master_data);

      // update master data edited
      $master_data_edited = json_decode($paket->field_pkt_master_data_edited->getString(), true);
      $new_master_data_edited = [];
      foreach ($master_data_edited['field'] as $key => $value) {
        if ($key==='flag') {
          $new_master_data_edited['field']['sub_title'] = [
            'showname' => 'Sub Title',
            'hidden' => false
          ]; //add sub title before flag
        }
        elseif ($key==='xml_paket') {
          $new_master_data_edited['field']['promo_text'] = [
            'showname' => 'Promo Text',
            'hidden' => false
          ];
        }
        elseif ($key==='tipe_paket') {
          $new_master_data_edited['field']['billing_period'] = [
            'showname' => 'Periode Pembayaran',
            'hidden' => false
          ];
        }
        $new_master_data_edited['field'][$key] = $value;
      }
      $paket->field_pkt_master_data_edited = json_encode($new_master_data_edited);

      // update the text default in field billing period
      $paket->field_pkt_billing_period = 'bulan';

      // save the updated data
      $paket->save();
    }

    return new JsonResponse(['status'=>'sukses update']);
  }

  /**
   * $addonlist is from index ['addonList']
   * @return array addon
   */
  public function get_attached_addon($addonlist){

    $addon_to_attach = [];
    foreach ($addonlist as $addon) {
      $service_xml = $addon['SERVICE_XML']; // it's long
      $md5_service_xml = md5($service_xml); // it's short, so compare string with md5

      $entity = \Drupal::entityTypeManager()->getStorage('node');
      $query = $entity->getQuery();

      $ids = $query->condition('status', 1)
        ->condition('type', 'addon')#type = bundle id (machine name)
        ->condition('field_add_md5_service_xml', $md5_service_xml)
        #->sort('created', 'ASC') #sorted
        #->pager(15) #limit 15 items
        ->execute();
        
      $id = reset($ids);

      // if addon already exist
      if ($id!==false) {
        $addon_data = \Drupal::entityTypeManager()->getStorage('node')->load($id);

        // now, do nothing, because its dummy, data cant be updated
      }
      else{

        $master_data = [
          'title' => $addon['DETAIL_PACKAGE'],
          'service_xml' => $service_xml,
          'md5_service_xml' => $md5_service_xml,
          'detail_package' => $addon['DETAIL_PACKAGE'],
          'price_total' => $addon['PRICE_TOTAL'],
          'gimmick' => $addon['GIMMICK']
        ];

        $master_data_edited = [
          'field' => [
            'title' =>[
              'showname' => 'TITLE',
              'hidden' => false
            ],
            'service_xml' =>[
              'showname' => 'SERVICE XML',
              'hidden' => false
            ],
            'detail_package' =>[
              'showname' => 'DETAIL PACKAGE',
              'hidden' => false
            ],
            'price_total' =>[
              'showname' => 'PRICE TOTAL',
              'hidden' => false
            ],
            'gimmick' =>[
              'showname' => 'GIMMICK',
              'hidden' => false
            ]
          ]
        ];

        // create new addon
        $addon_data = Node::create([
          'type' => 'addon',
          'title' => $addon['DETAIL_PACKAGE'],
          'field_add_service_xml' => $service_xml,
          'field_add_md5_service_xml' => $md5_service_xml,
          'field_add_detail_package' => $addon['DETAIL_PACKAGE'],
          'field_add_price_total' => $addon['PRICE_TOTAL'],
          'field_add_gimmick' => $addon['GIMMICK'],
          'field_add_master_data' => json_encode($master_data),
          'field_add_master_data_edited' => json_encode($master_data_edited)
        ]);
      }

      $addon_data->save();

      array_push($addon_to_attach, [
        'id' => $addon_data->id(),
        'service_xml' => $addon_data->field_add_service_xml->getString(),
        'md5_service_xml' => $addon_data->field_add_md5_service_xml->getString(),
        'detail_package' => $addon_data->field_add_detail_package->getString(),
        'price_total' => $addon_data->field_add_price_total->getString(),
        'gimmick' => $addon_data->field_add_gimmick->getString()
      ]);
    }

    return $addon_to_attach;
  }

  /**
   * $item_paket is each value in packageXml
   * @return array id products
   */
  public function get_attached_products($item_paket){
    
    $inet_detail = $item_paket['DETAIL_INET'];
    $md5_inet_detail = md5($inet_detail);
    $inet_price = $item_paket['PRICE_INTERNET'];

    $voice_detail = $item_paket['DETAIL_VOICE'];
    $md5_voice_detail = md5($voice_detail);
    $voice_price = $item_paket['PRICE_VOICE'];

    $products = [];
    $products[] = [
      'detail' => $inet_detail,
      'md5_detail' => $md5_inet_detail,
      'price' =>$inet_price
    ];
    $products[] = [
      'detail' => $voice_detail,
      'md5_detail' => $md5_voice_detail,
      'price' =>$voice_price
    ];

    $id_products = [];

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    foreach ($products as $product) {
      $query = $entity->getQuery();
      $ids = $query->condition('status', 1)
          ->condition('type', 'product')#type = bundle id (machine name)
          ->condition('field_product_md5_detail', $product['md5_detail'])
          #->sort('created', 'ASC') #sorted
          #->pager(15) #limit 15 items
          ->execute();

        $id = reset($ids);

        // if product already exist
        if ($id!==false) {
          $product_data = \Drupal::entityTypeManager()->getStorage('node')->load($id);
        }
        else{
          // create new product
          $product_data = Node::create([
            'type' => 'product',
            'title' => 'Paket Product',
            'field_product_detail' => $product['detail'],
            'field_product_md5_detail' => $product['md5_detail'],
            'field_product_price' => $product['price']
          ]);
        }
        $product_data->save();
        
        array_push($id_products, [
          'target_id' => $product_data->id()
        ]);
    }

    return $id_products;
  }

}