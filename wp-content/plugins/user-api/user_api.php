<?php
/**
 * @package Hello_Dolly
 * @version 1.7
 */
/*
Plugin Name: external api
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Matt Mullenweg
Version: 0.1
Author URI: http://ma.tt/
*/

/** Load wpdb */
//require_once( ABSPATH . WPINC . '/wp-db.php' );
///Users/yang/Documents/github/www/html/wordpress/wp-includes/wp-db.php
//require_once(  '../../wp-includes/wp-db.php' );
//require_once('/home6/boostsho/public_html/wp/wp-includes/wp-db.php');
include_once 'database.php';
include_once 'user.php';

//global $wpdb;

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$user = new User($db);
 
// query products
$stmt = $user->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $users_arr=array();
    //$users_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $user_item=array(
            "firstName" => $display_name,
            "emailAddress" => $user_email,
            "lastName" => '',
            "address" => '',
            "apt" => '',
            "city" => '',
            "state" => '',
            "zipcode" => '',
            "phone" => '',
            //"description" => html_entity_decode($description),
            //"price" => $price,
            //"category_id" => $category_id,
            //"category_name" => $category_name
        );
 
        array_push($users_arr, $user_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode($users_arr);
}else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}
 
// no products found will be here





// // read products will be here
// //firstName, lastName, emailAddress, address, city, state, zipcode, phone
// $result = $wpdb->get_col( "SELECT `display_name`,`user_email` FROM $wpdb->users" );
// //print_r($result);
// $userRet = array();
// foreach ($result as $resultData) {
//     // $arr[3] will be updated with each value from $arr...
//     $rowData =array();
//     $rowData["firstName"] = $resultData['display_name'];
//     $rowData["emailAddress"] = $resultData['user_email'];
    
//     array_push($userRet, $rowData);
//     //echo "{$value} <br/>";
// }



// $ret = array();

// foreach($products as $curProd){
//     $curRet = array();
//     $curRet["name"] = $curProd->name;
//     $curRet["price"] = $curProd->price;
//     $curRet["productCode"] = $curProd->productCode;
//     $curRet["averageRating"] = $curProd->avgRating;
//     $curRet["ratingRange"] = '0,5';
//     $curRet["thumbnail"] = $curProd->thumbnail";
//     $curRet["clickTo"] = "http://roncabeanz.com/Roncabeanz/ShowCoffee.php?productCode=$curProd->productCode";
//     $curRet["description"] = $curProd->description;

//     array_push($products, $curRet);
// }



?>