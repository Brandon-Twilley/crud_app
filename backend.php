<?php

//Where we store our client posted information.  This persists even after you go to your first page.
$mysqli = new mysqli("localhost", 'root', '', 'biags_store');


if($_SERVER['REQUEST_METHOD']=='GET') {
        //We read through our client_data file and echo out what we have in JSON

    $result = $mysqli->query('SELECT * FROM products;');
    $full_array = array();
    $query_response = array();
    while(true){
        $row = $result->fetch_assoc();
        if($row == null){
            break;
        }
        array_push($query_response, $row);
    }
    echo json_encode($query_response);

} else if($_SERVER['REQUEST_METHOD'] == 'POST')  {

    $body_response = file_get_contents('php://input');
    $response_json = json_decode($body_response);        //get the response body

    $statement= $mysqli->prepare('INSERT INTO products (name, description, price) VALUES (?, ?, ?);');
    $statement->bind_param('sss', $response_json->name,  $response_json->description,  $response_json->price);
    $statement->execute();

    echo json_encode($response_json);

    //array_push($database_array, $client_object);

} else if($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $body_response = file_get_contents('php://input');
    $response_json = json_decode($body_response);


    $statement= $mysqli->prepare('UPDATE products SET name=?, description=?, price=? WHERE id=?;');
    $statement->bind_param('ssss', $response_json->name,  $response_json->description,  $response_json->price, $response_json->id);
    $statement->execute();


    echo $body_response;



} else if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $body_response = file_get_contents('php://input');
    $response_json = json_decode($body_response);        //get the response body

    $statement= $mysqli->prepare('DELETE FROM products WHERE id=?;');
    $statement->bind_param('s', $response_json->id);
    $statement->execute();

    echo $body_response;

} else {
    echo 'ERROR READING REQUEST METHOD';
}

?>