<?php
    // set this to your system username/<name of folder in sites> 
    // (don't forget to symlink the public folder Sites).
    $hostname = php_uname("n");
	$domain = "http://" . $hostname . ":9000";     
    setDebugLogging(3);
    error_reporting(E_ALL);
    
    //BLDataSource::setDefaultDataSource(new BLMySQLDataSource("localhost", "root", "", "HealthyRecipes"));
    BLDataSource::setDefaultDataSource(new BLMySQLDataSource("ns1.sqonk.com", "healthykitch", "Lanas111", "healthykitch"));
    
?>