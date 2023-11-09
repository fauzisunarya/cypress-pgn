<?php
namespace Drupal\media_upload\Controller;

use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\File\FileSystemInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;

class ProductCatalogController {
  
  public function search(Request $request) {
    $search_query = $request->query->get('search');

    $result = array();
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query  = $entity->getQuery();

    $data = $query->condition('status', 1)
      ->condition('type', 'product_catalog')
      ->condition('title', "%{$search_query}%", 'LIKE')
      ->execute();
    
    foreach ($entity->loadMultiple($data) as $entity_id => $entity_obj) :
      $result[] = array(
        'nid'  => $entity_obj->id(),
        'uuid' => $entity_obj->uuid(),
        'name' => $entity_obj->label()
      );
    endforeach;

    return new JsonResponse($result);
  }

  /**
   * Setting Template Pricing save data
   */
  public function save_setting_template_pricing(){
    if (empty($_POST['product_catalog_id'])) {
      return new JsonResponse('product_catalog_id is required', 422);
    }

    $catalog = \Drupal::entityTypeManager()->getStorage('node')->load($_POST['product_catalog_id']);

    if ($catalog===null) {
      return new JsonResponse('invalid product catalog id', 422);
    }
    elseif ($catalog->type->entity->get('type')!=='product_catalog') {
      return new JsonResponse('invalid product catalog id', 422);
    }

    $setting_template_pricing = json_decode($catalog->field_pct_setting_temp_pricing->getString(), true);

    if (!is_array($setting_template_pricing)) {
      $setting_template_pricing = [];
    }

    // format = ['setting_id_1'=> true, 'setting_id_2'=> false, etc] true = this setting is showed
    foreach ($_POST['data'] as $setting) {
      $setting_template_pricing[$setting['id']] = $setting['value']==='false' ? false : true;
    }

    // save the updated data
    $catalog->field_pct_setting_temp_pricing = json_encode($setting_template_pricing);
    $catalog->save();

    $return = [
      'status' => 'success',
      'message' => 'Data Updated'
    ];

    return new JsonResponse($return);
  }


  // NOTE : save_image & save_video is same with method in PaketController. use the same endpoint to save (method in paket controller)

  /**
   * get list image (media image)
   * 
   * This function is called from ajax [post] ajax/catalog/get_media in detail product catalog page (image tab). 
   * This method have parameter in post data, ex: catalog_id
   */
  public function get_image(){

    // validate catalog id
    if (empty($_POST['catalog_id'])) {
      return new JsonResponse('catalog_id is required', 422);
    }
    $catalog = \Drupal::entityTypeManager()->getStorage('node')->load($_POST['catalog_id']);

    if ($catalog===null) {
      return new JsonResponse('invalid catalog_id', 422);
    }
    elseif ($catalog->type->entity->get('type')!=='product_catalog') {
      return new JsonResponse('invalid catalog_id', 422);
    }

    // get array of list media id. 
    // catalog has multi paket, and each paket has media (image). So, get all id of catalog & paket
    $arr_media_id = [ $catalog->id() ];

    $arr_list_paket = $catalog->field_pct_list_paket->getValue();
    foreach ($arr_list_paket as $value) {
      if (!empty($value['target_id'])) {
        $arr_media_id[] = $value['target_id'];
      }
    }
    
    // set query
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $query = $query->condition('status', 1)
    ->condition('type', 'paket_media')#type = bundle id (machine name)
    ->condition('field_workflow_status', 'workflow_status_approve');
    
    // get all paket media (image) for that catalog and child (paket)
    $query = $query->condition('field_media_paket_ref', $arr_media_id, 'IN' );

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

    $media_arrobj = $entity->loadMultiple($query->sort('created' , 'DESC')->range(($request_page-1)*$perpage, $perpage)->execute());

    // define the result array
    $return = [
      'status' => true,
      'message' => 'complete',
      'category' => [], // for general (when there is empty content). each content also have category with checked boolean
      'content' => [],
      'pagination' => $pagination,
      'catalog_id' => !empty($_POST['catalog_id']) ? (int)$_POST['catalog_id'] : null,  //for returning the request info: paket id
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

  /**
   * get list video (media video)
   * 
   * This function is called from ajax [post] ajax/catalog/get_video in detail product catalog page (video tab). 
   * This method have parameter in post data, ex: catalog_id
   */
  public function get_video(){

    // validate catalog id
    if (empty($_POST['catalog_id'])) {
      return new JsonResponse('catalog_id is required', 422);
    }
    $catalog = \Drupal::entityTypeManager()->getStorage('node')->load($_POST['catalog_id']);

    if ($catalog===null) {
      return new JsonResponse('invalid catalog_id', 422);
    }
    elseif ($catalog->type->entity->get('type')!=='product_catalog') {
      return new JsonResponse('invalid catalog_id', 422);
    }

    // get array of list media id. 
    // catalog has multi paket, and each paket has media (video). So, get all id of catalog & paket
    $arr_media_id = [ $catalog->id() ];

    $arr_list_paket = $catalog->field_pct_list_paket->getValue();
    foreach ($arr_list_paket as $value) {
      if (!empty($value['target_id'])) {
        $arr_media_id[] = $value['target_id'];
      }
    }
    
    // set query
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $query = $query->condition('status', 1)
    ->condition('type', 'paket_video')#type = bundle id (machine name)
    ->condition('field_workflow_status', 'workflow_status_approve');
    
    // get all paket media (image) for that catalog and child (paket)
    $query = $query->condition('field_video_paket_ref', $arr_media_id, 'IN' );

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

    $video_arrobj = $entity->loadMultiple($query->sort('created' , 'DESC')->range(($request_page-1)*$perpage, $perpage)->execute());

    // define the result array
    $return = [
      'status' => true,
      'message' => 'complete',
      'category' => [], // for general (when there is empty content). each content also have category with checked boolean
      'content' => [],
      'pagination' => $pagination,
      'catalog_id' => !empty($_POST['catalog_id']) ? (int)$_POST['catalog_id'] : null,  //for returning the request info: paket id
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

}