<?php 
	//
	// Like_x.php
	// 
	// Created on 2016-05-21 @ 08:58 pm.
	 
	require_once BLOGIC."/BLogic.php"; 
	 
	class Like_x extends BLGenericRecord 
	{ 
		public function __construct($dataSource = null) 
		{ 
			parent::__construct($dataSource); 
		} 
	 
		public function tableName() 
		{ 
			return "Like_x"; 
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
