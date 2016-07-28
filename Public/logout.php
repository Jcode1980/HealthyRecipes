<?php

	require "settings.php";
    session_start();
    
    if (session_id() != "") 
    {
		session_unset();
		session_destroy();
    }
    $_SESSION = array();
    
    $a = "/";
    if (safeValue($_REQUEST, "a") == 1) {
        $a .= "AdminLogin";
    }
            
    header('location: '.domainName().$a);
?>