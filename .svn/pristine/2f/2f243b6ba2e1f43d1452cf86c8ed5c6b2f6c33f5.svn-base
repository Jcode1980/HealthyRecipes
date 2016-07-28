/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


	
function validateLogin(page, action) {
    var error = el_id("loginError");
    error.style.display = "none";
    el_id("alertMsg").style.display = "none";
    var progress = el_id("progressSC");
    progress.style.display = "inline";

    var login = encodeURIComponent(el_id("login").value);
    var password = encodeURIComponent(el_id("password").value);
    var data = "page="+page+"&action="+action+"&login="+login+"&password="+password;
    sendRequest("index.php", data, function(originalQuery, response) {
        var result = JSON.parse(response);
        if (result["error"] != 0) {
            error.style.display = "block";
            error.innerHTML = result["message"];
            Effect.Shake("loginPanel");
        }
        else {
            window.location = window.location;
        }
        progress.style.display = "none";
    }, 
    function(originalQuery, errorResponse) {
        error.style.display = "block";
        el_id("loginError").value = "There was a problem trying to log you in, please try again later.";
        progress.style.display = "none";
    });
}

function sendReminder(page, login) {
    var error = el_id("reminderError");
    error.style.display = "none";
    el_id("alertMsg").style.display = "none";
    var progress = el_id("progressSCC");
    progress.style.display = "inline";
    var email = el_id("email").value;
    var data = "page="+page+"&action="+action+"&email="+email;
    sendRequest("index.php", data, function(originalQuery, response) {
        var result = JSON.parse(response);
        if (result["error"] != 0) {
            error.style.display = "block";
            error.innerHTML = result["message"];
            Effect.Shake("forgotPasswordTable");
        }
        else {
            var alertMsg = el_id("alertMsg");
            alertMsg.style.display = "block";
            alertMsg.innerHTML = "A reminder has been sent to this address.";
        }
        progress.style.display = "none";
    }, 
    function(originalQuery, errorResponse) {
        error.style.display = "block";
        el_id("loginError").value = "There was a problem trying to log you in, please try again later.";
        progress.style.display = "none";
    });
}

    function showForgotPassword() {
            var error = el_id("reminderError");
            if (error != null)
                    error.style.display = "none";
            el_id("loginPanel").style.display = "none";
            new Effect.Appear("forgotPasswordTable", { duration:0.8 });		
    }

    function showLoginPanel() {
            var error = el_id("loginError");
            if (error != null)
                    error.style.display = "none";
            el_id("forgotPasswordTable").style.display = "none";
            new Effect.Appear("loginPanel", { duration:0.8 });	
    }