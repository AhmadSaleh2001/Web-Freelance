<?php
session_start();
session_unset();
session_destroy();
if(!isset($_SESSION["username"]))header("Location:index.php");
header("Location:index.php");