<!--
Identification : db_connection.php
Author : group 14 (Nam, Yahye, Caleb, Jenny, Paul)
Purpose : Set up db connection.        
-->
<?php
	define("DBHOST", "localhost");
	define("DBDB",   "contacts");
	define("DBUSER", "lamp1user");
	define("DBPW", "!Lamp12!");

	function connectDB(){
		$dsn = "mysql:host=".DBHOST.";dbname=".DBDB.";charset=utf8";
		try{
			$db_conn = new PDO($dsn, DBUSER, DBPW);
            $db_conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $db_conn;
		} catch (PDOException $e){
			echo "<p>Error opening database <br/>\n".$e->getMessage()."</p>\n";
			exit(1);
		}
	}

?>
