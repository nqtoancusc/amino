<?php

class ParserAdapter {
	public static function import($fileType, $dataSource) {
                switch(strtolower($fileType)) {
                        case 'xml':
                                $xml = simplexml_load_file($dataSource);
                                if (!isset($xml)) {
                                        throw new Exception('Not found');
                                }
                                self::parse($xml);
                                break;
                        case 'csv':
                                // TODO
                                break;
                        case 'json':
                                // TODO
                                break;
                        default:
                                break;
                }
	}        
    
        public static function parse(SimpleXMLElement $xmlEpgNode) {
                self::parseEpgNode($xmlEpgNode);
        }
        
	private static function parseEpgNode(SimpleXMLElement $xmlEpgNode) {
                foreach($xmlEpgNode->children() as $xmlNetworkNode) {
                        self::parseNetworkNode($xmlNetworkNode);
                }
	}
        
	private static function parseNetworkNode(SimpleXMLElement $xmlNetworkNode) {
                foreach($xmlNetworkNode->children() as $xmlServiceNode) {
                        $serviceId = $xmlServiceNode->attributes()->id; // Channel Id
                        // Ignore unexisting channel
                        if (ServiceLivetvChannel::countSourceId($serviceId) < 1) {
                                continue;
                        }
                        if (!isset($xmlServiceNode->attributes()->id)) {
                                continue;
                        }
                        self::parseServiceNode($xmlServiceNode);
                }
	}
        
	private static function parseServiceNode(SimpleXMLElement $xmlServiceNode) {
                $sourceId = $xmlServiceNode->attributes()->id;
                $serviceLivetvChannel = ServiceLivetvChannel::getBySourceId($sourceId);
                foreach($xmlServiceNode->children() as $xmlEventNode) {
                        self::parseEventNode($serviceLivetvChannel, $xmlEventNode);
                }
	}
        
	private static function parseEventNode(ServiceLivetvChannel $serviceLivetvChannel, SimpleXMLElement $xmlEventNode) {
                foreach($xmlEventNode->children() as $xmlEventChildNode) {
                        if ($xmlEventChildNode->getName() == 'language') {
                                $xmlLanguageNode = $xmlEventChildNode;
                                if (!isset($xmlLanguageNode->attributes()->code)) {
                                        continue;
                                }
                                if (!isset($serviceLivetvChannel->primary_language)) {
                                        continue;
                                }                      
                                if (Helper::convertLanguageFromAlpha3ToAlpha2($xmlLanguageNode->attributes()->code) != $serviceLivetvChannel->primary_language) {
                                        continue;
                                }
                                self::parseLanguageNode($serviceLivetvChannel, $xmlEventNode, $xmlLanguageNode);
                        }
                }
	}
        
	private static function parseLanguageNode(ServiceLivetvChannel $serviceLivetvChannel, SimpleXMLElement $xmlEventNode, SimpleXMLElement $xmlLanguageNode) {
                foreach($xmlLanguageNode->children() as $xmlLanguageChildNode) {
                        if ($xmlLanguageChildNode->getName() != 'short_event') {
                                continue;
                        }
                        $xmlShortEventNode = $xmlLanguageChildNode;
                        if (!isset($xmlShortEventNode->attributes()->name)) {
                                continue;
                        }
                        self::parseShortEventNode($serviceLivetvChannel, $xmlEventNode, $xmlLanguageNode, $xmlShortEventNode);
                }
	}
        
	private static function parseShortEventNode(ServiceLivetvChannel $serviceLivetvChannel, SimpleXMLElement $xmlEventNode, SimpleXMLElement $xmlLanguageNode, SimpleXMLElement $xmlShortEventNode) {
                $longTitle = $xmlShortEventNode->attributes()->name;
                $extProgramId = Helper::randomInteger(9);
                $showType = 'other';
                $serviceLivetvProgram = ServiceLivetvProgram::getByLongTitle($longTitle);
                if (!isset($serviceLivetvProgram->id)) {
                        $serviceLivetvProgram = ServiceLivetvProgram::create($extProgramId, $showType, $longTitle);
                }
                if (isset($serviceLivetvProgram->id)) {
                        if (isset($xmlEventNode->attributes()->duration)) {
                                $durationInSecond = Helper::convertTimeToSecond($xmlEventNode->attributes()->duration);                                
                                $serviceLivetvProgram->setDuration($durationInSecond);
                        }
                        if (isset($xmlLanguageNode->attributes()->code)) {
                                $langAlpha2 = Helper::convertLanguageFromAlpha3ToAlpha2($xmlLanguageNode->attributes()->code);
                                $serviceLivetvProgram->setIso2Lang($langAlpha2);
                        }
                        if (isset($xmlEventNode->attributes()->start_time) && isset($durationInSecond)) {
                                $extScheduleId = Helper::randomInteger(9);
                                $startTime = DateTime::createFromFormat('y/m/d H:i:s', $xmlEventNode->attributes()->start_time)->getTimestamp();
                                $endTime = $startTime + $durationInSecond;
                                $serviceLivetvSchedule = ServiceLivetvSchedule::getByServiceLivetvProgramStarttimeEndtime($serviceLivetvChannel, $startTime, $endTime);
                                if (!isset($serviceLivetvSchedule->id)) {
                                        $serviceLivetvSchedule = ServiceLivetvSchedule::create($extScheduleId, $serviceLivetvChannel->id, $startTime, $endTime, $serviceLivetvProgram);
                                }
                        }
                }
	}
}