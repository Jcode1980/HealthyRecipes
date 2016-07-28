<?php
    // set this to your system username/<name of folder in sites> 
    // (don't forget to symlink the public folder Sites).
    $domain = "http://$hostname/~john/HealthyRecipes"; 
    
    setDebugLogging(1);
    error_reporting(E_ALL);
    
    BLDataSource::setDefaultDataSource(new BLMySQLDataSource("localhost", "root", "", "HealthyRecipes"));
    
?>