<?php
	require_once BLOGIC."/BLogic.php";
	
		
	class AdminSessionController extends PLController
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
			debugln("i'm being handled gay");
            $page = parent::handleRequest();
            if (! $page)
            {
    			$lastRequest = safeValue($_SESSION, "LAST_REQUEST");
    			if ($lastRequest == "")
    				$lastRequest = 0;
    			if (! DEPLOYED)
    				$timedOut = false; // never time out while under development.
    			else
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
    				$page = $this->logout();
    				$page->errorMessage = $error;
    				return $page;
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
		
		public function logout()
		{
            if (session_id() != "") 
            {
    			session_unset();
    			session_destroy();
            }
			return $this->pageWithName("FrontPage");
		}
	}
?>