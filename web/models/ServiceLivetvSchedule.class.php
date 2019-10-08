<?php

class ServiceLivetvSchedule extends DAO {
	static $table = 'service_livetv_schedule';

	public $ext_schedule_id;
	public $channel_id;
	public $start_time;
	public $end_time;
	public $run_time;
	public $program_id;
	public $is_live;
        
	public static function getByServiceLivetvProgramStarttimeEndtime(ServiceLivetvChannel $serviceLivetvChannel, $startTime, $endTime) {
		DB::prepare("select * from ".static::$table." where channel_id=:channel_id and start_time=:start_time and end_time=:end_time");
		DB::exec(array(
			':channel_id' => $serviceLivetvChannel->id,
			':start_time' => $startTime,
                        ':end_time' => $endTime
		));
		if ($result = DB::fetch()) {
			$o = new ServiceLivetvSchedule();
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
        
	public static function create($extScheduleId, $channelId, $startTime, $endTime, ServiceLivetvProgram $serviceLivetvProgram) {
		DB::prepare("insert into ".static::$table." (created_at,ext_schedule_id,channel_id,start_time,end_time, program_id)
			values (utc_timestamp(),:ext_schedule_id,:channel_id,:start_time,:end_time,:program_id)");
		DB::exec(array(
			':ext_schedule_id' => $extScheduleId,
			':channel_id' => $channelId,
			':start_time' => $startTime,
                        ':end_time' => $endTime,
                        ':program_id' => $serviceLivetvProgram->id
		));
		if ($id = DB::lastInsertId()) {
			return new ServiceLivetvSchedule($id);
		}
	}        
}