<?php

/**
 * General Data Access Object
 */

abstract class DAO {
	static $table = '';

	public $id;
	public $created_at;
	public $updated_at;
        public $deleted_at;


	public function __construct($id=null) {
		if ($id) {
			$this->getById($id);
		}
	}

	protected function getById($id) {
		if (!static::$table) {
			throw new Exception('DAO class '.get_class($this).' has no DB table defined');
		}
		DB::prepare("select * from ".static::$table." where id=:id");
		DB::exec(array(':id'=>$id));
		if ($obj = DB::fetch()) {
			$this->build($obj);
		}
	}

	public function build($result_object) {
		foreach($result_object as $property=>$value) {
			if (property_exists($this, $property)) {
				$this->$property = $value;
			}
		}
	}

	public function reload() {
		$this->getById($this->id);
		return $this;
	}

	public function __toString() {
		return print_r($this,true);
	}
}
