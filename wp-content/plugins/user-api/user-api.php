<?php

/** Load wpdb */
require_once( ABSPATH . WPINC . '/wp-db.php' );

global $wpdb;


//firstName, lastName, emailAddress, address, city, state, zipcode, phone
$result = $wpdb->get_col( "SELECT `display_name`,`user_email` FROM $wpdb->users" );
//print_r($result);
$userRet = array();
foreach ($result as $resultData) {
    // $arr[3] will be updated with each value from $arr...
    $rowData =array();
    $rowData["firstName"] = $resultData['display_name'];
    $rowData["emailAddress"] = $resultData['user_email'];
    
    array_push($userRet, $rowData);
    //echo "{$value} <br/>";
}



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

// set response code - 200 OK
http_response_code(200);

// make it json format
echo json_encode($userRet);

?>