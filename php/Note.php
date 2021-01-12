<?php
    include 'dbconnect.php';

    if($_POST['action']=='add_note'){
        
        try{
            $name = $_POST["data"]["name"];
            $description = $_POST["data"]["description"];
            //pdo
            $pdo->beginTransaction();
            $prepared_statement = $pdo->prepare("INSERT INTO notes(title, description, user_id, status) VALUES (?,?,?,?)");
            $prepared_statement->execute(array($name, $description, 1, 1));
            $pdo->commit();

        }catch(Exception $e){
            $pdo->rollBack();
            throw $e;
        }
    }

?>