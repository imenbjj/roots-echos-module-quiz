<?php
class config//conn bd
{
    private static $pdo = null;

    
    public static function getConnexion()
    {
        if (!isset(self::$pdo)) {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "roots&echos"; 

            try {
                self::$pdo = new PDO("mysql:host=$servername;dbname=roots&echos", $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
            } catch (Exception $e) {
                die('Error: ' . $e->getMessage()); 
            }
        }
        return self::$pdo; 
    }
}


config::getConnexion();

?>
