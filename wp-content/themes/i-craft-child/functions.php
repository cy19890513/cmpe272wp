<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
function enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

add_action('wp_head', 'loginFromParent');
function loginFromParent() {
    
    if(!$post->post_type == "product" || !isset($_REQUEST['userToken'])) {
        return;
    }
        
    $userToken = $_REQUEST['userToken'];
    if (is_user_logged_in()) {
//have to logout and redirect back to clear auth cookie.
        //if ($ssy_debug) echo "logout";
        wp_logout();
        $url = "/" . $wp->request . "/?userToken=" . $userToken;
        //if ($ssy_debug) echo $url;
        wp_redirect($url);
        exit();
     }

     //Users get from http://roncabeanz.com/Roncabeanz/ReadUsers.php
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://teamalphamarket.com/TeamAlphaMarket/ReadUserInfo.php?userToken=".$userToken);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $contents = curl_exec($ch);
    curl_close($ch);

    $userInfo = json_decode($contents, true);

    $user_id = username_exists( $userInfo['emailAddress'] );
    if ( !$user_id and email_exists($user_email) == false ) {
        $user_id = wp_create_user( $userInfo['emailAddress'], $userInfo['password'], $userInfo['emailAddress'] );
    } 


    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
}



?>