<?php

    require_once BLOGIC."/BLogic.php";
    require_once ROOT."/Components/Controllers/EditViewWrapper.php";

    class MealTypeEdit extends EditViewWrapper   
    {
        protected $prevPage = 'MealTypeList';
        protected $currentMealType;

        public function __construct($formData) 
        { 
            parent::__construct($formData, "MealTypeEdit");
        } 
    
        public function currentMealType() 
        {
            if (!$this->currentMealType) 
            {
                if (isset($this->id) && is_numeric($this->id)) 
                {
                    $this->currentMealType = BLGenericRecord::recordMatchingKeyAndValue("MealType", "id", $this->id);
                } 
                else {
                    $this->currentMealType = BLGenericRecord::newRecordOfType("MealType");
                }
            } 
            return $this->currentMealType;
        }

        /**
         * Wrapper for current entity, for ease of use with wrapper and templates
         */
        public function currentEntity() {
            return $this->currentMealType();
        }
    
        /**
         * Save Entity
         */
        public function save() 
        {
            $this->processFormValueKeyPathsForSave();
            try {
            	$this->currentEntity()->save();	
            }
    		catch (Exception $error) {
    			debugln($error->getMessage());
    			$this->errorMessage = "There was a problem saving changes to this item. Please try again.";
    			return;
    		}
        
    		$this->alertMessage = "Changes have been saved.";
        
            if (! $this->id) {
                $id = $this->currentEntity()->field($this->currentEntity()->pkNames());
                $this->goToLocation(null, array($id));
            }
        }
    
        /****************************************          
         *          Image Assist functions      *
         *          - Override as necessary     *
         ****************************************/
    
        /**
        * Override to specify the entity for images.    
        */
    	/*public function processImages()
    	{
            $this->save();
            if (! $this->errorMessage)
    		    parent::uploadImages('image', 'entity', 'joinKey');
    	}*/
    
        /**
         * Called before saving an image - check size, filetype, etc.
         * if $this->errorMessage is set, image will not be saved
         */
        /*protected function checkImageInfo($imgInfo) {
        } */
     
        /**
         * Called immediately after image is saved in DB
         * Set any additional fields / joins
         * return Path to image on disk
         */
    
        /*protected function getImagePath($imageDB = null) { 
        }*/
     
    
        /*
         * Called after image copied to disk
         * Process any additional actions - e.g. low res thumbnail copy etc.
         */
        /*
        protected function imageSaved($image) {
    		$lowres = new Imagick($image->imagePath());
    		if ($image->field("width") > $image->field("height"))
    			$lowres->thumbnailImage(180, 0);
    		else
    			$lowres->thumbnailImage(0, 180);
    		file_put_contents($image->thumbnailImagePath(), $lowres);
        } */
    
        /*******************************************/
    }

?>