<?php
    if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]))
        die("no direct script access allowed!");
        
	/*
		Deployment or testing is set by placing an empty '.production' or '.testing' file in the top of 
		the public web directory respectively.
	*/
	
	$deployed = file_exists(".production") ? 1 : 0;
	define("DEPLOYED", $deployed);	
	
	$testing = file_exists(".testing") ? 1 : 0;
	define("TESTING", $testing);
	
	define("MAX_IDLE_TIME", 1000);
	
	// set the relative paths to the BLogic framework and your app's private folder for each mode.	
	// NOTE: to utilise the BLFileMakerDataSource you must also define the paths to the constant FMPATH.
	if (DEPLOYED)
	{
		define("ROOT", "../Live/Private");
		define("BLOGIC", "../Live/BLogic");
		define("LOGS", "../logs");
		//define("PRODUCTION_FOLDER", "../../../Files/");
		define ("ROOT_PRODUCTION_FOLDER", "../Live/productionFiles/");
		
		define("FILES_PRODUCTION_FOLDER", ROOT_PRODUCTION_FOLDER . "files/");
		define("FILE_PREVIEWS_PRODUCTION_FOLDER", ROOT_PRODUCTION_FOLDER . "file_previews/");
		
	}
	else if (TESTING)
	{
		define("ROOT", "../../Private");
		define("BLOGIC", "../../BLogic");
		define("LOGS", "../../logs");
	}
	else
	{
		define("ROOT", "../Private");
		define("BLOGIC", "../BLogic");
		define("LOGS", "../logs");
		
		define("ROOT_PRODUCTION_FOLDER", "/Users/john/Sites/Upload/HealthyRecipes/");
		define("FILES_PRODUCTION_FOLDER", ROOT_PRODUCTION_FOLDER . "files/");
		define("FILE_PREVIEWS_PRODUCTION_FOLDER", ROOT_PRODUCTION_FOLDER . "file_previews/");
	}
   
	
	require_once ROOT."/settings.php"; // load the rest of the config from the private settings.
?>