<?php 
	//
	// FileUpload.php
	// 
	// Created on 2015-09-16 @ 10:00 am.
	 
	//Created, deleted, fildID (PK), fileName, hasFile, hashedID, mimType, type
	require_once BLOGIC."/BLogic.php"; 
	require_once ROOT."/Entities/File.php";
 
	class ProfileImage extends File 
	{ 
		public static $TYPE = 2;
		
		public function __construct($dataSource = null) 
		{ 
			parent::__construct($dataSource); 
			
			//$this->vars["type"] = RecipeImage::$TYPE;
		} 
	 
		public function type(){
			return ProfileImage::$TYPE;
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
		
		
		

	
		
		
	}
	
	
	?>