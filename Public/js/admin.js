/* 
    Document   : admin.js
    Created on : 26/03/2014
    Author     : Oliver Jacobs
    Description: Functions used by admin pages
 */



function confirmDelete(type)
{
    if (! type) {
        type = 'item';
    }
    return confirm('Are you sure you want to delete this '+type+'?');
}
