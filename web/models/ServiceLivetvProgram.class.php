<?php

class ServiceLivetvProgram extends DAO {
	static $table = 'service_livetv_program';

	public $ext_program_id;
	public $show_type;
	public $long_title;
	public $grid_title;
	public $original_title;
	public $duration;
	public $iso_2_lang;
	public $eidr_id;
        
	public function setDuration($duration) {
		DB::prepare("update ".static::$table." set duration=:duration, updated_at=utc_timestamp() where id=:id");
		DB::exec(array(
			':duration' => $duration,
			':id' => $this->id
		));
		return $this->reload();
	}
        
	public function setIso2Lang($iso2Lang) {
		DB::prepare("update ".static::$table." set iso_2_lang=:iso_2_lang, updated_at=utc_timestamp() where id=:id");
		DB::exec(array(
			':iso_2_lang' => $iso2Lang,
			':id' => $this->id
		));
		return $this->reload();
	}
        
	public static function getByExtProgramId($extProgramId) {
		DB::prepare("select * from ".static::$table." where ext_program_id=:ext_program_id");
		DB::exec(array(':ext_program_id'=>$extProgramId));
		if ($result = DB::fetch()) {
			$o = new ServiceLivetvProgram();
			$o->build($result);
			return $o;
		}
	}        

	public static function getByLongTitle($longTitle) {
		DB::prepare("select * from ".static::$table." where long_title=:long_title");
		DB::exec(array(':long_title'=>$longTitle));
		if ($result = DB::fetch()) {
			$o = new ServiceLivetvProgram();
			$o->build($result);
			return $o;
		}
	} 
        
	public static function getCount() {
		DB::prepare("select count(*) as c from ".static::$table);
		DB::exec(array());
		if ($result = DB::fetch()) {
			return $result->c;
		}
	}        
        
	public static function create($extProgramId, $showType = 'other', $longTitle) {
		DB::prepare("insert into ".static::$table." (created_at,ext_program_id,show_type,long_title)
			values (utc_timestamp(),:ext_program_id,:show_type,:long_title)");
		DB::exec(array(
			':ext_program_id' => $extProgramId,
			':show_type' => $showType,
			':long_title' => $longTitle
		));
		if ($id = DB::lastInsertId()) {
			return new ServiceLivetvProgram($id);
		}
	}        
}