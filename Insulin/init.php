<?php

$css = "layout/styles/";
$js = "layout/scripts/";
$images = "images/demp/";
$pages = "pages/";
session_start();

function MyRedirect($Url)
{
    header("location:" . $Url);
}

require_once("Connection.php");
require_once("layout/Header.php");
?>