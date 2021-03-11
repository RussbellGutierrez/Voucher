<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(21600);
    session_start();
}
// Use this namespace
use Steampixel\Route;

// Include router class
include 'routing/Steampixel/Route.php';

// Define a global basepath
define('BASEPATH','/voucher/');

// If your script lives in a subfolder you can use the following example
// Do not forget to edit the basepath in .htaccess if you are on apache
// define('BASEPATH','/api/v1');

// Add base route (startpage)
Route::add('/', function() {
    include 'app/login.php';
});

Route::add('/lo', function() {
    include 'app/login.php';
});

Route::add('/log/(.*)', function($data) {
    $_SESSION['data'] = $data;
    include 'process/user.php';
});

Route::add('/logout', function() {
    include 'process/logout.php';
});

/*Route::add('/pr', function() {
    include 'app/principal.php';
});*/

/*Route::add('/pr/([0-9]*)', function($slug) {
    include 'app/principal.php';
});*/

Route::add('/vp/([0-9]*)/pr', function($opt) {
    include 'app/principal.php';
});

Route::add('/advertencia', function() {
    include 'error/account.php';
});

Route::add('/db', function() {
    include 'error/database.php';
});

Route::add('/nd', function() {
    include 'error/nodata.php';
});

Route::add('/exp', function() {
    include 'error/expired.php';
});

// Add a 404 not found route
Route::pathNotFound(function($path) {
    // Do not forget to send a status header back to the client
    // The router will not send any headers by default
    // So you will have the full flexibility to handle this case
    header('HTTP/1.0 404 Not Found');
    include 'error/404.php';
});

// Add a 405 method not allowed route
Route::methodNotAllowed(function($path, $method) {
    // Do not forget to send a status header back to the client
    // The router will not send any headers by default
    // So you will have the full flexibility to handle this case
    header('HTTP/1.0 405 Method Not Allowed');
    echo 'Error 405 :-(<br>';
    echo 'The requested path "'.$path.'" exists. But the request method "'.$method.'" is not allowed on this path!';
});

// Run the Router with the given Basepath
Route::run(BASEPATH);
?>