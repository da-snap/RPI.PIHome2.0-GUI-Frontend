<?
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


function dbconnect()
{
    global $config;


        ;
	if (!($link = new PDO("sqlite:" . DB_FILE)))
	{
        print "<h3>could not connect to database</h3>\n";
		exit;
	}
    return $link;
}



function getLights()
{
	$link = dbconnect();
	$sql_getLights       = "SELECT * FROM  ".PREFIX."devices  WHERE aktiv = '1' ORDER BY sort DESC ";
	$query_getLights     = $link->query($sql_getLights);
	$x=0;
	while($light = $query_getLights->fetch(PDO::FETCH_ASSOC)){
		$devices[$x]["id"] = $light['id'];
		$devices[$x]["room_id"] = $light['room_id'];
		$devices[$x]["device"] = $light['device'];
		$devices[$x]["letter"] = $light['letter'];
		$devices[$x]["code"] = $light['code'];
		$devices[$x]["status"] = $light['status'];
		$x=$x+1;
	}
	return $devices;
}


function getRoomById($id)
{
    $link = dbconnect();
    $sql_getroom       = "SELECT * FROM ".PREFIX."rooms  WHERE id = '".$id."' ";
    $query_getroom      = $link->query($sql_getroom);
    while($getroom  = $query_getroom->fetch(PDO::FETCH_ASSOC)){
        return $getroom['room'];
    }
}



function setLightStatus($id,$status)
{
	if($status=="on"){ $s="1"; }elseif($status=="off"){ $s="0"; }
	$link = dbconnect();
        $sql = "UPDATE `".PREFIX."devices` SET `status`='".$s."' WHERE `id`='".$id."'";
	$update = $link->prepare($sql);
	$update->execute();
}



function getCodeById($id)
{
	$link = dbconnect();
	$sql_getcode       = "SELECT * FROM  ".PREFIX."devices  WHERE id = '".$id."' ";
	$query_getcode      = $link->query($sql_getcode);
	while($code  = $query_getcode->fetch(PDO::FETCH_ASSOC)){
		$c["letter"] = $code['letter'];
		$c["code"] = $code['code'];
	}
	return $c;
}



function allOff()
{
	$link = dbconnect();
	$sql_alloff = "SELECT * FROM ".PREFIX."devices WHERE status = 1 ";
	$query_alloff = $link->query($sql_alloff);
	while($getallon = $query_alloff->fetch(PDO::FETCH_ASSOC))
    {
        $stat = "off";
        setLightStatus($getallon["id"], $stat);
        if($getallon['letter']=="A"){
            $letter = "1";
        }elseif($getallon['letter']=="B"){
            $letter = "2";
        }elseif($getallon['letter']=="C"){
            $letter = "3";
        }elseif($getallon['letter']=="D"){
            $letter = "4";
        }elseif($getallon['letter']=="E"){
            $letter = "5";
        }
        $status = "0";
        $co = $getallon['code'];
        shell_exec('sudo ' . SEND_PATH . '/send '.$co.' '.$letter.' '.$status.' ');
	}
}


function checkLightStatus($id)
{
	$link = dbconnect();
	$sql_light       = "SELECT * FROM  ".PREFIX."devices  WHERE id = '".$id."' ";
	$query_light      = $link->query($sql_light);
	while($light  = $query_light->fetch(PDO::FETCH_ASSOC))
    {
	   $ls = $light['status'];
	}
	return $ls;
}


function settings()
{
	$link = dbconnect();
	$sql_settings = "SELECT * FROM ".PREFIX."settings ";
	$query_settings = $link->query($sql_settings);
	while($items = $query_settings->fetch(PDO::FETCH_ASSOC)){
		$settings[$items['name']] = $items['value'];
	}
	return $settings;
}


function getSunSet()
{
	$link = dbconnect();
	$sql_ss = "SELECT * FROM ".PREFIX."devices WHERE status = '0' AND sunset = '1' ";
	$query_ss = $link->query($sql_ss);
	while($items = $query_ss->fetch(PDO::FETCH_ASSOC)){
		$sunset[] = $items['id'];
	}
	return $sunset;
}



?>
