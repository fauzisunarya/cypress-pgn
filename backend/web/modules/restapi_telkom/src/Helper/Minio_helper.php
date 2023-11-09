<?php
namespace Drupal\restapi_telkom\Helper;

use Drupal;
use Exception;
use Drupal\file\Entity\File;
use Drupal\Core\File\FileSystemInterface;
use Drupal\image\Entity\ImageStyle;

class Minio_helper {

  /**
   * The S3fs Service.
   *
   * @var \Drupal\s3fs\S3fsServiceInterface
   */
  protected $s3fs = NULL;

  /**
   * The AWS SDK for PHP S3Client object.
   *
   * @var \Aws\S3\S3Client
   */
  protected $s3 = NULL;

  /**
   * Module configuration for stream.
   *
   * @var array
   */
  protected $config = [];

  /**
   * Minio_helper constructor.
   *
   * Creates the \Aws\S3\S3Client client object and activates the options
   * specified on the S3 File System Settings page.
   *
   */
  public function __construct()
  {
    $this->s3fs = Drupal::service('s3fs');

    foreach (Drupal::config('s3fs.settings')->get() as $prop => $value){
      $this->config[$prop] = $value;
    };

    $this->s3 = $this->s3fs->getAmazonS3Client($this->config);
  }

  /**
   * Upload file into minio server storage
   *
   * @param object $file_link
   *   File interface from $_FILES.
   * @param int | array $node_link
   *   Node id to link file with existing node data.
   *
   * @return Array
   *   The fully-configured image data
   * 
   */
  public function uploadFile($file_link, $node_link = null)
  {
    if ( !is_object($file_link) OR 
      !method_exists($file_link, 'getRealPath') OR 
      !method_exists($file_link, 'getClientOriginalName') ) 
      return $this->errorNotice('file is not instance from symfony request interface');

    // prepare variable
    $folderLoc   = date("Y-m");
    $image_large = ImageStyle::load('large');
    $image_thumb = ImageStyle::load('thumbnail');

    // save file first
    try {
      $filesaved = Drupal::service('file.repository')->writeData(
        file_get_contents($file_link->getRealPath()), 
        "s3://{$folderLoc}/" . str_replace(' ', '_', $file_link->getClientOriginalName()), 
        FileSystemInterface::EXISTS_RENAME
      );
    }
    catch (Exception $e) {
      $error = $e->getMessage();
    }

    if (!empty($filesaved)) :
      // prepare variable
      $file_uri = $filesaved->getFileUri();
      
      // set status to permanent so that its not deleted
      $filesaved->setPermanent();
      $filesaved->save();

      // make thumbnail if file is image
      if (str_contains($filesaved->getMimeType() , 'image')) {
        // generate thumbnail image
        $largeURI    = $image_large->buildUri($file_uri);
        $thumbURI    = $image_thumb->buildUri($file_uri);
        $statusLarge = $image_large->createDerivative($file_uri, $image_large->buildUri($file_uri));
        $statusThumb = $image_thumb->createDerivative($file_uri, $image_thumb->buildUri($file_uri));
        // set file usage flagging
        if (!empty($node_link) && is_array($node_link)) :
          // data is array
          foreach ($node_link as $key => $value) {
            foreach ($value as $node_id) :
              Drupal::service('file.usage')->add($filesaved, 'file', $key, $node_id);
            endforeach; 
          };
        else:
          // data is single key
          Drupal::service('file.usage')->add($filesaved, 'file', 'node', (!empty($node_link) ? $node_link : 0));
        endif;
        // store data
        $imageStyles = ['large' => $largeURI, 'thumbnail' => $thumbURI];
      };

      return [
        'status'  => true,
        'message' => 'success to upload into minio server',
        'data'    => [
          'fid'        => (int) $filesaved->id(),
          'uuid'       => $filesaved->uuid(),
          'filename'   => $filesaved->getFilename(),
          'fileURI'    => $file_uri,
          'filemime'   => $filesaved->getMimeType(),
          'filesize'   => $filesaved->getSize(),
          'filestyles' => !empty($imageStyles) ? $imageStyles : array(),
          'fileused'   => Drupal::service('file.usage')->listUsage($filesaved),
          'created_date' => date("Y-m-d H:i:s", $filesaved->getCreatedTime()),
          'last_update'  => date("Y-m-d H:i:s", $filesaved->getChangedTime())
        ]
      ];
    endif;

    return [
      'status'  => false,
      'message' => 'failed to upload file into minio server',
      'data'    => !empty($error) ? $error : array()
    ];
  }

  /**
   * Delete file from minio server
   *
   * @param int | object $file_id
   *   File interface from drupal FileInterface
   *
   * @return Array
   *   The fully-configured image data
   * 
   */
  public function deleteFile($file_id)
  {
    // prepare variable
    $file      = is_object($file_id) ? $file_id : File::load($file_id);
    $fileUsage = Drupal::service('file.usage');

    if (!empty($file)) :
      $usages = $fileUsage->listUsage($file);

      // cases when file usage history still intact
      if (!empty($usages)) {
        // delete file usage history
        foreach ($usages['file'] as $usageKey => $usage) :
          foreach ($usage as $nodeKey => $node) {
            $fileUsage->delete($file, 'file', $usageKey, $nodeKey);
          };
        endforeach;

        // delete file
        $file->delete();
      }
      else{
        // delete file
        $file->delete();
      };

      return [
        'status'  => true,
        'message' => 'success delete file from server'
      ];
    endif;

    return [
      'status'  => false,
      'message' => 'failed to delete file from server, data is not found'
    ]; 
  }

