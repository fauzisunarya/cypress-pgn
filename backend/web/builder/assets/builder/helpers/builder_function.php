<?php
class Builder_Function {
    public $_CONFIG = array ("support_url"=>"http://support","default_url"=>"http://icreativelabs.com");
    public function views($formView, $dataPost){
        ob_start();
        extract($dataPost);
        require $formView;
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    public function getConfig($itemName){
        return @$_CONFIG[$itemName];
    }

    public function uploadFromUri($image_uri, $image_name){
		$postMaxSize 				= $this->postMaxSize;
		$uploadMaxSize 				= $this->uploadMaxSize;
		$uploadDirectory			= $this->uploadDirectory;
		$uploadSubDirectory			= $this->uploadSubdirectory;
		$uploadRelativeDirectory	= $this->uploadRelativeDirectory;
		$attachmentDirectory		= $this->attachmentDirectory;

		$fileparts      			= pathinfo($image_name);
        $webp_image_name     		= $fileparts['filename'] . '.webp';

		if( ! file_exists($uploadDirectory) ):
			if( ! mkdir($uploadDirectory, 0777, true) ):
				$failed = true; // flagging failed to crate new dir
				$message = "Can't create sub-directory. Contact adminstrator for permission.";
			endif;
		endif;

		// if image exist then return it's file name
		if( file_exists($uploadDirectory . $webp_image_name) ):
			return get_base_url() . $uploadRelativeDirectory . $webp_image_name;
		endif;

		list($width) = @getimagesize($image_uri);
		if(empty($width)):
			$failed = true;
			$message = "Image not exist, check your url.";
		endif;

		if (empty($failed)) :
			// save image from url to local
			$img = file_put_contents($uploadDirectory . $image_name, file_get_contents($image_uri));
			
			if($img):

				$info 		= getimagesize($uploadDirectory . $image_name);
				$imgWidth  	= $info[0];      // width as integer for ex. 512
				$imgHeight 	= $info[1];      // height as integer for ex. 384

				$this->load->library('image_lib');
				$image_array = array();

				foreach($this->thumbnail as $key => $thumbnail){
					$config = array();
					// resize uploaded image
					$config['image_library'] 	= 'gd2';
					$config['source_image'] 	= $uploadDirectory . $image_name;

					if($key == 'original'){
						
						$config['maintain_ratio'] 	= TRUE;

						// maximum width 1900
						if($imgWidth > 1900){
							$config['width'] = 1900;
						} else if($imgHeight > 1900){
							$config['height'] = 1900;
						}

						$this->image_lib->initialize($config);
						$this->image_lib->resize();
						$this->image_lib->clear();

						$new_image_name		= convert_to_webp($config['source_image']);
						$image_array[$key] 	= $uploadSubDirectory . $new_image_name;

					} else {

						$width				= $thumbnail[0];
						$height				= $thumbnail[1];
						$fileparts 			= pathinfo($image_name);
						$thumbnail_name		= $fileparts['filename'] . ($key == 'half' ? '-half.' : "-{$width}x{$height}.") . $fileparts['extension'];
						$thumbnal_location 	= $uploadRelativeDirectory . $thumbnail_name;

						// resize uploaded image
						$config['new_image'] 		= FCPATH . $thumbnal_location;
						$config['maintain_ratio'] 	= TRUE;
						
						if($imgWidth < $imgHeight){
							$config['width'] = $width;
						} else {
							$config['height'] = $height;
						}

						$this->image_lib->initialize($config);
						$this->image_lib->resize();
						$this->image_lib->clear();
						
						if($key !== 'half'){

							$newInfo		= getimagesize(FCPATH . $thumbnal_location);
							$newImgWidth 	= $newInfo[0];      // width as integer for ex. 512
							$newImgHeight	= $newInfo[1];      // height as integer for ex. 384

							$cropConfig['source_image'] 	= FCPATH . $thumbnal_location;
							$cropConfig['image_library'] 	= 'gd2';
							$cropConfig['maintain_ratio'] 	= FALSE;
							$cropConfig['width'] 			= $width;
							$cropConfig['height'] 			= $height;

							if ($newImgWidth < $newImgHeight) {
								$cropConfig['master_dim'] = 'width';
								$cropConfig['x_axis'] = 0;
								$cropConfig['y_axis'] = ($width * ($newImgHeight / $newImgWidth - 1)) / 2;
							}
							else {
								$cropConfig['master_dim'] = 'height';
								$cropConfig['x_axis'] = ($height * ($newImgWidth / $newImgHeight - 1)) / 2;
								$cropConfig['y_axis'] = 0;
							}

							$this->image_lib->initialize($cropConfig);
							$this->image_lib->crop();
							$this->image_lib->clear();

							$image_array[$key] = $uploadSubDirectory . convert_to_webp($cropConfig['source_image']);
						} else {
							$image_array[$key] = $uploadSubDirectory . convert_to_webp(FCPATH . $thumbnal_location);
						}
					}
				}

				// insert data attachment
				$data = array(
					"attachment_name"			=> $new_image_name,
					"attachment_original_name"	=> $new_image_name,
					"attachment_type"			=> $info['mime'],
					"attachment_size"			=> filesize($uploadDirectory . $new_image_name),
					"attachment_location"		=> $image_array['original'],
					"attachment_location_medium"=> $image_array['medium'],
					"attachment_location_small"	=> $image_array['small'],
					"attachment_location_tiny"	=> $image_array['tiny'],
					"attachment_location_half"	=> $image_array['half'],
					"uploaded_date"				=> date("Y-m-d H:i:s"),
					"uploaded_by"				=> $this->session->userdata('user_id'),
					"uploaded_ip"				=> $this->input->ip_address()
				);

                //save the data into stock foto 
				//$attachment_id 	= $this->attachment->add_attachment($data);

				return get_base_url() . $uploadRelativeDirectory . $new_image_name;
			endif;
		else:
			$result = array(
				"type" => "cant-create-dir",
				"message" => $message
			);
		endif;
	}
}
?>