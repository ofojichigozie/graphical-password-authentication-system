<?php 
    require_once "database.php";
?>

<?php
	function redirect_to($location = NULL) {
		if ($location != NULL) {
			header("Location: {$location}");
			exit;
		}
	}

	function gpasCore($userImgPoint00 = NULL, $userImgPoint01 = NULL, $userImgPoint02 = NULL, $dbImgPoint00 = NULL, $dbImgPoint01 = NULL, $dbImgPoint02 = NULL){
		if(($userImgPoint00 != NULL) & ($userImgPoint01 != NULL) & ($userImgPoint02 != NULL) & ($dbImgPoint00 != NULL) & ($dbImgPoint01 != NULL) & ($dbImgPoint02 != NULL)){
			//Variable to track number of correct points
			$validPointCounter = 0;

			//Check the first point
			if(($userImgPoint00 == $dbImgPoint00) || ((($dbImgPoint00 - 10) <= $userImgPoint00) && ($userImgPoint00 <= ($dbImgPoint00 + 10)))){
				$validPointCounter++; //1
			}

			//Check the second point
			if(($userImgPoint01 == $dbImgPoint01) || ((($dbImgPoint01 - 10) <= $userImgPoint01) && ($userImgPoint01 <= ($dbImgPoint01 + 10)))){
				$validPointCounter++; //2
			}

			//Check the third point
			if(($userImgPoint01 == $dbImgPoint01) || ((($dbImgPoint02 - 10) <= $userImgPoint02) && ($userImgPoint02 <= ($dbImgPoint02 + 10)))){
				$validPointCounter++; //3
			}

			//Are the three points correct?
			if($validPointCounter == 3){
				return true;
			}else{
				return false;
			}
		}
	}

	function handleImageUpload($hashedPassword){
		//SECTION FOR HANLING FILES UPLOADED
		$uploadErrMsg = "";
		
		//Array to hold error messages using key value pair
		$upload_errors = array(
			UPLOAD_ERR_OK         => "No errors.",
			UPLOAD_ERR_INI_SIZE   => "Larger than upload_max_filesize.",
			UPLOAD_ERR_FORM_SIZE  => "Larger than form MAX_FILE_SIZE.",
			UPLOAD_ERR_PARTIAL    => "Partial upload.",
			UPLOAD_ERR_NO_FILE    => "No file.",
			UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
			UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
			UPLOAD_ERR_EXTENSION  => "File upload stopped by extension."
		);

		 //Check for error and get the corresponding message
		 $error = $_FILES['file']['error'];
		 if($error == 0){
		   //Check if uploaded file is an image
		   if(($_FILES['file']['type'] == "image/png") || ($_FILES['file']['type'] == "image/jpeg")){
		     //Get temporary and target file names
		     $temporaryFilename = $_FILES['file']['tmp_name'];
		     $targetFilename = "Store/Uploads/" . $hashedPassword . basename($_FILES["file"]["name"]);

		     //Check the width and height of the uploaded image
		     $imageSize = getimagesize($temporaryFilename);
		     $imageWidth = $imageSize[0];
		     $imageHeight = $imageSize[1];

		     if(($imageWidth <= 600) & ($imageHeight <= 600)){
		     	// Move the uploaded file to directory
		     	if(move_uploaded_file($temporaryFilename, $targetFilename)){
		     	  return array("status" => "success", "message" => $targetFilename);
		     	}else{
		     		return array("status" => "failed", "message" => "Something went wrong. Try again");
		     	}
		     }else{
		     	return array("status" => "failed", "message" => "The image dimension must be less than or equal to<span style='color: #FF0000;'>450</span> for both width and height");
		     }

		   }else{
		     return array("status" => "failed", "message" => "The file must be an <span style='color: #FF0000;'>image</span> file");
		   }
		 }else{ 
		   return array("status" => "failed", "message" => $upload_errors[$error]);
		 }
	}

?>