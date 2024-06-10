<?php
    include 'connection.php';

if($_POST){
   
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $response = [];
    
    $userQuery = $connection->prepare("SELECT * FROM user WHERE username = ? ");
    $userQuery->execute(array($username));

    if($userQuery->rowCount() != 0){
        $response['status'] = false;
        $response['message'] = "Login Failed";
    }else{
        $insert = 'INSERT INTO user (username,password,name) VALUES (:username,:password,:name)';
        $stmt = $connection->prepare($insert);

        try{
            $stmt->execute([
                ':username' => $username,
                ':password' => md5($password),
                ':name' => $name
            ]);

            $response['status'] = true;
            $response['message'] = "Success";
            $response['data'] =[
                'username' => $username,
                'name' => $name
            ];
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    
    $json = json_encode($response, JSON_PRETTY_PRINT);
    echo $json;
}
?>