<?php

class Clients
{

	public static $datas = array();

	// GET /clients
	// Returns the list of clients in JSON
	public static function doList()
	{
		self::loadData();
		$clients = array();
		foreach (self::$datas as $client) {
			$clients[] = $client;
		}
		self::sendData($clients);
	}

	// GET /clients/{clientId}
	// Returns the client's information in JSON
	public static function doGet($clientId)
	{
		self::loadData();
		$client = null;
		foreach (self::$datas as $id => $clientInDb) {
			if ($clientId == $id)
				$client = $clientInDb;
		}
		if ($client) {
			self::sendData($client);
		}
		else {
			header("HTTP/1.0 404 Not Found");
			header('Content-Type: text/json');
		}
	}

	// PUT /clients/{clientId}
	// Returns the id of the client in JSON
	public static function doSet($clientId)
	{
		self::loadData();
		$found = false;
		$putClient = null;
		/**
		 * @var string the client in JSON
		 * ex : { "id":2, "firstname": "Johnanie", "lastname": "Doelle" }
		 */
		$putValue = file_get_contents("php://input");

		if(!empty($putValue)) {
			$putClient = json_decode($putValue);
		}

		if(is_object($putClient)) {

			if($putClient->id != $clientId) {
				header("HTTP/1.1 400 Bad Request");
				header('Content-Type: text/json');
				return;
			}

			foreach (self::$datas as $id => & $clientInDb) {
				if ($clientId == $id) {
					$found = true;
					$clientInDb->firstname = $putClient->firstname;
					$clientInDb->lastname = $putClient->lastname;
				}
			}
			if ($found) {
				self::saveData();
				self::sendData(intval($clientId));
			}
			else {
				header("HTTP/1.1 404 Not Found");
				header('Content-Type: text/json');
			}
		}
		else {
			header("HTTP/1.1 400 Bad Request");
			header('Content-Type: text/json');
		}
	}

	// POST /clients/
	// Returns the id of the client in JSON
	public static function doCreate()
	{

		self::loadData();
		$found = false;
		$postClient = null;
		/**
		 * @var string the client in JSON
		 *  ex : { "firstname": "Johnanie", "lastname": "Doelle" }
		 */
		$postValue = file_get_contents("php://input");

		if(!empty($postValue)) {
			$postClient = json_decode($postValue);
		}

		if(is_object($postClient)) {
			$newId = max(array_keys(self::$datas)) + 1;

			self::$datas[$newId]['id'] = $newId;
			self::$datas[$newId]['firstname'] = $postClient->firstname;
			self::$datas[$newId]['lastname'] = $postClient->lastname;

			self::saveData();
			self::sendData(intval($newId));
		}
		else {
			header("HTTP/1.1 400 Bad Request");
			header('Content-Type: text/json');
		}
	}

	// DELETE /clients/{clientId}
	// Returns the id of the client in JSON
	public static function doDelete($clientId)
	{
		self::loadData();
		$found = false;
		$newDatas = array();
		foreach (self::$datas as $id => $clientInDb) {
			if ($clientId == $id) {
				$found = true;
			}
			else {
				$newDatas[$id] = clone $clientInDb;
			}
		}
		if ($found) {
			self::$datas = $newDatas;

			self::saveData();
			self::sendData(intval($clientId));
		}
		else {
			header("HTTP/1.0 404 Not Found");
			header('Content-Type: text/json');
		}
	}

	private static function saveData() {
		file_put_contents('datas.txt', json_encode( self::$datas ));
	}

	private static function loadData() {
		if(file_exists('datas.txt')) {
			if (($stringDatas = file_get_contents('datas.txt')) !== false) {
				self::$datas = (array) json_decode($stringDatas);
			}
		}
	}

	private static function sendData($data) {
		header('Content-Type: text/json');
		echo json_encode($data);
	}
}
