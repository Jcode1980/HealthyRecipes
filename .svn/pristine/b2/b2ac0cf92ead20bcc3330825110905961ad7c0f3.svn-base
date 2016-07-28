<?php
	require_once BLOGIC."/BL/BLMySQLDataSource.php";
	
	if (DEPLOYED)
	{
		// production database
		BLDataSource::setDefaultDataSource(new BLMySQLDataSource("host", "username", "password", "DatabaseName"));
	}
	else if (TESTING)
	{
		// testing database
		BLDataSource::setDefaultDataSource(new BLMySQLDataSource("host", "username", "password", "DatabaseName"));
	}
?>