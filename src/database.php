<?php

        try {
            $connection = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbname. ';charset=' . $dbcharset, $dbuser, $dbpass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
            ]);
        } catch (PDOException $e) {
            die('Connection error (' . mysqli_connect_errno() . ') '. mysqli_connect_error() . '<br />Server IP: ' . $dbhost . '<br />User: ' . $dbuser);
        }


        ?>