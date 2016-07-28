// ========================================================
// = general.js - AJAX scripting methods =
// ========================================================
// Author: Theo Howell
// Last Modified: 28/09/2012

// =========================================================
// = sendRequest: Send an ajax request with POST form data =
// =========================================================

/*
	Send an AJAX request to the server with optional post data.
	
	The format for both successCallback and errorCallback functions are:
	
	function mySuccessCallback(originalQuery, resultText) {}
	
	function myErrorCallback(originalQuery, resultText, httpStatus) {}
	
	where originalQuery is the POST data provided to sendRequest() and the resultText is the data 
	returned from the web service upon success OR the error string if communicating with the
	web service failed.
*/
function sendRequest(url, postData, successCallback, errorCallback)
{
	var httpRequest;
	
	if ('XMLHttpRequest' in window) // Mozilla, Safari, ...
	{
		httpRequest = new XMLHttpRequest();
        if (httpRequest.overrideMimeType)
        	httpRequest.overrideMimeType("application/x-www-form-urlencoded");
	}
	else // IE
	{
		try {
        	httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } 
        catch (e) {
	        try {
	            httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
	        } 
	        catch (e) {
				errorCallback(postData, "The ActiveXObject for the HTTP request could not be created.");
				return;
			}
       }
	}

	if (! httpRequest)
	{
		if (errorCallback != null)
			errorCallback(postData, "Sorry there was an error communicating with the server.");
		else if (window.console)
			console.log("Sorry there was an error communicating with the server.");
		return false;
	}

    httpRequest.onreadystatechange = function() { 
		if (httpRequest.readyState == 4 && httpRequest.status == 200) {
			successCallback(postData, httpRequest.responseText);
		}
		else if (httpRequest.readyState == 4 && errorCallback != null) {
			errorCallback(postData, httpRequest.responseText, httpRequest.status);
		}
	}; 
	
    var fullURL = url;
    var host = window.location.href;
    var pos = host.indexOf("http", 0);
    if (pos != 0)
    {
    	if (host.match(".php$" || host.match(".html$")))
    	{
    		while (! host.match("/$") && host.length > 1)
    		{
    			host = host.substring(0, host.length-1);
    		}
    	}
    	fullURL = host+url;
    }
	//console.log("sending request for "+fullURL);
    httpRequest.open("POST", fullURL, true);
	httpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	httpRequest.send(postData);
}

/*
 * Simple wrapper for JQuery ajax method
 */
function JsonRequest(url, jData, successCallback, errorCallback) {
    $.ajax({type: 'POST',
       url: url,
       dataType: 'json',
       // contentType: "application/json; charset=utf-8",  
       success: successCallback,
       error: errorCallback,
       data: jData
   });
}

/*
	Cycles through the given form element ID, compiles the input data and submits it to
	the given URL.
*/
function ajaxSubmitForm(url, formID, callback, callbackError)
{
	var vals = new Array();
	var form = document.getElementById(formID);
	var inputs = form.getElementsByTagName("input");
	var i;
	for (i = 0; i < inputs.length; i++)
	{
		if (inputs[i].type == "checkbox" && ! inputs[i].checked)
			continue;
		vals[i] = inputs[i].name+"="+encodeURIComponent(inputs[i].value);
	}
	var selects = form.getElementsByTagName("select");
	for (j = 0, i++; j < selects.length; j++, i++)
	{
		vals[i] = selects[j].name+"="+encodeURIComponent(selects[j].value);
	}
	var textareas = form.getElementsByTagName("textarea");
	for (j = 0, i++; j < textareas.length; j++, i++)
	{
		vals[i] = textareas[j].name+"="+encodeURIComponent(textareas[j].value);
	}
	sendRequest(url, vals.join("&"), callback, callbackError);
}
