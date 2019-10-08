<?php

class ServiceLivetvChannel extends DAO {
	static $table = 'service_livetv_channel';

	public $uuid;
	public $source_id;
	public $short_name;
	public $full_name;
	public $time_zone;
	public $primary_language;
        
	public static function countSourceId($sourceId) {
		DB::prepare("select count(*) as c from ".static::$table." where source_id=:source_id");
		DB::exec(array(':source_id'=>$sourceId));
		if ($result = DB::fetch()) {
			return $result->c;
		}
                return 0;
	}
        
	public static function getBySourceId($sourceId) {
		DB::prepare("select * from ".static::$table." where source_id=:source_id");
		DB::exec(array(':source_id'=>$sourceId));
		if ($result = DB::fetch()) {
			$o = new ServiceLivetvChannel();
			$o->build($result);
			return $o;
		}
	}        

	public static function getByUUID($uuid) {
		DB::prepare("select * from ".static::$table." where uuid=:uuid");
		DB::exec(array(':uuid'=>$uuid));
		if ($result = DB::fetch()) {
			$o = new ServiceLivetvChannel();
			$o->build($result);
			return $o;
		}
	}

}