<?php 
	require_once BLOGIC."/BLogic.php"; 
	require_once ROOT."/Components/Controllers/SessionController.php";
		
	class PageWrapper extends SessionController 
	{ 
		protected $innerTemplate;
		protected $hasInvalidTransactionID = false;
		
		public $errorMessage;
		public $alertMessage;
		
		public function __construct($formData, $innerTemplate) 
		{ 
			parent::__construct($formData, "PageWrapper"); 
			$this->innerTemplate = $this->templateForName($innerTemplate);
			$this->innerTemplate->set("controller", $this);
		} 
		
		// prevent page reloads and back button from repeating previous actions.
		public function handleRequest()
		{
			$page = parent::handleRequest();
			if ($page)
				return $page;
			if (empty($_SESSION["transactionID"]) || $this->formValueForKey("transactionID") != $_SESSION["transactionID"])
			{
				$this->hasInvalidTransactionID = true;
			}
			$_SESSION["transactionID"] = uniqid();
		}
		
		public function renderInnerTemplate()
		{
			echo $this->innerTemplate->fetch();
		}
		
		public function hasInvalidTransactionID()
		{
			return $this->hasInvalidTransactionID;
		}
		
		public function formMethod()
		{
			return "post";
		}
        
        public function metaKeywords()
        {
            return appName();
        }
        
        public function metaDescription()
        {
            return "";
        }
        
        public function pageTitle()
        {
            return appName();
        }
        
        public function conocialURL()
        {
            return domainName()."/".$this->className();
        }
        
        public function uploadImages($inputName = 'image', $imageEntityName = 'Image', $joinKey = null)
        {
        	$entity = $this->currentEntity();
        	debugln("this is the entity: " . get_class($entity));
        	if (! $entity)
        	{
        		$this->errorMessage = "Error Uploading Image. Please ensure you have created the item first by clicking save.";
        		return;
        	}
        	debugln("first goat");
        	
        	//$imageFileType = pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION);
        	// Check if image file is a actual image or fake image
        	
        	//debugln("fileType is: " . $imageFileType);
        	debugln("da files : " . var_dump($_FILES));
        	if (! empty($_FILES))
        	{
        		debugln("goat");
        		$entityID = $entity->field($entity->pkNames());
        		if (! $joinKey) {
        			$joinKey = strtolower($entity->tableName()).strtoupper($entity->pkNames());
        		}
        			
        		foreach ($_FILES as $inputName => $fileInfo)
        		{
        			if (empty($inputName) || empty($fileInfo)) {
        				continue;
        			}
        			$count = sizeof($fileInfo["tmp_name"]);
        			$limit = 50 * 1024 * 1024; // 50MB;
        
        			$fileStatus = $fileInfo["error"];
        			$name = $fileInfo["name"];
        			if (empty($name)) {
        				continue;
        			}
        			if (! checkUpload($this, $inputName, $limit, 0)) {
        				continue;
        			}
        			$src = $fileInfo["tmp_name"];
        
        			debugln("goat fileName: " . $src);
        			
        			$size = getimagesize($src);
        			$mimeType = $newImage->vars["mimeType"] = image_type_to_mime_type($size[2]);
        
        			if (strpos($mimeType, 'image') !== 0) {
        				$this->errorMessage = 'Uploaded file: ' . $name . ' is not an image';
        				continue;
        			}
        
        			$this->checkImageInfo($size);
        			if ($this->errorMessage) {
        				// if image check set an error message, return this to the user and don't upload image
        				return;
        			}
        
        			$destPath = "";
        			if ($imageEntityName)
        			{
        				$newImage = BLGenericRecord::newRecordOfType($imageEntityName);
        				$newImage->vars["fileName"] = $name;
        				$newImage->vars["width"] = $size[0];
        				$newImage->vars["height"] = $size[1];
        				$newImage->vars["mimeType"] = $mimeType;
        				$parts = explode(".", $name);
        				$newImage->vars["fileExtension"] = end($parts);
        				$newImage->vars[$joinKey] = $entityID;
        				try {
        					$newImage->save();
        				}
        				catch (Exception $error) {
        					debugln($error->getMessage());
        					$this->errorMessgae = "There was an error saving your image. Please consult the log file for more information.";
        					return;
        				}
        					
        				//configure any fields and joins, return image path
        				$destPath = $this->getImagePath($newImage);
        				debugln("dest path is: " . $destPath);
        			}
        			if (! $destPath)
        				return;
        
        			/** init directory structure as necessary **/
        			$dir = dirname($destPath);
        			if (! is_dir($dir)) {
        				mkdir($dir, 0777, true);
        			}
        
        			debugln("saving in da upload image $destPath", 1);
        			// Store full resolution image
        			if (! move_uploaded_file($src, $destPath))
        			{
        				$newImage->delete();
        				debugln("failed to move file to $destPath");
        				$this->errorMessage = "Sorry there was a problem uploading the file $name. Please try again.";
        				return;
        			}
        			$this->imageSaved($newImage);
        		}
        	}
        	else{
        		debugln("No files to be uploaded");
        	}
        }
	} 
?>
