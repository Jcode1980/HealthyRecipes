// ========================================================
// = general.js - Basic general purpose scripting methods =
// ========================================================
// Author: Theo Howell
// Last Modified: 28/09/2012

function el_id(id)
{
	return document.getElementById(id);
}

function supports_input_placeholder() 
{
	var i = document.createElement('input');
	return 'placeholder' in i;
}

function debugln(msg)
{
	if ( ! window.console ) 
		console = { log: function(){} };
	console.log(msg);
}

function implode(delim, array) 
{
    var combined = "";
    var len = array.length;
    for (var i = 0; i < len; i++) {
        combined += array[i];
        if (i < len-1)
            combined += delim;
    }
    return combined;
}

function safeJSON(json) 
{
    try {
        var r = JSON.parse(json);
        return r;
    } catch (e) {
        return null;
    }
}

// ===================
// = Class additions =
// ===================

if (typeof String.prototype.endsWith != 'function') 
{
	String.prototype.endsWith = function(suffix) {
	    return this.indexOf(suffix, this.length - suffix.length) !== -1;
	};
}

if (typeof String.prototype.startsWith != 'function') 
{
	String.prototype.startsWith = function(prefix) { 
		return this.slice(-prefix.length) == prefix;
	};
}

if (typeof String.prototype.trim != 'function')
{
	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g, '');
	};
}
