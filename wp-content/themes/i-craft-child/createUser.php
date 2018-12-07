<?php
/*
Template Name: CreateUser
*/

$request_method=$_SERVER["REQUEST_METHOD"];

//if not post return 
if($request_method != 'POST'){
    header("HTTP/1.0 405 Method Not Allowed");
    exit;
}

//header("HTTP/1.0 405 Method Not Allowed");
$product_name=$_POST["product_name"];
$price=$_POST["price"];
$quantity=$_POST["quantity"];
$seller=$_POST["seller"];




$response=array(
                'status' => 1,
                'status_message' =>'Product Added Successfully.'
            );


http_response_code(400);
header('Content-Type: application/json');
echo json_encode($response);



    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://teamalphamarket.com/TeamAlphaMarket/ReadUserInfo.php?userToken=".$userToken);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $contents = curl_exec($ch);
    curl_close($ch);

    $userInfo = json_decode($contents, true);

    $user_id = username_exists( $userInfo[0]['emailAddress'] );
    if ( !$user_id and email_exists($user_email) == false ) {
        $user_id = wp_create_user( $userInfo[0]['emailAddress'], $userInfo[0]['password'], $userInfo[0]['emailAddress'] );
    } 







 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->name) &&
    !empty($data->price) &&
    !empty($data->description) &&
    !empty($data->category_id)
){
 
    // set product property values
    $product->name = $data->name;
    $product->price = $data->price;
    $product->description = $data->description;
    $product->category_id = $data->category_id;
    $product->created = date('Y-m-d H:i:s');
 
    // create the product
    if($product->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "Product was created."));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create product."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
}
?>