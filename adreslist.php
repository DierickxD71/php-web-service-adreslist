<?php

class oneRecord {
	var $id, $firstName, $lastName, $streetName, $houseNumber, $postalCode, $cityName, $phoneNumber;
	function oneRecord($inpid, $inpfirstName, $inplastName, $inpstreetName, $inphouseNumber, $inppostalCode, $inpcityName, $inpphoneNumber)
	{
    	$this->id = $inpid; // int
    	$this->firstName = $inpfirstName; // string
    	$this->lastName = $inplastName; // string
    	$this->streetName = $inpstreetName; // string
    	$this->houseNumber = $inphouseNumber; // int
    	$this->postalCode = $inppostalCode; // int
    	$this->cityName = $inpcityName; // string
    	$this->phoneNumber = $inpphoneNumber; // string
	}
}

$my_DB_obj = null;

function &Connect2DB() {
	global $my_DB_obj;
	$my_DB_obj = new mysqli('localhost', 'adreslist', 'adreslist', 'adreslist');
	if ($my_DB_obj->connect_errno) {
		echo "Sorry, this website is experiencing problems.";
		echo "Error: Failed to make a MySQL connection, here is why: \n";
		echo "Errno: " . $my_DB_obj->connect_errno . "\n";
		exit;
	}
	return $my_DB_obj;
}
	
class adreslist
{

	public function GetCount() {
		$mysqli = &Connect2DB();
		$sql = "SELECT COUNT(*) as total FROM adreslist";
		if (!$result = $mysqli->query($sql)) {
			echo "Sorry, the website is experiencing problems.";
			echo "Error: Our query failed to execute and here is why: \n";
			echo "Errno: " . $mysqli->errno . "\n";
			exit;
		}
		if ($result->num_rows === 0) {
			echo "We could not find a match , sorry about that. Please try again.";
			exit;
		}
		while ($record = $result->fetch_assoc()) {
			$myRecord = new SoapVar($record['total'], XSD_STRING, null, null, 'out');
		}
		$result->free();
		$mysqli->close();
		return $myRecord;
	}

	public function GetAll() {
		$mysqli = &Connect2DB();
		$sql = "SELECT id, firstName, lastName, streetName, houseNumber, postalCode, cityName, phoneNumber FROM adreslist";
		if (!$result = $mysqli->query($sql)) {
			echo "Sorry, the website is experiencing problems.";
			echo "Error: Our query failed to execute and here is why: \n";
			echo "Errno: " . $mysqli->errno . "\n";
			exit;
		}
		if ($result->num_rows === 0) {
			echo "We could not find a match , sorry about that. Please try again.";
			exit;
		}
		$myRecords = new ArrayObject();
		while ($record = $result->fetch_assoc()) {
		    $myRecord  = new oneRecord($record['id'], $record['firstName'], $record['lastName'], $record['streetName'], $record['houseNumber'], $record['postalCode'], $record['cityName'], $record['phoneNumber']);
			$myRecord = new SoapVar($myRecord, SOAP_ENC_OBJECT, null, null, 'record');
			$myRecords->append($myRecord);
		}
		$AllRecords = new SoapVar($myRecords, SOAP_ENC_OBJECT, NULL, NULL, 'out');
		$result->free();
		$mysqli->close();
		return $AllRecords;
	}

	public function GetOne($input) {
    	$requestId = $input->RecordNumber;
		$mysqli = &Connect2DB();
        $sql = $mysqli->prepare("SELECT id, firstName, lastName, streetName, houseNumber, postalCode, cityName, phoneNumber FROM adreslist WHERE id = ?");
        $sql->bind_param('i', $requestId);
        if (!$sql->execute()) {
			echo "Sorry, the website is experiencing problems.";
			echo "Error: Our query failed to execute and here is why: \n";
			echo "Errno: " . $mysqli->errno . "\n";
			exit;
		}
		$result = $sql->get_result();
		if ($result->num_rows === 0) {
			echo "We could not find a match for ID $requestId, sorry about that. Please try again.";
			exit;
		}
		$myRecords = new ArrayObject();
		while ($record = $result->fetch_assoc()) {
		    $myRecord  = new oneRecord($record['id'], $record['firstName'], $record['lastName'], $record['streetName'], $record['houseNumber'], $record['postalCode'], $record['cityName'], $record['phoneNumber']);
			$myRecord = new SoapVar($myRecord, SOAP_ENC_OBJECT, null, null, 'record');
			$myRecords->append($myRecord);
		}
		$AllRecords = new SoapVar($myRecords, SOAP_ENC_OBJECT, NULL, NULL, 'out');
		$result->free();
		$mysqli->close();
		return $AllRecords;
	}
	
	public function AddOne($input) {
    	$inputid = $input->record->id;
    	$inputfirstName = $input->record->firstName;
    	$inputlastName = $input->record->lastName;
    	$inputstreetName = $input->record->streetName;
    	$inputhouseNumber = $input->record->houseNumber;
    	$inputpostalCode = $input->record->postalCode;
    	$inputcityName = $input->record->cityName;
    	$inputphoneNumber = $input->record->phoneNumber;
		$mysqli = &Connect2DB();
        $sql = $mysqli->prepare("INSERT INTO adreslist (id, firstName, lastName, streetName, houseNumber, postalCode, cityName, phoneNumber)"
                                . "VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
                                . "ON DUPLICATE KEY UPDATE"
                                . "  firstName = VALUES(firstName),"
                                . "  lastName = VALUES(lastName),"
                                . "  streetName = VALUES(streetName),"
                                . "  houseNumber = VALUES(houseNumber),"
                                . "  postalCode = VALUES(postalCode),"
                                . "  cityName = VALUES(cityName),"
                                . "  phoneNumber = VALUES(phoneNumber)"
                                );
        $sql->bind_param('isssiiss', $inputid, $inputfirstName, $inputlastName, $inputstreetName, $inputhouseNumber, $inputpostalCode, $inputcityName, $inputphoneNumber);
        if (!$sql->execute()) {
			echo "Sorry, the website is experiencing problems.";
			echo "Error: Our query failed to execute and here is why: \n";
			echo "Errno: " . $mysqli->errno . "\n";
			exit;
		}
		$myRecord = new SoapVar('ok', XSD_STRING, null, null, 'out');
		$mysqli->close();
		return $myRecord;
	}
	
	public function DeleteOne($input) {
    	$requestId = $input->RecordNumber;
		$mysqli = &Connect2DB();
        $sql = $mysqli->prepare("DELETE FROM adreslist WHERE id = ?");
        $sql->bind_param('i', $requestId);
        if (!$sql->execute()) {
			echo "Sorry, the website is experiencing problems.";
			echo "Error: Our query failed to execute and here is why: \n";
			echo "Errno: " . $mysqli->errno . "\n";
			exit;
		}
		$myRecord = new SoapVar('ok', XSD_STRING, null, null, 'out');
		$mysqli->close();
		return $myRecord;
	}
	
}

$server = new SoapServer('adreslist.wsdl',
		array('soap_version' => SOAP_1_2,
				'uri' => 'urn:adreslist',
				'style' => SOAP_DOCUMENT,
				'use' => SOAP_LITERAL,
				'cache_wsdl' => WSDL_CACHE_NONE));
		$server->setClass('adreslist');
		$server->addFunction(SOAP_FUNCTIONS_ALL);
		$server->handle();

    
//}
