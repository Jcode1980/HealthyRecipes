<?php
    require "../Public/settings.php";
    
    if (count($argv) < 2) {
        echo "setadmin <name> <login> <desired password>\n";
        exit;
    }
    
    $name = safeValue($argv, 1);
    $login = safeValue($argv, 2);
    $password = safeValue($argv, 3);
    
    if (! $login || ! $password) {
        echo "Both a login and password are needed.\n";
        exit;
    }
    
    $enc_password = crypt(md5($login), md5($password));    
    
    $user = BLGenericRecord::recordMatchingKeyAndValue("AdminUser", "login", $login);
    if ($user) {
        echo "updating user..\n";
    }
    else {
        echo "creating new user..\n";
        $user = BLGenericRecord::newRecordOfType("AdminUser");
    }
    
    $user->vars["name"] = $name;
    $user->vars["login"] = $login;
    $user->vars["password"] = $enc_password;
    try {
        $user->save();
    }
    catch (Exception $error) {
        print $error->getMessage();
    }
    
    echo "done\n";
?>