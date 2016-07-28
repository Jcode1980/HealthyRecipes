<?php
    /*
        Place all app config details that do not need to go into the public settings.php
        into this file.
    */
    
    require_once BLOGIC."/BLogic.php";
	require_once BLOGIC."/PL/PLRequestResponseHandler.php";

	//include any application specific utility methods and globals.
	foreach (glob(ROOT.'/Utils/*.php') as $filename)
	{
		require_once $filename;
	}
	
	date_default_timezone_set("Australia/Melbourne"); // set the default time zone.
	
	PLController::$componentROOT = ROOT."/Components";
	
	/*
		The following two variables contain a black list of registered page 
		names and actions that will be silently ignored. If the website is
		being hit by a spam bot or other hacking attempt you can place the 
		registered data it's trying to submit into these arrays.
	*/
	global $bannedPageNames;
	$bannedPageNames = array();
	
	global $bannedActions;
	$bannedActions = array();
	
	// ** Enable this line to connect to a database.
	require_once ROOT."/dbconnect.php";
	
	/* 
	    Set this to a short string of characters for html obfusication. 
	    NOTE: The encryption key must remain consistant across all page
	    reloads. Don't use a runtime randomiser like uniqid() and don't
	    store differing keys in a user's session. Sessions eventually 
	    can expire and you will end up with situations where the encrypted
	    form data will be unreadable to the request-response handler.
	*/
	define("ENC_KEY", "2kcjlkjasdfalkjow");

    // default page and action IDs for form printing routines.
    define("BL_DEFAULT_PAGE_ID", "page");
    define("BL_DEFAULT_ACTION_ID", "action");
    
	if (DEPLOYED || TESTING)
	{
		$level = TESTING ? 1 : 0;
		setDebugLogging($level);
		
		$domain = "mynewsite.com";
		if (TESTING) {
			$domain .= "/test";
        } 
        
        // === AUTO BLACK LISTING ===
        // If your site starts to report errors of a strange or outright suspicious 
        // nature you can turn on auto page and action name banning. Once a request for an
        // incorrect component or action name passes the set threshold (defaults to 3) then
        // it's placed on the register and all subsequent attempts will result in a delayed
        // HTTP 400 response.
        //setBanningEnabled(true);
        
        // When using auto-ban, adjust this from the default 3 if you need to tighten or loosen the tollerance.
        //setBanningTolerance(3); 
		
		// add your developer email address to this array to receive critical 
		// error reports from the production server.
		installGeneralErrorHandlers(array("john@sqonk.com.au"), "error.html");
	}
	else
	{
		// This will gracefully load custom configs from a file with the developer's machine host name,
		// short circuiting problems caused by certain settings that are different between people.
        
        $hostname = php_uname("n");
		$developer_custom_settings_file = "../dev/_$hostname.settings.php";
		if (file_exists($developer_custom_settings_file))
		    require_once $developer_custom_settings_file;
		
		define("PRODUCTION_FOLDER", "/Users/john/Sites/Upload/");
	}
	
	/* The app name and domain name the site operates under. The domain name
	is used in some situations such as error handling where it will auto-forward
	to an error page at the top level. */
	setAppInfo("Healthy Recipes", $domain); 

    // Triggered when user tries to request a component or action that does not exist.
	setNotFoundPage("not_found.html");
    
	setLogPath(LOGS."/".str_replace(" ", "_", appName()).".log"); // customise log file name.
	
	session_name(str_replace(" ", "_", appName()));
	session_cache_limiter("nocache");
	
	// roll log the file if it exceeds the file limit (5MB is the default limit, which 
	// can be adjusted with the second parameter of setLogPath()).
	if (TESTING || DEPLOYED)
	    rollLogFileIfNeeded();
?>