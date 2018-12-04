<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
function enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

add_action('wp_head', 'loginFromParent');
function loginFromParent() {
    $yang_debug = true;
    global $post;
if ($yang_debug) error_log("debug line 10");
    if(!$post->post_type == "product" || !isset($_GET['userToken'])) {
        return;
    }
if ($yang_debug) error_log("debug line 13");
    $userToken = $_GET['userToken'];
    if (is_user_logged_in()) {
//have to logout and redirect back to clear auth cookie.
        //if ($ssy_debug) error_log("logout");
        wp_logout();
        $url = "/" . $wp->request . "/?userToken=" . $userToken;
        //if ($ssy_debug) error_log($url);
        wp_redirect($url);
        exit();
     }
if ($yang_debug) error_log("debug line 24");
     //Users get from http://roncabeanz.com/Roncabeanz/ReadUsers.php
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://teamalphamarket.com/TeamAlphaMarket/ReadUserInfo.php?userToken=".$userToken);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $contents = curl_exec($ch);
    curl_close($ch);

    $userInfo = json_decode($contents, true);
if ($yang_debug) error_log("debug line 33");
if ($yang_debug) print_r($userInfo);

    $user_id = username_exists( $userInfo['emailAddress'] );
    if ( !$user_id and email_exists($user_email) == false ) {
if ($yang_debug) error_log("debug line 40 try to create an account");
        $user_id = wp_create_user( $userInfo['emailAddress'], $userInfo['password'], $userInfo['emailAddress'] );
    } 
if ($yang_debug) print_r($user_id);

    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
}



?>