  /**
   * replace existing file with the new one
   *
   * @param int $exist_file
   *   File id for drupal file node
   * @param object $new_file
   *   File interface from $_FILES.
   *
   * @return Array
   *   The fully-configured image data
   * 
   */
  public function replaceFile(int $exist_file, $new_file)
  {
    if ( !is_object($new_file) OR 
      !method_exists($new_file, 'getRealPath') OR 
      !method_exists($new_file, 'getClientOriginalName') ) 
      return $this->errorNotice('file is not instance from symfony request interface');

    // prepare variable
    $file = File::load($exist_file);

    if (!empty($file)) :
      $fileUsage    = Drupal::service('file.usage')->listUsage($file);
      $usageHistory = array();

      // copy file usage history
      if (!empty($fileUsage['file'])) {
        foreach ($fileUsage['file'] as $usageKey => $usage) :
          $usageHistory[$usageKey] = array_map(function($res){
            return $res;
          }, array_keys($usage));
        endforeach;
      };

      // release memory
      unset($fileUsage, $usageKey, $usage, $nodeKey, $node);

      $deleted = $this->deleteFile($file);

      if ($deleted['status']) {
        // release memory
        unset($deleted);

        return $this->uploadFile($new_file, $usageHistory);
      }
      else{
        return $deleted;
      };
    endif;

    return [
      'status' => false,
      'message' => 'Existing file not found',
      'data' => array()
    ];
  }

  /**
   * Retrieve image public URI based on file id
   *
   * @param Int $file_id
   *   File id from drupal node.
   * @param String $size
   *   original | large | thumbnail.
   *
   * @return Array
   *   The fully-configured image data
   *
   */
  public function getFileTemp($file_id, $size = 'thumbnail', $raw = false)
  {
    // to check whenever this is id or object instance
    $fileData = is_object($file_id) ? $file_id : File::load($file_id);

    if (!empty($fileData) && str_contains($fileData->getFileUri() , 's3')):
      // prepare variable
      $fileCreated  = date("Y-m", $fileData->getCreatedTime());
      $fileRealname = $fileData->getFilename();
      $fileMime     = $fileData->getMimeType();
      $fileType     = str_contains($fileData->getFileUri(), 'pictures');

      // free memory
      unset($fileData);

      try {
        // make request to server
        $request = $this->s3->getObject([
          'Bucket' => $this->config['bucket'],
          'Key'    => ($size === 'original') ? 
            ($fileType ? "pictures/" : "" ) . "{$fileCreated}/$fileRealname" :
            "styles/{$size}/s3/". ($fileType ? "pictures/" : "" ) ."{$fileCreated}/{$fileRealname}"
        ]);
      } catch (Exception $e) {
        $error = $e->getMessage();
      }

      //Get the pre-signed URL
      return empty($error) && !empty($request) ? [
        'status' => true,
        'message' => 'File success retrieved',
        'data' => $raw ? $request['Body']->getContents() : sprintf("data:%s;base64,%s", $fileMime, base64_encode($request['Body']->getContents()))
      ] : [
        'status' => false,
        'message' => 'File failed to retrieve',
        'data' => $error
      ];
    else:
      return ['status' => false, 'message' => 'File not found or not stored in S3 File System', 'data' => null];
    endif;
  }

  /**
   * Retrieve file based on File ID
   *
   * @param String $idFile
   *   File id or object instance.
   * @param String $size
   *   original | large | thumbnail.
   *
   * @return Array
   *   The fully-configured image data
   *
   */
  public function getFileById($idFile, $size = 'original', $type = 'info', $available = '+30 minutes', $raw = false)
  {
    // to check whenever this is id or object instance
    $fileData = is_object($idFile) ? $idFile : File::load($idFile);

    if (!empty($fileData) && str_contains($fileData->getFileUri() , 's3')):

      $fileInfo = $this->methodSelector('info', ['file' => $fileData]);

      if ($type !== 'info') :
        $fileType     = str_contains($fileInfo['data']['filemime'], 'video') ? 'video' : 'image';
        $generateFile = $this->methodSelector($fileType, ['file' => $fileData, 'size' => $size, 'raw' => $raw]);

        // replace existing fileURI data
        $fileInfo['data']['fileURI'] = $generateFile['data'];

        return $fileInfo;
      endif;

      return $fileInfo;
    else:
      return ['status' => false, 'message' => 'File not found or not stored in S3 File System', 'data' => null];
    endif;
  }

