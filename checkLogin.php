<?php 
session_start();
if(empty($_SESSION))
{
    session_destroy();
    
    header('Location: login.php');
}
?>