<?php

$mysqli = new mysqli("localhost", 'root', '', 'biags_store');


if($_SERVER['REQUEST_METHOD']=='GET') {
    //We read through our client_data file and echo out what we have in JSON

    $result = $mysqli->query('SELECT * FROM users;');
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


    $statement= $mysqli->prepare('INSERT INTO users (username, password) VALUES (?, ?);');
    $statement->bind_param('ss', $response_json->username,  $response_json->password);
    $statement->execute();

    echo json_encode($response_json);

} else if($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $body_response = file_get_contents('php://input');
    $response_json = json_decode($body_response);


    $statement= $mysqli->prepare('UPDATE users SET username=?, password=? WHERE id=?;');
    $statement->bind_param('sss', $response_json->username,  $response_json->password,  $response_json->id);
    $statement->execute();


    echo $body_response;



} else if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $body_response = file_get_contents('php://input');
    $response_json = json_decode($body_response);        //get the response body

    $statement= $mysqli->prepare('DELETE FROM users WHERE id=?;');
    $statement->bind_param('s', $response_json->id);
    $statement->execute();
    echo $mysqli->error;

    echo $body_response;

} else {
    echo 'ERROR READING REQUEST METHOD';
}

?>