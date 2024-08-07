<?php

/**
 * a session is a way to keep track of user data 
 * as they navigate through your website. 
 * Sessions allow you to store user-specific information
 * such as their login credentials, shopping cart items, or preferences,
 * and keep that information available throughout their visit to your site.
 *
 *  A session is initiated when a user visits a webpage on your site
 * that starts a new session. At that point, a unique session ID is created 
 * for that user, which is usually stored in a cookie on their browser. 
 * This session ID is used to link the user's browser to the session data stored on the server.
 * 
 * The session data is stored in a global variable called $_SESSION. 
 * This variable is an associative array that allows you to store and retrieve session data for the current user.
 * 
 * Under the hood, PHP sessions work by storing the session data 
 * on the server in a temporary file or in a database.
 *  When a user accesses a page on your site, PHP checks their browser 
 * for the session ID and retrieves the corresponding session data from the server.
 *  The $_SESSION variable is then populated with the session data, 
 * and any changes made to the $_SESSION variable are saved back to the 
 * server when the session is closed or destroyed.
 */

// creates a session or resumes the current one based on a session
// identifier passed via a GET or POST request
session_start();

?>
