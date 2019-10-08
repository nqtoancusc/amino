<?php

class ServiceLlivetvChannelCollection extends Collection {
	protected $_tables = array('service_livetv_channel');
	protected $_selects = array('service_livetv_channel.*');
	protected $_wheres = array('1>0');

	protected function build($result) {
		$o = new ServiceLlivetvChannel();
		$o->build($result);
		return $o;
	}
}