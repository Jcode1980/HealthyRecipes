<?php

    require_once BLOGIC."/BLogic.php";
    require_once ROOT."/Components/Controllers/EditViewComponent.php";
    require_once ROOT."/Entities/MealType.php";
    require_once BLOGIC."/BLogic.php";
    //require_once ROOT."/Utils/imagick.php";

    class RecipeEdit extends EditViewComponent   
    {
        protected $prevPage = 'RecipeList';
        protected $currentRecipe;
        protected $selectedMealType;
		protected $selectedIngredient;
		protected $selectedInstruction;
		protected $selectedComment;
        
        public function __construct($formData) 
        { 
            parent::__construct($formData, "RecipeEdit");
            
            global $bl_url_args;
            $this->id = safeValue($bl_url_args, 0);
            
            addCSS('css/editform.css');
        } 
    
        public function currentRecipe() 
        {
			debugln("aaaaa");
			debugln("my id is111: " . $this->id);
            if (!$this->currentRecipe) 
            {
				debugln("my id is: " . $this->id);
				//debugln(print_r(debug_backtrace(), TRUE));
                if (isset($this->id) && is_numeric($this->id)) 
                {
                    $this->currentRecipe = BLGenericRecord::recordMatchingKeyAndValue("Recipe", "id", $this->id);
                } 
                else {
                    $this->currentRecipe = BLGenericRecord::newRecordOfType("Recipe");
                }
            } 
			else{
				debugln("class name: " . get_class($this->currentRecipe));
				debugln("currentRecipe is set?? " . $this->currentRecipe->vars["id"]);
			}
            return $this->currentRecipe;
        }
        
        

        /**
         * Wrapper for current entity, for ease of use with wrapper and templates
         */
        public function currentEntity() {
            return $this->currentRecipe();
        }
    
        public function selectedMealType(){
        	return selectedMealType;
        }
        
        public function setSelectedMealType($theMealType){
        	$this->selectedMealType = $theMealType;
        }
        
        
        /**
         * Save Entity
         */
        public function save() 
        {
            $this->processFormValueKeyPathsForSave();
            try {
            	$this->currentEntity()->save();	

   				if($this->selectedComment()){
                	$this->selectedComment()->save();
                }
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
        
        public function createInstruction()
        {
        	if ($this->currentEntity()){
        		
        		$newInstruction = $this->currentEntity()->createInstruction();
        		
        		$this->processFormValueKeyPathsForSave();
        
        		try {
        			$newInstruction->save();
        
        			//$savedID = doEncrypt($newInstruction->vars["incomeSourceID"]);
        			//$this->setFormValueForKey($savedID, "selectedIncomeSourceID");
        		}
        		catch (Exception $error) {
        			$this->errorMessage = "There was an error creating the Income Source. Please try again.";
        			debugln($error->getMessage());
        		}
        	}
        	debugln("done creating instruction", 2);
        }
        
        public function createIngredient()
        {
        	if ($this->currentEntity()){
        
        		$newIngredient = $this->currentEntity()->createIngredient();
        
        		$this->processFormValueKeyPathsForSave();
        
        		try {
        			$newIngredient->save();
        
        			//$savedID = doEncrypt($newInstruction->vars["incomeSourceID"]);
        			//$this->setFormValueForKey($savedID, "selectedIncomeSourceID");
        		}
        		catch (Exception $error) {
        			$this->errorMessage = "There was an error creating the Income Source. Please try again.";
        			debugln($error->getMessage());
        		}
        	}
        	debugln("done creating instruction", 2);
        }
        
       
        public function mealTypesArray(){
        	return MealType::allMealTypes();
        }
        
        public function addMealType(){
        	debugln("form value for key for mealt type is : " .  $this->formValueForKey("mealTypeSelection"));
        	$id =  $this->formValueForKey("mealTypeSelection");
        	
        	$foundMealType = BLGenericRecord::recordMatchingKeyAndValue("MealType", "id", $id);
        	$this->currentRecipe()->addMealType($foundMealType);
        }
        
        public function mealTypesForRecipe(){
        	return $this->currentEntity()->mealTypes();
        }
        
		public function createComment(){
			$comment = $this->currentRecipe()->createCommentForUser($this->user());
			$comment->save();	
		}    

		public function deleteComment() 
        {
    		$this->deleteObject("Comment", "selectedCommentID");        
		}

		public function deleteInstruction() 
        {
    		$this->deleteObject("Instruction", "selectedInstructionID");        
		} 

		public function deleteIngredient() 
        {
    		$this->deleteObject("Ingredient", "selectedIngredientID");        
		} 

		public function deleteObject($entity, $selectedIDString){
			debugln("delete object");
			$objectDeletionID = doDecrypt($this->formValueForKey("objectForDeletionID"));

			$selectedID = doDecrypt($this->formValueForKey("objectForDeletionID"));
			if(isset($selectedID) && $selectedID == $objectDeletionID){
				$this->setFormValueForKey(null, $selectedIDString);
			}

			$objectForDeletion = BLGenericRecord::recordMatchingKeyAndValue($entity, "id", $objectDeletionID);
			$objectForDeletion->delete();		
		}

		public function isCurrentComment($commentID){
			$selectedCommentID =  doDecrypt($this->formValueForKey("selectedCommentID"));
			debugln("this is the selectedCOmment: " . $selectedCommentID);
			
			return (isset($selectedCommentID) && $selectedCommentID == $commentID);
		}

		public function isCurrentInstruction($instructionID){
			$selectedInstructionID =  doDecrypt($this->formValueForKey("selectedInstructionID"));
			//debugln("this is the selec: " . $selectedInstructionID);
			
			return (isset($selectedInstructionID) && $selectedInstructionID == $instructionID);
		}

		public function isCurrentIngredient($ingredientID){
			$selectedIngredientID =  doDecrypt($this->formValueForKey("selectedIngredientID"));
			//debugln("this is the selectedCOmment: " . $selectedIngredientID);
			
			return (isset($selectedIngredientID) && $selectedIngredientID == $ingredientID);
		}

		public function selectedComment(){
			if (!$this->selectedComment) 
            {
				//debugln("my id is: " . $this->id);
				$selectedCommentID = doDecrypt($this->formValueForKey("selectedCommentID"));

                if (isset($selectedCommentID) && is_numeric($selectedCommentID)) 
                {
                    $this->selectedComment = BLGenericRecord::recordMatchingKeyAndValue("Comment", "id", $selectedCommentID);
                } 
            } 
            return $this->selectedComment;

		}

		public function selectedIngredient(){
			if (!$this->selectedIngredient) 
            {
				//debugln("my id is: " . $this->id);
				$selectedIngredientID = doDecrypt($this->formValueForKey("selectedIngredientID"));

                if (isset($selectedIngredientID) && is_numeric($selectedIngredientID)) 
                {
                    $this->selectedIngredient = BLGenericRecord::recordMatchingKeyAndValue("Ingredient", "id", $selectedIngredientID);
                } 
            } 
            return $this->selectedIngredient;
		}

		public function selectedInstruction(){
			if (!$this->selectedInstruction) 
            {
				//debugln("my id is: " . $this->id);
				$selectedIngredientID = doDecrypt($this->formValueForKey("selectedInstructionID"));

                if (isset($selectedInstructionID) && is_numeric($selectedInstructionID)) 
                {
                    $this->selectedInstruction = BLGenericRecord::recordMatchingKeyAndValue("Instruction", "id", $selectedInstructionID);
                } 
            } 
            return $this->selectedInstruction;
		}

        


        /****************************************          
         *          Image Assist functions      *
         *          - Override as necessary     *
         ****************************************/
    
        /**
        * Override to specify the entity for images.    
        */
    	public function processImages()
    	{
            $this->save();
            if (! $this->errorMessage)
    		    parent::uploadImages('image', 'RecipeImage', "recipeID");
            
    	}
    
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
    
        protected function getImagePath($imageDB = null) { 
        	debugln("getImagePath being called");
        	return $imageDB->filePath();
        }
     
    
        /*
         * Called after image copied to disk
         * Process any additional actions - e.g. low res thumbnail copy etc.
         */
        
        protected function imageSaved($image) {
        	//Fix me 
//         	debugln("imageSaved being called");
        	
//     		$lowres = new Imagick($image->imagePath());
//     		if ($image->field("width") > $image->field("height"))
//     			$lowres->thumbnailImage(180, 0);
//     		else
//     			$lowres->thumbnailImage(0, 180);
//     		file_put_contents($image->thumbnailImagePath(), $lowres);
        } 
        
      
        
    
        /*******************************************/
    }

?>