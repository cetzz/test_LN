<?php

        try {
            $connection = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbname. ';charset=' . $dbcharset, $dbuser, $dbpass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
            ]);
        } catch (PDOException $e) {
            die('Error de Conexión (' . mysqli_connect_errno() . ') '. mysqli_connect_error() . '<br />IP Sevidor: ' . $dbhost . '<br />Usuario: ' . $dbuser);
        }


        ?>