<?php



class Helper {
        static function getFileType(string $fileName) {
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                return $extension;
        }
        
        static function convertTimeToSecond($time) {
                list($hour, $minute, $second) = explode(':', $time);
                if (isset($hour) && isset($minute) && isset($second)) {
                        return $hour*60*60 + $minute*60 + $second;
                }
                return null;
        }
        
        static function convertLanguageFromAlpha3ToAlpha2($langAlpha3) {
                $langAlpha2 = '';
                switch ($langAlpha3) {
                        case 'fin':
                                $langAlpha2 = 'fi';
                                break;
                        case 'swe':
                                $langAlpha2 = 'sv';
                                break;
                        default:
                                break;
                }
                return $langAlpha2;
        }
        
        static function randomInteger($length) {
		$space = range(0,9);
		$string = '';
		$i=0;
		while($i<$length) {
			shuffle($space);
			$string.= $space[1];
			$i++;
		}
		return $string;
	}
        
	static function randomString($length) {
		$space = array_merge(range(0,9),range('a','z'),range('A','Z'));
		$string = '';
		$i=0;
		while($i<$length) {
			shuffle($space);
			$string.= $space[1];
			$i++;
		}
		return $string;
	}


	/**
	 * Add a member id to comma-separated list of members, if not already in the list.
	 *
	 * @param string $list			Comma-separated
	 * @param string $member_id		Member
	 * @return string 	Resulting comma-separated string
	 */
	static function addToList($list, $member_id) {
		if ($list) {
			$current = explode(',',$list);
		} else {
			$current = array();
		}
		if (!in_array($member_id, $current)) {
			$current[] = $member_id;
			sort($current);
		}
		return implode(',',$current);
	}

	/**
	 * Remove a member id to comma-separated list of members, if on the list.
	 *
	 * @param string $list			Comma-separated
	 * @param string $member_id		Member
	 * @return string 	Resulting comma-separated string
	 */
	static function removeFromList($list, $member_id) {
		if ($list) {
			$current = explode(',',$list);
		} else {
			$current = array();
		}
		if (($key = array_search($member_id, $current)) !== false) {
			unset($current[$key]);
		}
		return implode(',',$current);
	}

	static function getRemotePort() {
		return $_SERVER['REMOTE_PORT'] ? $_SERVER['REMOTE_PORT'] : '0';
	}

	static function getRemoteIP() {
		// Get client's IP address
		if (isset($_SERVER['HTTP_CLIENT_IP']) && array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
			$ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$ips = array_map('trim', $ips);
			$ip = $ips[0];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
		}
		$ip = filter_var($ip, FILTER_VALIDATE_IP);
		return ($ip === false) ? '0.0.0.0' : $ip;
	}
	
	static function getLocalTime($utc_datetime, $to_timezone, $from_format = "Y-m-d H:i:s", $to_format = "d.m.Y H:i") {
		$t = DateTime::createFromFormat( $from_format, $utc_datetime, new DateTimeZone( 'UTC' ) );
		$t->setTimeZone(new DateTimeZone($to_timezone));
		return $t->format($to_format);
	}
	
	static function getUtcTime($local_datetime, $from_timezone, $from_format = "Y-m-d H:i", $to_format = "Y-m-d H:i:s") {
		$t = DateTime::createFromFormat( $from_format, $local_datetime, new DateTimeZone( $from_timezone ) );
		$t->setTimeZone(new DateTimeZone('UTC'));
		return $t->format($to_format);
	}
	
	static function getDuration(DateTime $start, DateTime $end) {
		$duration = '';
		$diff= $end->diff($start);
		$days = $diff->format('%d');
		
		$years = $diff->format('%y');
		if ($years != 0) {
			$duration = $duration.$years.' '.Tr::t('y');
		}

		$months = $diff->format('%m');
		if ($months != 0) {
			$duration = $duration.' '.$months.' '.Tr::t('m');
		}
		
		if ($days != 0) {
			$duration = $duration.' '.$days.' '.Tr::t('d');
		}

		return trim($duration);
	}	
	
	static function convertCmToMm($value) {
		return $value*10;
	}
	
	static function convertDecToHex($dec) {
		$originalHex = strtoupper(dechex($dec));
		$prefix = "";
		if (strlen($originalHex) < 24) {
			for($i = 0; $i < (24 - strlen($originalHex)); $i++) {
				$prefix .= "0";
			}
		}

		return $prefix.$originalHex;
	}
	
	static function convertHexToDec($hex) {
		return hexdec($hex);
	}
        
	static function hash($seed) {
		return hash('SHA256', $seed);
	}
        
        /*
         * GET POST PUT DELETE
         */
        static function callAPI($method, $url, $username, $password, $data, $sessionToken = null, $sessionId = null){
                $curl = curl_init();
                switch ($method) {
                        case "POST":
                                curl_setopt($curl, CURLOPT_POST, 1);
                                if ($data)
                                        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                                        break;
                        case "PUT":
                                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                                if ($data)
                                        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));			 					
                                        break;
                        default:
                                if ($data)
                                        $url = sprintf("%s?%s", $url, http_build_query(json_encode($data)));
                }

                // OPTIONS:
                curl_setopt($curl, CURLOPT_URL, $url);
                $headers = array(
                        'Content-Type: application/json; charset=utf8'
                );

                if (isset($sessionId)) {
                        $headers[] = 'Cookie: '.$sessionId;
                }

                if (isset($sessionToken)) {
                        $headers[] = 'X-sessionToken: '.$sessionToken;
                }

                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_HEADER, 1);
                
                if (isset($username) && isset($password)) {
                     curl_setopt($curl, CURLOPT_USERPWD, $username.':'.$password);
                }
                
                curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

                // EXECUTE:
                $result = curl_exec($curl);
                if (!$result) {
                        die("Connection Failure");
                }
                curl_close($curl);
                list($headerText, $body) = explode("\r\n\r\n", $result, 2);
                $headers = self::getHeadersFromCurlResponse($headerText);
                $response = new stdClass();
                $response->headers = $headers;
                $response->body = json_decode($body);
                return $response;
        }
        
        static function getHeadersFromCurlResponse($headerText) {
            $headers = array();
            foreach (explode("\r\n", $headerText) as $i => $line) {
                    if ($i === 0) {
                            $headers['http_code'] = $line;
                    } else {
                            list ($key, $value) = explode(': ', $line);
                            $headers[$key] = $value;
                    }
            }
            return $headers;
        }
}