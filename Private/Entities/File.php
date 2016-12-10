<?php 
	//
	// FileUpload.php
	// 
	// Created on 2015-09-16 @ 10:00 am.
	 
	//Created, deleted, fildID (PK), fileName, hasFile, hashedID, mimType, type
	require_once BLOGIC."/BLogic.php"; 
 
	abstract class File extends BLGenericRecord 
	{ 
		
		public function __construct($dataSource = null)
		{
			parent::__construct($dataSource);
			$this->vars["created"] = date("y/m/d");
			$this->vars["type"] = $this->type();
			
		}

		static public function processTheFile(){
			
		}
		
		public abstract function type();
		
		public function imagePath(){
			return $this->filePath();
		}
		
		
		public function filePath(){
			
			//return "/Users/john/Sites/Upload/" . $this->vars["fileID"];

			$filePath = FILES_PRODUCTION_FOLDER . $this->className() . "/" . $this->vars["fileID"];
			debugln("this is the production folder: " . $filePath);

			return $filePath;
			
		}
		
		public function thumbnailImagePath(){
			$filePath = FILE_PREVIEWS_PRODUCTION_FOLDER . $this->className() . $this->vars["fileID"];
			debugln("this is the preview folder: " . $filePath);
		
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


	}

?>