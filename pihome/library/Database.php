<?php
/*
 * PiHome v2.0
 * http://pihome.harkemedia.de/
 *
 * PiHome Copyright (c) 2015, Sebastian Harke
 * Lizenz Informationen.
 *
 * This work is licensed under the Creative Commons Namensnennung - Nicht-kommerziell -
 * Weitergabe unter gleichen Bedingungen 3.0 Unported License. To view a copy of this license,
 * visit: http://creativecommons.org/licenses/by-nc-sa/3.0/.
 *
*/

class Database {

    private $_con;

    public function __construct() {
    	return $this->con();
  	}

    function con()
	{

		try
		{
		    /*** connect to SQLite database ***/
		    $this->_con = new PDO("sqlite:" . DB_FILE);

		}
		catch(PDOException $e)
		{
		    echo $e->getMessage();
		    echo "<br><br>Database -- NOT -- loaded successfully .. ";
		    die( "<br><br>Query Closed !!! $error");
		}
	    return $this->_con;
	}

}


