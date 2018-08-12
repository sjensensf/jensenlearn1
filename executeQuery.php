<?php

function executeQuery($query=null,$arr=null){

	
		$hostname = "lamphost";
		$username = "lampuser";
		$password = "lamppass";
		$dbName = "lampdb";
		$dbPort="3306";


	if(($query!=null)&&(is_array($arr))&&($arr!=null)){
		$dbconn = new PDO('mysql:host='.$hostname.';port='.$dbPort.';dbname='.$dbName.';charset=utf8', $username, $password,array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
		$dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$result=$dbconn->prepare($query);
		$result->execute($arr);
		if(!$result){
			$retVal[]=false;
			$retVal[]="some sort of error on execute";
			$retVal[]=$dbconn->errorInfo();
			return;
		}
		if(preg_match("/^select/i", $query)){
			$result->setFetchMode(PDO::FETCH_ASSOC);
			while($row=$result->fetch(PDO::FETCH_ASSOC)){
				$retVal[0]=true;
				$retVal[]=$row;
			}
			if(!isset($retVal)){
				$retVal[]=false;
				$retVal[]="no records returned";
			}
		}
		if(preg_match("/^insert/i", $query)){
			$retVal[]=$dbconn->lastInsertId();
		}
		if(preg_match("/^update/i", $query)){
			$retVal[]=$result->rowCount();
		}
		if(preg_match("/^delete/i", $query)){
			$retVal[]=$result->rowCount();
		}
	}

	$dbconn=NULL;
	return $retVal;
}


?>
