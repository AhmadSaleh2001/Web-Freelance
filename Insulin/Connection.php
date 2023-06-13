<?php
    $Conn = "";
    try
    {
        $Conn = new PDO("mysql:host=localhost;dbname=insulin" , "root" , "");
        
    }
    catch(PDOException $e)
    {
        echo "No";
    }
?>