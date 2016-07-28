<?php 
	//
	// FileUpload.php
	// 
	// Created on 2015-09-16 @ 10:00 am.
	 
	//Created, deleted, fildID (PK), fileName, hasFile, hashedID, mimType, type
	require_once BLOGIC."/BLogic.php"; 
 
	class RecipeImage extends BLGenericRecord 
	{ 
		public static $TYPE = 1;
		
		public function __construct($dataSource = null) 
		{ 
			parent::__construct($dataSource); 
			$this->vars["created"] = date("y/m/d");
			$this->vars["type"] = RecipeImage::$TYPE;
		} 
	 
		public function tableName() 
		{ 
			return "File"; 
		} 
		 
		public function pkNames() 
		{ 
			return "fileID"; 
		}
		
		/*
		 Override this method if you have any database fields which should not
		 be modified or saved back to the server. This provides only 'quiet' protection.
		 It does not pass any errors or warnings back if field data has changed, it merely
		 ommits the fields from the save request.
		 */
		public function readOnlyAttributes()
		{
			return array("fileID");
		}
		
		
		static public function processTheFile(){
			
		}
		
		
		
		public function filePath(){
			
			//return "/Users/john/Sites/Upload/" . $this->vars["fileID"];

			$filePath = PRODUCTION_FOLDER . $this->vars["fileID"];
			debugln("this is the production folder: " . $filePath);

			return $filePath;
			
		}
		
		//could this be added to base object ?
		public static function objectForID($encryptedID){
			$id = doDecrypt($encryptedID);
			debugln("this is the id:" . $id);
			if ($id) {
				return BLGenericRecord::recordMatchingKeyAndValue("File", "fileID", $id);
			}
			else{
				return null;
			}
		}

	public function thumbnailImagePath(){
		//return $this->filePath();
		return  "images/template/page-1_img06.jpg";
	}
		
		
	}
	
	
	?>
