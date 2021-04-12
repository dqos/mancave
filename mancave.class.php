<?php
/*
** Mancave Core Class
** Coded by Tamer
** 06-12-2017
*/

class Mancave {

	public $date;
	public $time;
	public $get;
	public $db;

	// The constructor passed the GET arguments directly into the object. This input must be sanitized.
	public function __construct($get) {

		date_default_timezone_set("Europe/Amsterdam");
		$this->date = date("d-m-Y H:i:s");
		$this->time = time();
		$this->get = $get;

		// This switch executes the correct function based on the type of devices, which is found by the supplied ID.
		// validate_Type() returns the type for validation.
		switch ($this->validate_Type($this->get['id'])) {

			// If it matches the rolling shutter type we execute Rolluik().
			case 'rolluik':
			$this->api_Rolluik();
			break;
			default:
			die('Missing type...');
		}

	}

	// Gets the status of rollingshutter ID in JSON format. This must be returned by the API.
	public function api_Rolluik() {
		$this->validate_Parameters('id');
		$getStatus = $this->database_Select('devices', $this->get['id']);
		$getStatus['unix'] = time();
		return json_encode($getStatus);
	}

	// Validates GET parameters for their type.
	public function validate_Parameters() {
		foreach (func_get_args() as $param) {
			if (!isset($this->get[$param])) {
				die('Missing parameter!');
			}
		}
	}

	// This returns the type based on given ID.
	public function validate_Type($id) {
		$devices = $this->database_Select('devices');

		if (is_array($devices[$id])) {
			return $devices[$id]['type'];
		} else {
			return false;
		}
	}

	// Internal database function to insert data.
	public function database_Insert($dbname, $key, $value) {
		$db = json_decode(file_get_contents('db_'.$dbname.'.json'), true);
		$db[$key] = $value;
		file_put_contents('db_'.$dbname.'.json', json_encode($db));
	}

	// Internal database function to get data.
	public function database_Select($dbname, $key = 0) {

		$db = json_decode(file_get_contents('db_'.$dbname.'.json'), true);
		if ($key != 0) {
			return $db[$key];
		} else {
			return $db;
		} 
	}

	// Internal database to drop data.
	public function database_Drop($dbname, $key) {
		$db = json_decode(file_get_contents('db_'.$dbname.'.json'), true);
		unset($db[$key]);
    	file_put_contents('db_'.$dbname.'.json', json_encode($db));
	}
}

// This class extends the Mancave class and adds controlling capabilities.
class Mancave_Controller extends Mancave
{
    public function __construct()
    {
    	date_default_timezone_set("Europe/Amsterdam");
		$this->date = date("d-m-Y H:i:s");
		$this->time = time();
    }

    // Adds a new device and give it a name. Returns the ID.
    public function devices_Add($type, $name) {
    	$generateID = mt_rand();
    	$this->database_Insert('devices', $generateID, array('type' => $type, 'name' => $name, 'state' => 0, 'pstate' => 0));
    	return $generateID;
    }

    // Delete a device based on ID.
    public function devices_Delete($id) {
    	$this->database_Drop('devices', $id);
    }

    // Find a device and it's information based on type. Returns an array with all devices of that type.
    public function devices_Find($type) {

    	$devices = $this->database_Select('devices');
    	foreach ($devices as $id => $data) {

    		if ($data['type'] == $type) {
    			$results[$id] = $data;
    		}

    	}
    	return $results;
    }

    // Controll a device's state, like a rolling shutter. $state = percentage.
    public function devices_Control($id, $state) {
    	$db = $this->database_Select('devices', $id);

    	if ($state <= 100 && $state >= 0) {
    		$this->database_Insert('devices', $id, array('type' => $db['type'], 'name' => $db['name'], 'state' => $state, 'pstate' => $db['state'], 'date' => $this->date, 'time' => $this->time));
    	}
    	
    }

    // Returns device information based on ID.
    public function devices_Status($id) {
    	return $db = $this->database_Select('devices', $id);
    }

}
