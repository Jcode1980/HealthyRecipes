<?php	
	require_once(BLOGIC."/BLogic.php");
	require_once ROOT.'/Components/Controllers/PageWrapper.php';
	
	class FrontPage  extends PageWrapper 
	{
		public function __construct($formData)
		{
			parent::__construct($formData, "FrontPage");
		}
	}
?>