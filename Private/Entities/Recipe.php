<?php 
	//
	// Recipe.php
	// 
	// Created on 2016-06-06 @ 10:11 pm.
	 
	require_once BLOGIC."/BLogic.php"; 
	 
	class Recipe extends BLGenericRecord 
	{ 
		public function __construct($dataSource = null) 
		{ 
			parent::__construct($dataSource); 
			
			$this->defineRelationship(new BLToManyRelationship("recipeimages", $this, "RecipeImage", "id", "recipeID"));
			$this->defineRelationship(new BLToOneRelationship("mainImage", $this, "RecipeImage", "mainImageID", "fileID"));
			$this->defineRelationship(new BLToManyRelationship("comments", $this, "Comment", "id", "recipeID"));
			
		} 
	 
		public function tableName() 
		{ 
			return "Recipe"; 
		} 

		 
		public function pkNames() 
		{ 
			return "id"; 
		}
		
		/*
			Override this method if you have any database fields which should not
			be modified or saved back to the server. This provides only 'quiet' protection.
			It does not pass any errors or warnings back if field data has changed, it merely
			ommits the fields from the save request.
		*/
		public function readOnlyAttributes()
		{
			return array("id");
		}	
		
		public function sortedIngredients(){
			$found = BLGenericRecord::find("Ingredient", new BLKeyValueQualifier("recipeID", OP_EQUAL, $this->vars["id"]), array("sortID" => ORDER_ASCEND, "id" =>ORDER_ASCEND));
			debugln("found ingredients : " . count($found));
			return $found;
		}
		
		public function sortedInstructions(){
			$found = BLGenericRecord::find("Instruction", new BLKeyValueQualifier("recipeID", OP_EQUAL, $this->vars["id"]), array("sortID" => ORDER_ASCEND, "id" =>ORDER_ASCEND));
			return $found;
		}
		
		public function createInstruction(){
			debugln("creating instruction");
			
			$newInstruction = BLGenericRecord::newRecordOfType("Instruction");
			$newInstruction->vars["recipeID"] = $this->vars["id"];

			$lastInstruction = $this->lastInstruction();
			
			$sortID =  $lastInstruction ? $lastInstruction->vars["sortID"] + 1 : 1; 
			$newInstruction->vars["sortID"] = $sortID;
			
			return $newInstruction;
			
		}
		
		public function lastInstruction(){
			$currentInstructions = $this->sortedInstructions();
			return end($currentInstructions);
		}
		
		public function createIngredient(){
			debugln("creating ingredeitn");
				
			$newIngredient = BLGenericRecord::newRecordOfType("Ingredient");
			$newIngredient->vars["recipeID"] = $this->vars["id"];
		
			$lastIngredient = $this->lastIngredient();
				
			debugln("goat ass: " .  $lastIngredient->vars["sortID"]);
			$sortID =  $lastIngredient ? $lastIngredient->vars["sortID"] + 1 : 1;
			$newIngredient->vars["sortID"] = $sortID;
				
			return $newIngredient;
				
		}
		
		public function lastIngredient(){
			$currentIngredients = $this->sortedIngredients();
			return end($currentIngredients);
		}
		
	
		public function sortedComents(){
			$found = BLGenericRecord::find("Comment", new BLKeyValueQualifier("recipeID", OP_EQUAL, $this->vars["id"]), array("created" => ORDER_ASCEND));
			return $found;
		}
		

		public function addMealType($mealType){
			//check if meal Type already exists in many to many relationship
			
			if(!$this->mealTypeExistsInRecipe($mealType)){
				$newMealTypeRecipe = BLGenericRecord::newRecordOfType("MealTypeRecipe");
				$newMealTypeRecipe->vars["recipeID"] = $this->vars["id"];
				$newMealTypeRecipe->vars["mealTypeID"] = $mealType->vars["id"];
				
				$newMealTypeRecipe->save();
			}
			
		}
		
		public function mealTypeExistsInRecipe($mealType){
			$qual = new BLAndQualifier(array(
					new BLKeyValueQualifier("recipeID", OP_EQUAL, $this->vars["id"]),
					new BLKeyValueQualifier("mealTypeID", OP_EQUAL, $mealType->vars["id"]),
			
			));
			$found = BLGenericRecord::find("MealTypeRecipe", $qual);
			debugln("found mealType in recipe: " + count($found));
			
			return count($found) > 0;
		}
		
		public function mealTypes(){
			$mealTypes = array();
			
			$qual = new BLAndQualifier(array(
					new BLKeyValueQualifier("recipeID", OP_EQUAL, $this->vars["id"]),
			));
			$foundMealTypeRecipes = BLGenericRecord::find("MealTypeRecipe", $qual);

			if(count($foundMealTypeRecipes) > 0){
				$mealTypeRecipeArray = array();
				
				//Is there a shortcut for this??
				foreach ($foundMealTypeRecipes as $mealTypeRecipe){
					
					array_push($mealTypeRecipeArray, $mealTypeRecipe->vars["mealTypeID"]);
				}
				
				$mealTypes = $this->objectMatchingKeyAndValue("MealType", "id", $mealTypeRecipeArray);
				
			}
			debugln("found meal Types: " . count($mealTypes));
			return $mealTypes;
		}

	
		public function createCommentForUser($user){
			debugln("creating comment");
				
			$newComment = BLGenericRecord::newRecordOfType("Comment");
			$newComment->vars["recipeID"] = $this->vars["id"];

			$newComment->vars["userID"] = $user->vars["id"];
			return $newComment;
		}
		
		//public function objectMatchingKeyAndValue("ReportType", "reportTypeID", pk);
		public function objectMatchingKeyAndValue($entity, $key, $arrayValues){
			$qualifiers = array();
			
			foreach ($arrayValues as $value) {
				debugln("pushing mealTypeIDs as qual: " . $value);
				array_push($qualifiers, new BLKeyValueQualifier($key, OP_EQUAL, $value));
			}
			
			return BLGenericRecord::find($entity, new BLOrQualifier($qualifiers)); 
		}
		
	/*	public function createObjectForRecepe($entity){
			debugln("creating object: " . $entity);
			
			$newObject = BLGenericRecord::newRecordOfType($entity);
			$newObject->vars["recipeID"] = $this->currentEntity()->vars["recipeID"];
			
			$lastInstruction = lastInstruction();
			
			$sortID =  $lastInstruction ? $lastInstruction->vars["sortID"] + 1 : 1;
			$newInstruction->vars["sortID"];
			
			return $newInstruction;
		}*/
		
		
		
		
		/* 	Override this method if you have any database fields that deal in
			raw binary data.
			WARNING: attributes returned from here do not get escaped when working with the
			MySQLDataSource so be very very careful on trusting the contents of the data
			you are working with!
		*/
		/* public function binaryAttributes()
		{
			return array();
		}
		*/
		/*
		public function awakeFromFetch()
		{
			
		}	 
		
		public function validateForSave()
		{
			
		}
		*/
		
		/*
		// amount of time any apc auto-caching will store a copy for records of this entity
		public function cacheTTL()
		{
			return 60;
		}
		*/
	} 
?>
