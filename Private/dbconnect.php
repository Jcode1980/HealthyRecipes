<?php
	require_once BLOGIC."/BL/BLMySQLDataSource.php";
	
	if (DEPLOYED)
	{
		// production database
		BLDataSource::setDefaultDataSource(new BLMySQLDataSource("localhost", "healthykitch", "Lanas111", "healthykitch"));
	}
	else if (TESTING)
	{
		// testing database
		BLDataSource::setDefaultDataSource(new BLMySQLDataSource("host", "username", "password", "DatabaseName"));
	}
	else{
		BLDataSource::setDefaultDataSource(new BLMySQLDataSource("localhost", "root", "", "HealthyRecipes"));
		//BLDataSource::setDefaultDataSource(new BLMySQLDataSource("htpns1.sqonk.com", "healthykitch", "Lanas111", "healthykitch"));
	}
?>