<?php
class ConnectDatabases
{
    public function dbBenjamit(bool $attrError = false)  : object|bool
    {
        try
        {
            $con = new PDO("mysql:host=localhost;dbname=Benjamit;","root","");
            if($attrError == true)
            {
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            $con->exec('set names utf8');
            return $con;
        }
        catch(Exception $e)
        {
            echo "Connection failed database Benjamit error message ".$e->getMessage();
            exit();
        }
    }
}
?>