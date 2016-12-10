<?php
    ob_start();
    
    require "settings.php";
    
    session_start();
    debugln("getImage");
    
    //$user = BLGenericRecord::restoreFromDictionary(sessionValueForKey("user"));
    $lastRequest = safeValue($_SESSION, "LAST_REQUEST");
    
    debugln($_SESSION);
    if (!$lastRequest)
        $lastRequest = 0;
    $now = time();
    $timedOut = ($now-$lastRequest) > MAX_IDLE_TIME; 
    debugln("now: $now, last: $lastRequest, timedOut = $timedOut");
    //debugln("get: " . $_GET);
    $imageID = safeValue($_GET, "id");
    $key = safeValue($_GET, "key");
    $entity = safeValue($_GET, "entity");

    
    /* FIX ME: check on imageID should check the data type, but presently it's returning incorrect results. */
    debugln("bla blahsss");
    debugln("goat here session: " . safeValue($_SESSION, "SERVER_GENERATED_SID"));
    debugln("Image ID: " . $imageID);
    debugln("key: " . $key);
    debugln("entity: " . $entity);
    debugln("timeout: " . ($timedOut ? 'true' : 'false'));
    if (safeValue($_SESSION, "SERVER_GENERATED_SID") && $imageID && $key && ! $timedOut)
    //if (safeValue($_SESSION, "SERVER_GENERATED_SID") && $imageID)
    {
    	
        $image = BLGenericRecord::recordMatchingKeyAndValue($entity, "fileID", $imageID);
        if ($image)
        {
                $path = $image->field($key);
                debugln("this is the path: $path", 1);
                if (file_exists($path))
                {
        			header("Content-Type: ".$image->vars["mimeType"]);
        			header("Content-Length: ".filesize($path));
		
        			$name = $image->field("name");
        			header("Content-Disposition: inline; filename=$name");
		
                    ob_clean();
        			$handle = fopen($path, "rb");
        			fpassthru($handle);
                    ob_end_flush();
        			exit;
                }
    			else {
                    $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
                    header("$protocol 500 Internal Server Error");
                    echo "The image is not available.";
    			}
        }
        else {
            debugln("getProfileImage: image not found in database.");
        }
        exit;
    }
    else{
    	debugln("goat goat");
    }
    
    echo "not allowed.";
    debugln($_GET, 2);
    debugln(sessionData(), 2);
?>