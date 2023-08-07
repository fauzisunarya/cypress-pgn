<?php

namespace Drupal\restapi_telkom\Helper;

use Drupal;

class Product_helper {

  public function mappingProductKnowledge(array $data = [], $code = 'pkt')
  {
    if (empty($data)) return null;

    $result = array();

    foreach ($data as $res) :
      $parent_ref = $res->{"field_{$code}_pknowledge_parent"}->getString();
      $imageList  = !$res->get("field_{$code}_pknowledge_image")->isEmpty() ? $res->get("field_{$code}_pknowledge_image")->referencedEntities() : array();
      $fileList   = !$res->get("field_{$code}_pknowledge_file")->isEmpty() ? $res->get("field_{$code}_pknowledge_file")->referencedEntities() : array();

      $result[$parent_ref][] = array(
        'id'        => (int) $res->id(),
        'uuid'      => $res->uuid(),
        'parent_id' => (int) $parent_ref,
        'title'     => $res->label(),
        'body'      => $res->{"field_{$code}_pknowledge_body"}->getValue()[0]['value'],
        'image_list'   => !empty($imageList) ? array_map(function($img){
          return [
            'fid'        => (int) $img->id(),
            'uuid'       => $img->uuid(),
            'imageName'  => $img->label(),
            'imageMime'  => $img->getMimeType(),
            'imageSize'  => $img->getSize(),
            'imageURL'   =>"{$_ENV['APP_URL']}/restapi/v1/media_render/{$img->uuid()}",
            'created_date' => date("Y-m-d H:i:s", $img->getCreatedTime()),
            'last_edited'  => date("Y-m-d H:i:s", $img->getChangedTime())
          ];
        }, $imageList) : $imageList,
        'file_list'    => !empty($fileList) ? array_map(function($file){
          return [
            'fid'        => (int) $file->id(),
            'uuid'       => $file->uuid(),
            'fileName'   => $file->label(),
            'fileMime'   => $file->getMimeType(),
            'fileSize'   => $file->getSize(),
            'fileURL'    =>"{$_ENV['APP_URL']}/restapi/v1/media_render/{$file->uuid()}",
            'created_date' => date("Y-m-d H:i:s", $file->getCreatedTime()),
            'last_edited'  => date("Y-m-d H:i:s", $file->getChangedTime())
          ];
        }, $fileList) : $fileList,
        'created_date' => date("Y-m-d H:i:s", $res->getCreatedTime()),
        'last_update'  => date("Y-m-d H:i:s", $res->getChangedTime())
      );
    endforeach;

    // release memory
    unset($data, $res, $imageList);

    return $result;   
  }

  public function mappingProductKnowGroup(array $data = [], $code = 'pkt', $ref_name = '')
  {
    if (empty($data)) return null;

    $result = array();

    foreach ($data as $res) :
      $parent_ref   = $res->{"field_{$code}_knowledge_{$ref_name}"}->getString();
      $mainList     = !$res->get("field_{$code}_knowledge_order")->isEmpty() ? $res->{"field_{$code}_knowledge_order"}->getValue() : array();
      $priorityList = !$res->get("field_{$code}_knowledge_priority")->isEmpty() ? $res->{"field_{$code}_knowledge_priority"}->getValue() : array();
      $nonPriorityList = !$res->get("field_{$code}_knowledge_non_priority")->isEmpty() ? $res->{"field_{$code}_knowledge_non_priority"}->getValue() : array();

      $result[$parent_ref][] = array(
        'id'        => (int) $res->id(),
        'uuid'      => $res->uuid(),
        'parent_id' => (int) $parent_ref,
        'title'     => $res->label(),
        'main_list'        => !empty($mainList) ? array_map(fn($target) => (int) $target['target_id'], $mainList) : [],
        'priority_list'    => !empty($priorityList) ? array_map(fn($target) => (int) $target['target_id'], $priorityList) : [],
        'nonpriority_list' => !empty($nonPriorityList) ? array_map(fn($target) => (int) $target['target_id'], $nonPriorityList) : [],
        'created_date' => date("Y-m-d H:i:s", $res->getCreatedTime()),
        'last_update'  => date("Y-m-d H:i:s", $res->getChangedTime())
      );
    endforeach;

    return $result;
  }
}