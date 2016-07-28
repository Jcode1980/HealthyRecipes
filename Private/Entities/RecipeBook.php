<?php 
	//
	// Recipe.php
	// 
	// Created on 2016-06-06 @ 10:11 pm.
	 
	require_once BLOGIC."/BLogic.php"; 
	 
	class RecipeBook extends BLGenericRecord 
	{ 
		public function __construct($dataSource = null) 
		{ 
			parent::__construct($dataSource); 
			
			$this->defineRelationship(new BLToManyRelationship("recipes", $this, "Recipe", "id", "recipeBookID"));
		} 
	 
		public function tableName() 
		{ 
			return "RecipeBook"; 
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
		
		public function sortedRecipes(){
			$foundRecipeRecipeBook = BLGenericRecord::find("RecipeRecipeBook", new BLKeyValueQualifier("recipeBookID", OP_EQUAL, $this->vars["id"]), array("sortID" => ORDER_ASCEND, "id" =>ORDER_ASCEND));
			
			$returningRecipes = array();
			
			if(sizeof($foundRecipeRecipeBook) > 0){

				for($foundRecipeRecipeBook as $recipeRecipeBook){
					 $foundRecipeArray = BLGenericRecord::find("Recipe", new BLKeyValueQualifier("recipeID", OP_EQUAL, $recipeRecipeBook->vars["recipeID"]), null);
					
					array_push($returningRecipes, $foundRecipeArray[0]);
				}	
			}						

			return $returningRecipes;
		}
		
		public function lastSort(){
		
		}

		public function addToRecipes(){

		}
		
		public function removeFromRecipes(){
	
		}

		
	

		public function objectMatchingKeyAndValue($entity, $key, $arrayValues){
			$qualifiers = array();
			
			foreach ($arrayValues as $value) {
				debugln("pushing mealTypeIDs as qual: " . $value);
				array_push($qualifiers, new BLKeyValueQualifier($key, OP_EQUAL, $value));
			}
			
			return BLGenericRecord::find($entity, new BLOrQualifier($qualifiers)); 
		}
		
	
		
		
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
