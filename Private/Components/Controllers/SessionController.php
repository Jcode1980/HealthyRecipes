<?php
	require_once BLOGIC."/BLogic.php";
	
		
	class SessionController extends PLController
	{
		public function __construct($formData, $componentName)
		{
			parent::__construct($formData, $componentName);
		}
		
		/*
			Examine the time elapsed between this request and the previous one. If it's found to be beyond
			a certain time frame then destory the session and log the user out.
		*/
		public function handleRequest()
		{
            $page = parent::handleRequest();
            if (! $page)
            {
    			$lastRequest = safeValue($_SESSION, "LAST_REQUEST");
    			if ($lastRequest == "")
    				$lastRequest = 0;
    			$timedOut = (time()-$lastRequest) > 3600; // 1 hour idle timeout
    			$error = "";
			
    			if ($timedOut)
    			{
    				$error = "For security reasons your session timed out as it was idle for too long. Please log in again.";
    			}
    			else if (empty($_SESSION["userID"]) || ! isset($_SESSION["SERVER_GENERATED_SID"]))
    			{
    				$error = "Your session was deemed to be invalid. Please log in again.";
    			}
			
    			if ($error)
    			{
    				$this->logout($error);
    			}
    			$_SESSION["LAST_REQUEST"] = time();	
            }
            return $page;
		}
		
		protected $user;
		
		public function user()
		{
			if (! $this->user && isset($_SESSION["user"]))
			{
				$this->user = BLGenericRecord::restoreFromDictionary($_SESSION["user"]);
			}
			return $this->user;
		}
		
		public function logout($errorMessage = "")
		{
            if (session_id() != "") 
            {
    			session_unset();
    			session_destroy();
            }
            session_start();
            $page = $this->pageWithName("Login");
            if ($errorMessage) {
                $page->errorMessage = $errorMessage;
            }
            $page->goToLocation();
		}
	}
?>