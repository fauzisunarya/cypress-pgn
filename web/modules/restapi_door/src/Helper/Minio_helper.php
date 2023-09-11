<?php
namespace Drupal\restapi_door\Helper;

use Drupal;
use Drupal\restapi_door\Helper\Result;

class Minio_helper {

	protected $s3fs = NULL;
  	protected $s3 = NULL;
	protected $_config = [];

	protected $client = NULL;

    public function __construct($config = array()) {

		$this->_config['user'] = getenv('MINIO_USER');
		$this->_config['secret'] = getenv('MINIO_SECRET_KEY');
		$this->_config['region'] = getenv('MINIO_REGION');
		$this->_config['host'] = getenv('MINIO_HOST');
		$this->_config['bucket'] = getenv('MINIO_BUCKET');
		$this->_config['upload_path'] = getenv('MINIO_UPLOADPATH');
		
        $file = getcwd() . '/../vendor/aws/aws-sdk-php/src/AwsClient.php';
		if (file_exists($file)){
            if(!class_exists('\Aws\S3\S3Client')){
                require_once $file;
            }
            $this->client = new \Aws\S3\S3Client([
                'version' => 'latest',
                'region'  => $this->_config['region'],
                'endpoint' => $this->_config['host'],
                'use_path_style_endpoint' => true,
                'credentials' => [
                    'key'    => $this->_config['user'],
                    'secret' => $this->_config['secret'],
                ],
            ]);
        }
    }

	public function upload($file, $subpath = null){
		$result= new Result();
		$bucket = $this->_config['bucket'];
		$filename = $file['filename'];
		$imageData = base64_decode(end(explode(",", $file['file'])));
		$key = $this->_config['upload_path'].date('Y/m/d/');

		try {
			$insert = $this->client->putObject(array(
				'Bucket'          => $bucket,
				'Key'             => $key.$filename,
				'Body'            => $imageData,
				'ContentType'     => $file['mimeType'],
			));
			 $statusCode = $insert->get('@metadata')['statusCode'];
            if ($statusCode === 200) {
                $result->info= "OK";
                $result->data = $key;
            } else {
                $result->code = 1;
                $result->info = "Upload failed, please try again later.";
            }
        } catch (\Aws\Exception\AwsException $e) {
            $result->code = $e->getStatusCode();
            $result->info = $e->getAwsErrorMessage();
        }

        return $result;
    }

	public function download($key) {

        $this->getObject([
            'Bucket' => $this->_config['bucket'],
            'Key'    => $key,
        ]);
    }

	public function generateLocalUrl($key){

        if ($route = $this->_config['download_route']) {
            return '/' . trim($route, '/') .  '/' . $this->_config['bucket'] . '/' . $key;
        } else {
            return '/uploads/cms/' . $this->_config['bucket'] . '/' . $key;
        }
    }

    public function getObject($key){
        $retrieve = $this->client->getObject([
            'Bucket' => $this->_config['bucket'],
            'Key'    => $key,
        ]);

        return $retrieve;
    }

    public function deleteObject($params){
        return $this->client->deleteObject($params);
    }

    public function delete($key) {
        $result = new Result();

        try {
            $this->deleteObject([
                'Bucket' => $this->_config['bucket'],
                'Key'    => $key,
            ]);
        } catch (\Aws\S3\Exception\S3Exception $e) {
            $result->code = 2;
            $result->info = 'Error: ' . $e->getAwsErrorMessage() . PHP_EOL;
        }

        return $result;
    }
}
  
