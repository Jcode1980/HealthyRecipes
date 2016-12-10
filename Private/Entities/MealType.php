<?php 
	//
	// MealType.php
	// 
	// Created on 2016-06-07 @ 08:58 pm.
	 
	require_once BLOGIC."/BLogic.php"; 
	 
	class MealType extends BLGenericRecord 
	{ 
		public static $BREAKFAST = 1;
		public static $LUNCH = 2;
		public static $DINNER = 3;
		public static $DESSERT = 4; 
		
		public static $MAIN_MEAL_QUALS = null;	

		public function __construct($dataSource = null) 
		{ 
			parent::__construct($dataSource); 
		} 
	 
		public function tableName() 
		{ 
			return "MealType"; 
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
		
		
		public static function mainMealTypes(){
			
			$quals = new BLKeyValueQualifier("id", OP_IN, array(MealType::$BREAKFAST, MealType::$LUNCH, MealType::$DINNER));
			return BLGenericRecord::find("MealType", $quals, null);
		}		

		public static function allMealTypes(){
			return BLGenericRecord::find("MealType", null);
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
