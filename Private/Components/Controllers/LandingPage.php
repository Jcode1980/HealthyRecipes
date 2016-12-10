<?php 
	require_once BLOGIC."/BLogic.php"; 
	require_once ROOT.'/Components/Controllers/PageWrapper.php';
	require_once ROOT."/Entities/MealType.php";
	

	class LandingPage extends PageWrapper 
	{ 
		public function __construct($formData) 
		{ 
			parent::__construct($formData, "LandingPage");
		} 

   		public function mainMealTypesArray(){
        	return MealType::mainMealTypes();
        }
	} 
?>