  /**
   * Retrieve file public URI based on File URI
   *
   * @param String $linkURI
   *   File properties based on real uri on database Or link generated by drupal system.
   * @param String $size
   *   original | large | thumbnail.
   *
   * @return Array
   *   The fully-configured image data
   *
   */
  public function getFileByURI($linkURI, $size = 'thumbnail', $type = 'image', $available = '+30 minutes', $raw = false)
  {
    $linkURI    = urldecode($linkURI);
    $searchData = Drupal::entityTypeManager()->getStorage('file')->loadByProperties(['uri' => $linkURI]);
    $fileData   = !empty($searchData) ? current($searchData) : null;

    // this is actual database URI
    if (!empty($fileData)):
      // call core function
      return $this->methodSelector($type, ['file' => $fileData, 'size' => $size, 'avail' => $available, 'raw' => $raw]);

    // this is URI generated string
    else:
      // find s3 word occurrence
      $count = substr_count($linkURI, 's3');
      // find URI based on regex function
      preg_match(sprintf("/(?:s3(.*)){%s}\?/is", $count), $linkURI, $match);

      if (!empty($match[1])){
        // prepare variable
        $fileManage = Drupal::service('file.usage');
        $result = Drupal::entityTypeManager()->getStorage('file')
          ->getQuery()
          ->condition('uri', "%{$match[1]}%", 'LIKE')
          ->execute();

        // in case found more than identical files
        if (count($result) > 1) :
          foreach ($result as $file) {
            $searchImg = File::load($file);

            if (!empty($fileManage->listUsage($searchImg))) {
              $usedImage = $searchImg;
              break;
            };
          };
        // retrieve first index data
        else:
          $usedImage = current($result);
        endif;

        // free memory
        unset($fileManage, $result, $searchImg, $match, $count);

        // call core function
        return $this->methodSelector($type, ['file' => $usedImage, 'size' => $size, 'avail' => $available, 'raw' => $raw]);
      }
      else{
        return ['status' => false, 'message' => "File with URI : {$linkURI} , is not found", 'data' => null];
      };
    endif;
  }

  /**
   * Retrieve video public URI based on file id
   *
   * @param Int $file_id
   *   File id from drupal node.
   * @param String $available
   *   File availability within given minutes.
   *
   * @return Array
   *   The fully-configured image data
   *
   */
  public function getVideoTemp($file_id, $available = '+30 minutes')
  {
    // to check whenever this is id or object instance
    $fileData = is_object($file_id) ? $file_id : File::load($file_id);

    if (!empty($fileData) && str_contains($fileData->getFileUri() , 's3')):
      // check is this file is an image
      if (!str_contains($fileData->getMimeType() , 'video')){
        return ['status' => false, 'message' => 'File is not a video', 'data' => null];
      };

      // prepare variable
      $fileCreated  = date("Y-m", $fileData->getCreatedTime());
      $fileRealname = $fileData->getFilename();

      // free memory
      unset($fileData);

      try {
        // make request to server
        $request = $this->s3->getCommand('GetObject', [
          'Bucket' => $this->config['bucket'],
          'Key'    => "{$fileCreated}/$fileRealname"
        ]);

        // The period of availability
        $result = $this->s3->createPresignedRequest($request, $available);
      } catch (Exception $e) {
        $error = $e->getMessage();
      }

      //Get the pre-signed URL
      return empty($error) && !empty($result) ? [
        'status' => true,
        'message' => 'File video retrieved',
        'data' => (string) $result->getUri()
      ] : [
        'status' => false,
        'message' => 'File video failed to retrieve',
        'data' => $error
      ];
    else:
      return ['status' => false, 'message' => 'File not found or not stored in S3 File System', 'data' => null];
    endif;
  }

  /**
   * internal function to determine which function should use
   */
  private function methodSelector(string $method, array $params)
  {
    switch (strtolower($method)) {
      case 'image':
        return $this->getFileTemp($params['file'], $params['size'], $params['raw']);
      break;

      case 'video':
        return $this->getVideoTemp($params['file'], $params['avail']);
      break;

      case 'delete':
        return $this->deleteFile($params['file']);
      break;

      case 'info':
        return array(
          'status'  => true,
          'message' => 'success retrieved',
          'data'    => [
            'fid'        => (int) $params['file']->id(),
            'uuid'       => $params['file']->uuid(),
            'filename'   => $params['file']->getFilename(),
            'fileURI'    => $params['file']->getFileUri(),
            'filemime'   => $params['file']->getMimeType(),
            'filesize'   => $params['file']->getSize(),
            'filestyles' => !empty($imageStyles) ? $imageStyles : array(),
            'fileused'   => Drupal::service('file.usage')->listUsage($params['file']),
            'created_date' => date("Y-m-d H:i:s", $params['file']->getCreatedTime()),
            'last_update'  => date("Y-m-d H:i:s", $params['file']->getChangedTime())
          ]
        );
      break;
      
      default:
        return $this->errorNotice('Method not available');
      break;
    }
  }

  /**
   * internal function to return error notice
   */
  private function errorNotice($msg = '')
  {
    return [
      'status'  => false,
      'message' => $msg,
      'data'    => array()
    ];
  }

}
  
