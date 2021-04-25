<?php

    try {
        $connection = new PDO('mysql:host=' . $dbhost . ';charset=' . $dbcharset, $dbuser, $dbpass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
        ]);
    } catch (PDOException $e) {
        die('Connection error (' . mysqli_connect_errno() . ') '. mysqli_connect_error() . '<br />Server IP: ' . $dbhost . '<br />User: ' . $dbuser);
    }
    if (executeQuery('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "'.$dbname.'"')->fetch()[0]!=null){
        
        try {
            $connection = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbname. ';charset=' . $dbcharset, $dbuser, $dbpass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
            ]);
        } catch (PDOException $e) {
            die('Connection error (' . mysqli_connect_errno() . ') '. mysqli_connect_error() . '<br />Server IP: ' . $dbhost . '<br />User: ' . $dbuser);
        }
    }
    
    $dbh = null;

        ?>