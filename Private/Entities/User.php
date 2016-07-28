<?php 
	//
	// User.php
	// 
	// Created on 2016-05-20 @ 10:42 pm.
	 
	require_once BLOGIC."/BLogic.php";
require_once BLOGIC."/BLogic.php"; 
	 
	class User extends BLGenericRecord 
	{ 
		public function __construct($dataSource = null) 
		{ 
			parent::__construct($dataSource); 
            
            $this->vars["created"] = mysql_date();
		} 
	 
		public function tableName() 
		{ 
			return "User"; 
		} 
		 
		public function likeRecipeToggle($recipeID){
			$recipeLike = $this->likesRecipe($recipeID);

			

			if(!$recipeLike){
				debugln("create like object");
				$theLike = BLGenericRecord::newRecordOfType("Like_x");
				$theLike->vars["userID"] = $this->vars["id"];
				$theLike->vars["recipeID"] = $recipeID;
				$theLike->save();
			}
			else if($recipeLike){
				debugln("delete like object " . get_class($recipeLike));
				$recipeLike->delete();
				
			}

			
		}

		public function likesRecipe($recipeID){
			$qual = new BLAndQualifier(array(
					new BLKeyValueQualifier("recipeID", OP_EQUAL, $recipeID),
					new BLKeyValueQualifier("userID", OP_EQUAL, $this->vars["id"]),
						
			));

			$found = BLGenericRecord::find("Like_x", $qual);
			echo "found non deleted: " + count($found);
			
			if(count($found) > 0){
				return $found[0];
			}
			else{
				return $found;
			}
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
