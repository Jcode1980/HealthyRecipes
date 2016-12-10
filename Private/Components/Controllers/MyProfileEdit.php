<?php

    require_once BLOGIC."/BLogic.php";
    require_once ROOT."/Components/Controllers/EditViewComponent.php";
    require_once ROOT."/Entities/MealType.php";
    require_once BLOGIC."/BLogic.php";
    //require_once ROOT."/Utils/imagick.php";

    class MyProfileEdit extends EditViewComponent   
    {	
    	protected $currentUser;
        public function __construct($formData) 
        { 
            parent::__construct($formData, "MyProfileEdit");
            
            global $bl_url_args;
            //$this->id = safeValue($bl_url_args, 0);
            
            //addCSS('css/editform.css');
        } 

    	public function processImage()
    	{
            $this->save();
            if (! $this->errorMessage)
    		    $this->uploadImages('image', 'ProfileImage', "recipeID");
            
    	}

    	public function currentUser(){
    		if(!$this->currentUser){
    			$this->currentUser =  BLGenericRecord::restoreFromDictionary(sessionValueForKey("user"));
    			
    			debugln("profileImage : " . $this->currentUser->vars["profileImageID"] );
    			debugln($_SESSION);
    		}
    		
    		return $this->currentUser;
    	}
    	
    	public function currentEntity(){
    		return $this->currentUser();
    	}
        
    	protected function imageSaved($image) {
    		
    		parent::imageSaved($image);
    		
    		$this->currentUser()->vars["profileImageID"] = $image->field("fileID");
    		debugln("MyProfileEdit: imageSaved being called: Image fileID: " . strval($image->field("fileID")) . " currentUser: " . $this->currentUser()->vars["id"] . "profileImageID: " . $this->currentUser()->vars["profileImageID"]);
    		$error = $this->currentUser()->save();
    		debugln("was there an error??? " . $error);
    	}
    	
	}
?>