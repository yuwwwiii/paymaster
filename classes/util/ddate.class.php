<?php

/**
 * @package Core
 */

class dDate {
	static protected $time_zone = 'GMT';
	static protected $date_format = APPCONFIG_FORMAT_DATE;
	static protected $time_format = APPCONFIG_FORMAT_TIME;
	static protected $time_unit_format = 12; //Minutes

	static protected $month_arr = array(
										'jan' => 1,
										'january' => 1,
										'feb' => 2,
										'february' => 2,
										'mar' => 3,
										'march' => 3,
										'apr' => 4,
										'april' => 4,
										'may' => 5,
										'jun' => 6,
										'june' => 6,
										'jul' => 7,
										'july' => 7,
										'aug' => 8,
										'august' => 8,
										'sep' => 9,
										'september' => 9,
										'oct' => 10,
										'october' => 10,
										'nov' => 11,
										'november' => 11,
										'dec' => 12,
										'december' => 12
										);

	static $day_of_week_arr = NULL;

	static $month_of_year_arr = NULL;

	private function __construct() {
		this::setTimeZone();
	}

	private function _get_month_short_names() {
		// i18n: This private method is not called anywhere in the class.
		//       It's purpose is simply to ensure that the short (3 letter)
		//       month forms are included in gettext() calls so that they
		//       will be properly extracted for translation.
		return array (
				1 => 'Jan',
				2 => 'Feb',
				3 => 'Mar',
				4 => 'Apr',
				5 => 'May',
				6 => 'Jun',
				7 => 'Jul',
				8 => 'Aug',
				9 => 'Sep',
				10 =>'Oct',
				11 =>'Nov',
				12 =>'Dec',
				);
	}

	public static function getTimeZone() {
		return self::$time_zone;
	}
	public static function setTimeZone($time_zone = NULL) {
		$time_zone = Misc::trimSortPrefix( trim($time_zone) );

		//Default to system local timezone if no timezone is specified.
		if ( $time_zone == '' ) {
			//$time_zone = 'GMT';
			$time_zone = date('e');
		}

		if ( $time_zone == self::$time_zone ) {
//			Debug::text('TimeZone already set to: '. $time_zone, __FILE__, __LINE__, __METHOD__, 10);
			return TRUE;
		}

		if ( $time_zone != '' ) {
//			Debug::text('Setting TimeZone: '. $time_zone, __FILE__, __LINE__, __METHOD__, 10);

			self::$time_zone = $time_zone;

			putenv('TZ='.$time_zone);

			global $db;
			if ( isset($db) AND is_object($db) AND $db->dataProvider == 'mysql' ) {
				if ( @$db->Execute('SET SESSION time_zone=\''. $time_zone .'\'') == FALSE ) {
					return FALSE;
				}
			}

			return TRUE;
		} else {
			//PHP doesn't have a unsetenv(), so this will cause the system to default to UTC.
			//If we don't do this then looping over users and setting timezones, if a user
			//doesn't have a timezone set, it will cause them to use the previous users timezone.
			//This way they at least use UTC and hopefully the issue will stand out more.
			putenv('TZ=');
		}

		return FALSE;
	}

	public static function setDateFormat($date_format) {
		$date_format = trim($date_format);

//		Debug::text('Setting Default ddDate Format: '. $date_format, __FILE__, __LINE__, __METHOD__, 10);

		if ( !empty($date_format) ) {
			self::$date_format = $date_format;

			return TRUE;
		}

		return FALSE;
	}

	public static function setTimeFormat($time_format) {
		$time_format = trim($time_format);

//		Debug::text('Setting Default Time Format: '. $time_format, __FILE__, __LINE__, __METHOD__, 10);

		if ( !empty($time_format) ) {
			self::$time_format = $time_format;

			return TRUE;
		}

		return FALSE;
	}

	public static function setTimeUnitFormat($time_unit_format) {
		$time_unit_format = trim($time_unit_format);

//		Debug::text('Setting Default Time Unit Format: '. $time_unit_format, __FILE__, __LINE__, __METHOD__, 10);

		if ( !empty($time_unit_format) ) {
			self::$time_unit_format = $time_unit_format;

			return TRUE;
		}

		return FALSE;
	}

	public static function getTimeZoneOffset() {
		return date('Z');
	}

	function convertSecondsToHMS( $seconds, $include_seconds = FALSE ) {
		if ( $seconds < 0 ) {
			$negative_number = TRUE;
		}

		$seconds = abs($seconds);

		// there are 3600 seconds in an hour, so if we
		// divide total seconds by 3600 and throw away
		// the remainder, we've got the number of hours
		$hours = intval(intval($seconds) / 3600);

		// add to $hms, with a leading 0 if asked for
		$retval[] = str_pad($hours, 2, "0", STR_PAD_LEFT);

		// dividing the total seconds by 60 will give us
		// the number of minutes, but we're interested in
		// minutes past the hour: to get that, we need to
		// divide by 60 again and keep the remainder
		$minutes = intval(($seconds / 60) % 60);

		// then add to $hms (with a leading 0 if needed)
		$retval[] = str_pad($minutes, 2, "0", STR_PAD_LEFT);

		if ( $include_seconds == TRUE ) {
			// seconds are simple - just divide the total
			// seconds by 60 and keep the remainder
			$secs = intval($seconds % 60);

			// add to $hms, again with a leading 0 if needed
			$retval[] = str_pad($secs, 2, "0", STR_PAD_LEFT);
		}

		if ( isset( $negative_number ) ) {
			$negative = '-';
		} else {
			$negative = '';
		}

		return $negative.implode(':', $retval );
	}

	public static function parseTimeUnit($time_unit) {
		/*
			10 	=> 'hh:mm (2:15)',
			12 	=> 'hh:mm:ss (2:15:59)',
			20 	=> 'Hours (2.25)',
			22 	=> 'Hours (2.241)',
			30 	=> 'Minutes (135)'
		*/
		//Get rid of any spaces or commas.
		//ie: 1,100 :10 should still parse correctly
		$time_unit = str_replace( array(',',' '), '', $time_unit);
//		Debug::text('Time Unit Format: '. self::$time_unit_format, __FILE__, __LINE__, __METHOD__, 10);

		//Convert string to seconds.
		switch (self::$time_unit_format) {
			case 10: //hh:mm
			case 12: //hh:mm:ss
				$time_units = explode(':',$time_unit);

				if (!isset($time_units[0]) ) {
					$time_units[0] = 0;
				}
				if (!isset($time_units[1]) ) {
					$time_units[1] = 0;
				}
				if (!isset($time_units[2]) ) {
					$time_units[2] = 0;
				}

				if ( $time_units[0] < 0 OR $time_units[1] < 0 OR $time_units[2] < 0) {
					$negative_number = TRUE;
				}

				$seconds = ( abs( (int)$time_units[0] ) * 3600) + ( abs( (int)$time_units[1] ) * 60) + abs( (int)$time_units[2] );

				if ( isset($negative_number) ) {
					$seconds = $seconds * -1;
				}

				break;
			case 20: //hours
			case 22: //hours [Precise]
				$seconds = $time_unit * 3600;
				break;
			case 30: //minutes
				$seconds = $time_unit * 60;
				break;
		}

		if ( isset($seconds) ) {
			return $seconds;
		}

		return FALSE;
	}

	public static function getTimeUnit($seconds, $time_unit_format = NULL ) {
		if ( $time_unit_format == '' ) {
			$time_unit_format = self::$time_unit_format;
		}

		//var_dump($seconds);
		if ( empty($seconds) ) {
			//echo "Empty Seconds<br>\n";
			switch ($time_unit_format) {
				case 10: //hh:mm
					$retval = '00:00';
					break;
				case 12: //hh:mm:ss
					$retval = '00:00:00';
					break;
				case 20: //hours
					$retval = '0.00';
					break;
				case 22: //hours
					$retval = '0.000';
					break;
				case 20: //minus
					$retval = 0;
					break;
			}

		} else {

			switch ($time_unit_format) {
				case 10: //hh:mm
					$retval = self::convertSecondsToHMS( $seconds );
					/*
					//Debug::text('Seconds: '. $seconds, __FILE__, __LINE__, __METHOD__, 10);
					if ( $seconds < 0 ) {
						$negative_number = TRUE;
					}
					$seconds = abs($seconds);

					$hours = number_format( floor($seconds / 3600), 0 );
					$minutes = number_format( ($seconds % 3600) / 60, 0);

					if ( $minutes == 60 ) {
						$hours++;
						$minutes = 0;
					}

					if ( isset( $negative_number ) ) {
						$negative = '-';
					} else {
						$negative = '';
					}
					$minutes = abs($minutes);

					//Debug::text('Hours: '. $hours, __FILE__, __LINE__, __METHOD__, 10);
					//Debug::text('Minutes: '. $minutes, __FILE__, __LINE__, __METHOD__, 10);

					$retval = $negative.str_pad($hours,2,0,STR_PAD_LEFT).':'.str_pad($minutes,2,0,STR_PAD_LEFT);
					*/
					break;
				case 12: //hh:mm:ss
					$retval = self::convertSecondsToHMS( $seconds, TRUE );
					break;
				case 20: //hours
					$retval = number_format( $seconds / 3600, 2);
					break;
				case 22: //hours [Precise]
					$retval = number_format( $seconds / 3600, 3);
					break;
				case 30: //minutes
					$retval = number_format( $seconds / 60, 0);
					break;
			}

		}

		if ( isset($retval) ) {
			//var_dump($retval);
			return $retval;
		}

		return FALSE;
	}

	public static function parseDateTime($str) {
		//List of all formats that require custom parsing.
		$custom_parse_formats = array(
									'd/m/Y',
									'd/m/y',
									'd-m-y',
									'd-m-Y',
									'm/d/y',
									'm/d/Y',
									'm-d-y',
									'm-d-Y',
									'Y-m-d',
									'M-d-y',
									'M-d-Y',
									);
		$str = trim($str);
		$orig_str = $str;
//		Debug::text('String: '. $str .' Date Format: '. self::$date_format, __FILE__, __LINE__, __METHOD__, 10);

		if ( !is_numeric($str) AND in_array( self::$date_format, $custom_parse_formats) ) {
//			Debug::text('Custom Parse Format detected!', __FILE__, __LINE__, __METHOD__, 10);
			//Match to: Year, Month, Day
			$textual_month = FALSE;
			switch (self::$date_format) {
				case 'M-d-y':
				case 'M-d-Y':
//					Debug::text('Parsing format: M-d-y', __FILE__, __LINE__, __METHOD__, 10);
					$date_pattern = '/([A-Za-z]{3})\-([0-9]{1,2})\-([0-9]{2,4})/';
					$match_arr = array( 'year' => 3, 'month' => 1, 'day' => 2 );
					$textual_month = TRUE;
					break;
				case 'm-d-y':
				case 'm-d-Y':
//					Debug::text('Parsing format: m-d-y', __FILE__, __LINE__, __METHOD__, 10);
					$date_pattern = '/([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{2,4})/';
					$match_arr = array( 'year' => 3, 'month' => 1, 'day' => 2 );
					break;
				case 'm/d/y':
				case 'm/d/Y':
//					Debug::text('Parsing format: m/d/y', __FILE__, __LINE__, __METHOD__, 10);
					$date_pattern = '/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{2,4})/';
					$match_arr = array( 'year' => 3, 'month' => 1, 'day' => 2 );
					break;
				case 'd/m/y':
				case 'd/m/Y':
//					Debug::text('Parsing format: d/m/y', __FILE__, __LINE__, __METHOD__, 10);
					$date_pattern = '/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{2,4})/';
					$match_arr = array( 'year' => 3, 'month' => 2, 'day' => 1 );

					break;
				case 'd-m-y':
				case 'd-m-Y':
//					Debug::text('Parsing format: d-m-y', __FILE__, __LINE__, __METHOD__, 10);
					$date_pattern = '/([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{2,4})/';
					$match_arr = array( 'year' => 3, 'month' => 2, 'day' => 1 );

					break;
				default:
//					Debug::text('NO pattern match!', __FILE__, __LINE__, __METHOD__, 10);
					break;
			}

			if ( isset($date_pattern) ) {
				//Make regex less strict, and attempt to match time as well.
				$date_result = preg_match( $date_pattern, $str, $date_matches );
				//var_dump($date_matches);

				if ( $date_result != 0 ) {
//					Debug::text('Custom Date Match Success!', __FILE__, __LINE__, __METHOD__, 10);

					$date_arr = array(
										'year' => $date_matches[$match_arr['year']],
										'month' => $date_matches[$match_arr['month']],
										'day' => $date_matches[$match_arr['day']],
									);

					//Handle dates less then 1970
					//If the two digit year is greater then current year plus 10 we assume its
					//a 1990 year.
					//Debug::text('Passed Year: '. $date_arr['year'] ." Current Year threshold: ". (date('y')+10), __FILE__, __LINE__, __METHOD__, 10);
					if ( strlen($date_arr['year']) == 2 AND $date_arr['year'] > (date('y')+10) ) {
						$date_arr['year'] = (int)'19'.$date_arr['year'];
					}

					//Debug::Arr($date_arr, 'Date Match Arr!', __FILE__, __LINE__, __METHOD__, 10);

					//; preg_match('/[a-z]/', $date_arr['month']) != 0
					if ( $textual_month == TRUE) {
						$numeric_month = self::$month_arr[strtolower($date_arr['month'])];
//						Debug::text('Numeric Month: '. $numeric_month, __FILE__, __LINE__, __METHOD__, 10);
						$date_arr['month'] = $numeric_month;
						unset($numeric_month);
					}

					$tmp_date = $date_arr['year'].'-'.$date_arr['month'].'-'.$date_arr['day'];
//					Debug::text('Tmp Date: '. $tmp_date, __FILE__, __LINE__, __METHOD__, 10);

					//Replace the date pattern with NULL leaving only time left to append to the end of the string.
					$time_result = preg_replace( $date_pattern, '', $str );
					$formatted_date = $tmp_date .' '. $time_result;
				} else {
//					Debug::text('Custom Date Match Failed... Falling back to strtotime', __FILE__, __LINE__, __METHOD__, 10);
				}

			}


		}

		if ( !isset($formatted_date) ) {
//			Debug::text('NO Custom Parse Format detected!', __FILE__, __LINE__, __METHOD__, 10);
			$formatted_date = $str;
		}
//		Debug::text('Parsing Date: '. $formatted_date , __FILE__, __LINE__, __METHOD__, 10);

		if ( is_numeric( $formatted_date ) ) {
			$epoch = (int)$formatted_date;
		} else {
			$epoch = self::strtotime( $formatted_date );
			//$epoch = strtotime($formatted_date);

			//Parse failed.
			if ( $epoch == $formatted_date ) {
//				Debug::text('Parsing Date Failed! Returning FALSE! ', __FILE__, __LINE__, __METHOD__, 10);
				$epoch = FALSE;
			}

//			Debug::text('Parsed Date: '. dDate::getDate('DATE+TIME', $epoch) , __FILE__, __LINE__, __METHOD__, 10);
		}

		return $epoch;
	}

	public static function getISODateStamp( $epoch ) {
		$format = 'Ymd';

		return date( $format, $epoch);
	}

    public static function getFormattedDateStamp( $epoch ) {
		$format = 'dmy';

		return date( $format, $epoch);
	}
	
	public static function getFormattedDateStampMDY( $epoch ) {
		$format = 'mdy';

		return date( $format, $epoch);
	}

	public static function getFormattedDateStampMDYLong( $epoch ) {
		$format = 'mdY';

		return date( $format, $epoch);
	}
	
	public static function getDBTimeStamp( $epoch, $include_time_zone = TRUE ) {
		$format = 'Y-m-d H:i:s';
		if ( $include_time_zone == TRUE ) {
			$format .= ' T';
		}

		return date( $format, $epoch);
	}

	public static function getDate($format = NULL, $epoch = NULL, $nodst = FALSE) {
		if ( !is_numeric($epoch) OR $epoch == 0 ) {
//			Debug::text('Epoch is not numeric: '. $epoch, __FILE__, __LINE__, __METHOD__, 10);
			return FALSE;
		}

		if ( empty($format) ) {
//			Debug::text('Format is empty: '. $format, __FILE__, __LINE__, __METHOD__, 10);

			$format = 'DATE';
		}

		//Debug::text('Format: '. $format, __FILE__, __LINE__, __METHOD__, 10);
		//Debug::text('Format: '. $format .' Epoch: '. $epoch, __FILE__, __LINE__, __METHOD__, 10);
		switch ( strtolower($format) ) {
			case 'date':
				$format = self::$date_format;
				break;
			case 'time':
				$format = self::$time_format;
				break;
			case 'date+time':
				$format = self::$date_format.' '.self::$time_format;
				break;
			case 'epoch':
				$format = 'U';
				break;

		}

		if ($epoch == '' OR $epoch == '-1') {
			//$epoch = dDate::getTime();
			//Don't return anything if EPOCH isn't set.
			//return FALSE;
			return NULL;
		}

		//debug_print_backtrace();
		//Debug::text('Epoch: '. $epoch, __FILE__, __LINE__, __METHOD__, 10);
		//This seems to support pre 1790 dates..
		return date($format, $epoch);

		//Support pre 1970 dates?
		//return adodb_date($format, $epoch);
	}

	public static function getDayOfMonthArray() {
		for($i=1; $i <= 31; $i++) {
			$retarr[$i] = $i;
		}

		return $retarr;
	}

	public static function getMonthOfYearArray() {
        if ( is_array(self::$month_of_year_arr) == FALSE ) {
			self::$month_of_year_arr = array(
												1 => ('January'),
												2 => ('February'),
												3 => ('March'),
												4 => ('April'),
												5 => ('May'),
												6 => ('June'),
												7 => ('July'),
												8 => ('August'),
												9 => ('September'),
												10 => ('October'),
												11 => ('November'),
												12 => ('December')
				);
        }

		return self::$month_of_year_arr;
	}

	public static function getDayOfWeekArray() {
		if ( is_array(self::$day_of_week_arr) == FALSE ) {
			self::$day_of_week_arr = array(
											0 => ('Sunday'),
											1 => ('Monday'),
											2 => ('Tuesday'),
											3 => ('Wednesday'),
											4 => ('Thursday'),
											5 => ('Friday'),
											6 => ('Saturday')
				);
	        }
		return self::$day_of_week_arr;
	}

	public static function getDayOfWeek($epoch, $start_week_day = 0) {
		$dow = date('w', (int)$epoch);

		if ( $start_week_day == 0 ) {
			return $dow;
		} else {
			$retval = $dow-$start_week_day;
			if ( $dow < $start_week_day ) {
				$retval = $dow+(7-$start_week_day);
			}
			return $retval;
		}
	}

	public static function getDayOfWeekByInt($int) {
		self::getDayOfWeekArray();

		if ( isset(self::$day_of_week_arr[$int]) ) {
			return self::$day_of_week_arr[$int];
		}

		return FALSE;
	}

	public static function getDayOfWeekArrayByStartWeekDay( $start_week_day = 0 ) {
		$arr = self::getDayOfWeekArray();
		foreach( $arr as $dow => $name ) {
			if ( $dow >= $start_week_day ) {
				$retarr[$dow] = $name;
			}
		}

		if ( $start_week_day > 0 ) {
			foreach( $arr as $dow => $name ) {
				if ( $dow < $start_week_day ) {
					$retarr[$dow] = $name;
				} else {
					break;
				}
			}
		}

		return $retarr;

	}

	public static function doesRangeSpanDST( $start_epoch, $end_epoch ) {
//		Debug::text('Start Epoch: '. dDate::getDate('DATE+TIME', $start_epoch) .'  End Epoch: '. dDate::getDate('DATE+TIME', $end_epoch), __FILE__, __LINE__, __METHOD__, 10);
		$dst_epochs = self::getDSTEpoch($start_epoch);

		if ( $dst_epochs !== FALSE ) {
			if ($start_epoch <= $dst_epochs['start'] AND $end_epoch >= $dst_epochs['start']
					OR $start_epoch <= $dst_epochs['end'] AND $end_epoch >= $dst_epochs['end'] ) {

				return TRUE;
			}
		}

		return FALSE;
	}

	public static function getDSTepoch($epoch = NULL) {
		if ($epoch == NULL OR $epoch == '') {
			$epoch = self::getTime();
		}

		if ( strtolower( self::$time_zone ) == 'gmt' ) {
			return FALSE;
		}

		/*

			Daylight Saving Time begins for most of the United States at 2 a.m. on the first Sunday of April.
            Time reverts to standard time at 2 a.m. on the last Sunday of October. In the U.S.,
            each time zone switches at a different time.

			In the European Union, Summer Time begins and ends at 1 am Universal Time (Greenwich Mean Time).
            It starts the last Sunday in March, and ends the last Sunday in October.
            In the EU, all time zones change at the same moment.

		*/

		$epoch = mktime(0,0,0,4,1,date('Y', $epoch));

        //Lets try to make PHP do as much work as possible. So we know DST always happens between 1am and 2am on specific days.
		$first_sunday_in_april = strtotime('This Sunday', $epoch ) + 3600;
//		Debug::text('Epoch of first Sunday in April: '. $first_sunday_in_april .' - '. dDate::getDate('DATE+TIME', $first_sunday_in_april) , __FILE__, __LINE__, __METHOD__, 10);

		if ( date('I', $first_sunday_in_april) == 1) { //1am
//			Debug::text('Found european DST boundary', __FILE__, __LINE__, __METHOD__, 10);
			$dst_start = $first_sunday_in_april;
		} elseif (date('I', ($first_sunday_in_april + 3600) ) == 1 ) { //2am
//			Debug::text('Found US DST boundary', __FILE__, __LINE__, __METHOD__, 10);
			$dst_start = $first_sunday_in_april + 3599;
		} else {
//			Debug::text('Failed to find DST boundary', __FILE__, __LINE__, __METHOD__, 10);
			$dst_start = FALSE;
		}
//		Debug::text('DST Start '. $dst_start .' - '. dDate::getDate('DATE+TIME', $dst_start) , __FILE__, __LINE__, __METHOD__, 10);
		unset($first_sunday_in_april);

		$epoch = mktime(0,0,0,4,1,date('Y', $epoch));

		//Find DST end
		$epoch = mktime(0,0,0,11,1,date('Y', $epoch));

		$last_sunday_in_oct = strtotime('Last Sunday', $epoch );
//		Debug::text('Epoch of last Sunday in Oct: '. $last_sunday_in_oct .' - '. dDate::getDate('DATE+TIME', $last_sunday_in_oct) , __FILE__, __LINE__, __METHOD__, 10);

		if ( date('I', $last_sunday_in_oct) == 0) { //1am
//			Debug::text('Found european DST boundary', __FILE__, __LINE__, __METHOD__, 10);
			$dst_end = $last_sunday_in_oct;
		} elseif (date('I', ($last_sunday_in_oct + 3600) ) == 0 ) { //2am
//			Debug::text('Found US DST boundary', __FILE__, __LINE__, __METHOD__, 10);
			$dst_end = $last_sunday_in_oct + 3599;
		} else {
//			Debug::text('Failed to find DST boundary', __FILE__, __LINE__, __METHOD__, 10);
		}
//		Debug::text('DST End '. $dst_end .' - '. dDate::getDate('DATE+TIME', $dst_end) , __FILE__, __LINE__, __METHOD__, 10);
		unset($last_sunday_in_oct);

		return array('start' => $dst_start, 'end' => $dst_end);
	}

	public static function getTime() {
		return time();
	}

	public static function getDays($seconds) {
		return bcdiv( $seconds, 86400);
	}

	public static function getHours($seconds) {
		return bcdiv( bcdiv( $seconds, 60), 60);
	}

	public static function getSeconds($hours) {
		return bcmul( $hours, 3600 );
	}

	public static function getDaysInMonth($epoch = NULL ) {
		if ($epoch == NULL) {
			$epoch = dDate::getTime();
		}

		return date('t', $epoch);
	}

	public static function snapTime($epoch, $snap_to_epoch, $snap_type) {
//		Debug::text('Epoch: '. $epoch .' ('.dDate::getDate('DATE+TIME', $epoch).') Snap Epoch: '. $snap_to_epoch .' ('.dDate::getDate('DATE+TIME', $snap_to_epoch).') Snap Type: '. $snap_type, __FILE__, __LINE__, __METHOD__, 10);

		if ( empty($epoch) OR empty($snap_to_epoch) ) {
			return $epoch;
		}

		switch (strtolower($snap_type)) {
			case 'up':
//				Debug::text('Snap UP: ', __FILE__, __LINE__, __METHOD__, 10);
				if ( $epoch <= $snap_to_epoch ) {
					$epoch = $snap_to_epoch;
				}
				break;
			case 'down':
//				Debug::text('Snap Down: ', __FILE__, __LINE__, __METHOD__, 10);
				if ( $epoch >= $snap_to_epoch ) {
					$epoch = $snap_to_epoch;
				}
				break;
		}

//		Debug::text('Snapped Epoch: '. $epoch .' ('.dDate::getDate('DATE+TIME', $epoch).')', __FILE__, __LINE__, __METHOD__, 10);
		return $epoch;
	}

	public static function roundTime($epoch, $round_value, $round_type = 20, $grace_time = 0 ) {

//		Debug::text('In Epoch: '. $epoch .' ('.dDate::getDate('DATE+TIME', $epoch).') Round Value: '. $round_value .' Round Type: '. $round_type, __FILE__, __LINE__, __METHOD__, 10);

		if ( empty($epoch) OR empty($round_value) OR empty($round_type) ) {
			return $epoch;
		}

		switch ($round_type) {
			case 10: //Down
				if ( $grace_time > 0 ) {
					$epoch += $grace_time;
				}
				$epoch = $epoch - ( $epoch % $round_value );
				break;
			case 20: //Average
				$epoch = (int)( ($epoch + ($round_value / 2) ) / $round_value ) * $round_value;
				break;
			case 30: //Up
				if ( $grace_time > 0 ) {
					$epoch -= $grace_time;
				}
				$epoch = (int)( ($epoch + ($round_value - 1) ) / $round_value ) * $round_value;
				break;
		}

		return $epoch;
	}

	public static function graceTime($current_epoch, $grace_time, $schedule_epoch) {
		//Debug::text('Current Epoch: '. $current_epoch .' Grace Time: '. $grace_time .' Schedule Epoch: '. $schedule_epoch, __FILE__, __LINE__, __METHOD__, 10);
		if ( $current_epoch <= ($schedule_epoch + $grace_time)
				AND $current_epoch >= ($schedule_epoch - $grace_time) ) {
            //Within grace period, return scheduled time.
			return $schedule_epoch;
		}

		return $current_epoch;
	}

	function getTimeStampFromSmarty($prefix, $array) {
//		Debug::text('Prefix: '. $prefix, __FILE__, __LINE__, __METHOD__, 10);
		//Debug::Arr($array, 'getTimeStampFromSmarty Array:', __FILE__, __LINE__, __METHOD__, 10);

		if ( isset($array[$prefix.'Year']) ) {
			$year = $array[$prefix.'Year'];
		} else {
			$year = strftime("%Y");
		}
		if ( isset($array[$prefix.'Month']) ) {
			$month = $array[$prefix.'Month'];
		} else {
			//$month = strftime("%m");
			$month = 1;
		}
		if ( isset($array[$prefix.'Day']) ) {
			$day = $array[$prefix.'Day'];
		} else {
			//If day isn't specified it uses the current day, but then if its the 30th, and they
			//select February, it goes to March!
			//$day = strftime("%d");
			$day = 1;
		}
		if ( isset($array[$prefix.'Hour']) ) {
			$hour = $array[$prefix.'Hour'];
		} else {
			$hour = 0;
		}
		if ( isset($array[$prefix.'Minute']) ) {
			$min = $array[$prefix.'Minute'];
		} else {
			$min = 0;
		}
		if ( isset($array[$prefix.'Second']) ) {
			$sec = $array[$prefix.'Second'];
		} else {
			$sec = 0;
		}

//		Debug::text('Year: '. $year .' Month: '. $month .' Day: '. $day .' Hour: '. $hour .' Min: '. $min .' Sec: '. $sec, __FILE__, __LINE__, __METHOD__, 10);

		return self::getTimeStamp($year,$month,$day,$hour,$min,$sec);
	}

	function getTimeStamp($year="",$month="",$day="", $hour=0, $min=0, $sec=0) {
		if ( empty($year) ) {
			$year = strftime("%Y");
		}

		if ( empty($month) ) {
			$month = strftime("%m");
		}

		if ( empty($day) ) {
			$day = strftime("%d");
		}

		if ( empty($hour) ) {
			$hour = 0;
		}

		if ( empty($min) ) {
			$min = 0;
		}

		if ( empty($sec) ) {
			$sec = 0;
		}

		//Use adodb time library to support dates earlier then 1970.
		//require_once( Environment::getBasePath() .'classes/adodb/adodb-time.inc.php');

//		Debug::text('  - Year: '. $year .' Month: '. $month .' Day: '. $day .' Hour: '. $hour .' Min: '. $min .' Sec: '. $sec, __FILE__, __LINE__, __METHOD__, 10);

		$epoch = adodb_mktime($hour,$min,$sec,$month,$day,$year);

//		Debug::text('Epoch: '. $epoch .' Date: '. self::getDate($epoch), __FILE__, __LINE__, __METHOD__, 10);

		return $epoch;
	}

	public static function getDayDifference($start_epoch, $end_epoch) {
		//FIXME: Be more accurate, take leap years in to account etc...
		$days = ($end_epoch - $start_epoch) / 86400;

//		Debug::text('Days Difference: '. $days, __FILE__, __LINE__, __METHOD__, 10);

		return $days;
	}

	public static function getWeekDifference($start_epoch, $end_epoch) {
		//FIXME: Be more accurate, take leap years in to account etc...
		$weeks = ($end_epoch - $start_epoch) / (86400 * 7);

//		Debug::text('Week Difference: '. $weeks, __FILE__, __LINE__, __METHOD__, 10);

		return $weeks;
	}

	public static function getMonthDifference($start_epoch, $end_epoch) {
//		Debug::text('Start Epoch: '. dDate::getDate('DATE+TIME', $start_epoch) .' End Epoch: '. dDate::getDate('DATE+TIME', $end_epoch) , __FILE__, __LINE__, __METHOD__, 10);

		$epoch_diff = $end_epoch - $start_epoch;
//		Debug::text('Diff Epoch: '. $epoch_diff , __FILE__, __LINE__, __METHOD__, 10);
		$x = floor( $epoch_diff / 60 / 60 / 24 / 7 / 4);

		/*
		$x=-1; //Start at -1 because it'll always match the first month?
		for($i = $start_epoch; $i < $end_epoch; $i += ( date('t',$i) * 86400) ) {
			//echo "I: $i ". dDate::getDate('DATE+TIME', $i) ." <br>\n";
			Debug::text('I: '. $i.' '. dDate::getDate('DATE+TIME', $i), __FILE__, __LINE__, __METHOD__, 10);
			$x++;
		}
		*/
//		Debug::text('Month Difference: '. $x, __FILE__, __LINE__, __METHOD__, 10);

		return $x;
	}

	public static function getYearDifference($start_epoch, $end_epoch) {
		//FIXME: Be more accurate, take leap years in to account etc...
		$years = ( ($end_epoch - $start_epoch) / 86400 ) / 365;

//		Debug::text('Years Difference: '. $years, __FILE__, __LINE__, __METHOD__, 10);

		return $years;
	}

	public static function getDateByMonthOffset($epoch, $month_offset) {
		//return mktime(0,0,0,date('n', $epoch) + $month_offset,date('j',$epoch),date('Y',$epoch) );
		return mktime(date('G', $epoch),date('i', $epoch),date('s', $epoch),date('n', $epoch) + $month_offset,date('j',$epoch),date('Y',$epoch) );
	}

	public static function getBeginMinuteEpoch($epoch = NULL) {
		if ($epoch == NULL OR $epoch == '' OR !is_numeric($epoch)) {
			$epoch = self::getTime();
		}

		$retval=mktime(date('G',$epoch),date('i', $epoch),0,date('m',$epoch),date('d',$epoch),date('Y',$epoch));
		//Debug::text('Begin Day Epoch: '. $retval .' - '. dDate::getDate('DATE+TIME', $retval) , __FILE__, __LINE__, __METHOD__, 10);
		return $retval;
	}

	public static function getBeginDayEpoch($epoch = NULL) {
		if ($epoch == NULL OR $epoch == '' OR !is_numeric($epoch)) {
			$epoch = self::getTime();
		}

		$retval=mktime(0,0,0,date('m',$epoch),date('d',$epoch),date('Y',$epoch));
		//Debug::text('Begin Day Epoch: '. $retval .' - '. dDate::getDate('DATE+TIME', $retval) , __FILE__, __LINE__, __METHOD__, 10);
		return $retval;
	}

	public static function getMiddleDayEpoch($epoch = NULL) {
		if ($epoch == NULL OR $epoch == '' OR !is_numeric($epoch) ) {
			$epoch = self::getTime();
		}

		$retval=mktime(12,0,0,date('m',$epoch),date('d',$epoch),date('Y',$epoch));
		//Debug::text('Middle (noon) Day Epoch: '. $retval .' - '. dDate::getDate('DATE+TIME', $retval) , __FILE__, __LINE__, __METHOD__, 10);
		return $retval;
	}

	public static function getEndDayEpoch($epoch = NULL) {
		if ($epoch == NULL OR $epoch == '' OR !is_numeric($epoch)) {
			$epoch = self::getTime();
		}

		$retval=mktime(0,0,0,date('m',$epoch),date('d',$epoch)+1,date('Y',$epoch))-1;
		//Debug::text('Begin Day Epoch: '. $retval .' - '. dDate::getDate('DATE+TIME', $retval) , __FILE__, __LINE__, __METHOD__, 10);
		return $retval;
	}

	public static function getBeginMonthEpoch($epoch = NULL) {
		if ($epoch == NULL OR $epoch == '' OR !is_numeric($epoch) ) {
			$epoch = self::getTime();
		}

		$retval=mktime(0,0,0,date('m',$epoch),1,date('Y',$epoch));

		return $retval;
	}

	public static function getEndMonthEpoch($epoch = NULL, $preserve_hours = FALSE) {
		if ($epoch == NULL OR $epoch == '' OR !is_numeric($epoch)) {
			$epoch = self::getTime();
		}

		$retval=mktime(0,0,0,date('m',$epoch) + 1,1,date('Y',$epoch)) - 1;

		return $retval;
	}

	public static function getBeginYearEpoch($epoch = NULL) {
		if ($epoch == NULL OR $epoch == '' OR !is_numeric($epoch) ) {
			$epoch = self::getTime();
		}

		$retval=mktime(0,0,0,1,1,date('Y',$epoch));

		return $retval;
	}

	public static function getEndYearEpoch($epoch = NULL) {
		if ($epoch == NULL OR $epoch == '' OR !is_numeric($epoch) ) {
			$epoch = self::getTime();
		}

		//Debug::text('Attempting to Find End Of Year epoch for: '. dDate::getDate('DATE+TIME', $epoch), __FILE__, __LINE__, __METHOD__,10);

		$retval=mktime(0,0,0,1,1,date('Y',$epoch) + 1) - 1;

		return $retval;
	}

	public static function getYearQuarter( $epoch = NULL ) {
		if ($epoch == NULL OR $epoch == '' OR !is_numeric($epoch) ) {
			$epoch = self::getTime();
		}

		$quarter = ceil( date('n', $epoch ) / 3 );

//		Debug::text('Date: '. dDate::getDate('DATE+TIME', $epoch ) .' is in quarter: '. $quarter, __FILE__, __LINE__, __METHOD__,10);
		return $quarter;
	}

	public static function getDateOfNextDayOfWeek($anchor_epoch, $day_of_week_epoch) {
		//Anchor Epoch is the anchor date to start searching from.
		//Day of week epoch is the epoch we use to extract the day of the week from.
//		Debug::text('-------- ', __FILE__, __LINE__, __METHOD__,10);
//		Debug::text('Anchor Epoch: '. dDate::getDate('DATE+TIME', $anchor_epoch), __FILE__, __LINE__, __METHOD__,10);
//		Debug::text('Day Of Week Epoch: '. dDate::getDate('DATE+TIME', $day_of_week_epoch), __FILE__, __LINE__, __METHOD__,10);

		if ( $anchor_epoch == '' ) {
			return FALSE;
		}

		if ( $day_of_week_epoch == '' ) {
			return FALSE;
		}

		//Get day of week of the anchor
		$anchor_dow = date('w', $anchor_epoch);
		$dst_dow = date('w', $day_of_week_epoch);
//		Debug::text('Anchor DOW: '. $anchor_dow .' Destination DOW: '. $dst_dow, __FILE__, __LINE__, __METHOD__,10);

		$days_diff = ($anchor_dow - $dst_dow);
//		Debug::text('Days Diff: '. $days_diff, __FILE__, __LINE__, __METHOD__,10);

		if ( $days_diff > 0 ) {
			//Add 7 days (1 week) then minus the days diff.
			$anchor_epoch += 604800;
		}

		$retval = mktime( 	date('H', $day_of_week_epoch ),
							date('i', $day_of_week_epoch ),
							date('s', $day_of_week_epoch ),
							date('m', $anchor_epoch ),
							date('j', $anchor_epoch ) - $days_diff,
							date('Y', $anchor_epoch )
							);

//		Debug::text('Retval: '. dDate::getDate('DATE+TIME', $retval), __FILE__, __LINE__, __METHOD__,10);
		return $retval;

	}

	public static function getDateOfNextDayOfMonth($anchor_epoch, $day_of_month_epoch, $day_of_month = NULL ) {
		//Anchor Epoch is the anchor date to start searching from.
		//Day of month epoch is the epoch we use to extract the day of the month from.
//		Debug::text('-------- ', __FILE__, __LINE__, __METHOD__,10);
//		Debug::text('Anchor Epoch: '. dDate::getDate('DATE+TIME', $anchor_epoch) . ' Day Of Month Epoch: '. dDate::getDate('DATE+TIME', $day_of_month_epoch) .' Day Of Month: '. $day_of_month, __FILE__, __LINE__, __METHOD__,10);

		if ( $anchor_epoch == '' ) {
			return FALSE;
		}

		if ( $day_of_month_epoch == '' AND $day_of_month == '' ) {
			return FALSE;
		}

		if ( $day_of_month_epoch == '' AND $day_of_month != '' AND $day_of_month <= 31 ) {
			$tmp_days_in_month = dDate::getDaysInMonth( $anchor_epoch );
			if ( $day_of_month > $tmp_days_in_month ) {
				$day_of_month = $tmp_days_in_month;
			}
			unset($tmp_days_in_month);

			$day_of_month_epoch = mktime( 	date('H', $anchor_epoch ),
											date('i', $anchor_epoch ),
											date('s', $anchor_epoch ),
											date('m', $anchor_epoch ),
											$day_of_month,
											date('Y', $anchor_epoch )
								);
		}

		//If the anchor date is AFTER the day of the month, we want to get the same day
		//in the NEXT month.
		$src_dom = date('j', $anchor_epoch);
		$dst_dom = date('j', $day_of_month_epoch);
		//Debug::text('Anchor DOM: '. $src_dom .' DST DOM: '. $dst_dom, __FILE__, __LINE__, __METHOD__,10);

		if ( $src_dom > $dst_dom ) {
			//Debug::text('Anchor DOM is greater then Dest DOM', __FILE__, __LINE__, __METHOD__,10);

			//Get the epoch of the first day of the next month
			$anchor_epoch = dDate::getEndMonthEpoch( $anchor_epoch )+1;

			//Find out how many days are in this month
			$days_in_month = dDate::getDaysInMonth( $anchor_epoch );

			if ( $dst_dom > $days_in_month ) {
				$dst_dom = $days_in_month;
			}

			$retval = $anchor_epoch + (($dst_dom-1)*86400);
		} else {
			//Debug::text('Anchor DOM is equal or LESS then Dest DOM', __FILE__, __LINE__, __METHOD__,10);

			$retval = mktime( 	date('H', $anchor_epoch ),
								date('i', $anchor_epoch ),
								date('s', $anchor_epoch ),
								date('m', $anchor_epoch ),
								date('j', $day_of_month_epoch ),
								date('Y', $anchor_epoch )
								);
		}

		return $retval;
	}

	public static function getLastHireDateAnniversary($hire_date) {
//		Debug::Text('Hire Date: '. $hire_date .' - '. dDate::getDate('DATE+TIME', $hire_date) , __FILE__, __LINE__, __METHOD__,10);

		//Find last hire date anniversery.
		$last_hire_date_anniversary = gmmktime(12,0,0, date('n',$hire_date), date('j',$hire_date), ( date('Y', dDate::getTime() ) ) );
		//If its after todays date, minus a year from it.
		if ( $last_hire_date_anniversary >= dDate::getTime() ) {
			$last_hire_date_anniversary = mktime(0,0,0, date('n',$hire_date), date('j',$hire_date), ( date('Y', dDate::getTime() ) - 1) );
		}
//		Debug::Text('Last Hire Date Anniversary: '. $last_hire_date_anniversary .' - '. dDate::getDate('DATE+TIME', $last_hire_date_anniversary) , __FILE__, __LINE__, __METHOD__,10);

		return $last_hire_date_anniversary;
	}

	public static function getBeginWeekEpoch($epoch = NULL, $start_day_of_week = 0 ) {
		if ($epoch == NULL OR $epoch == '') {
			$epoch = self::getTime();
		}

		if (  !is_numeric( $start_day_of_week ) ) {
			if ( strtolower($start_day_of_week) == 'mon' ) {
				$start_day_of_week = 1;
			} elseif ( strtolower($start_day_of_week) == 'sun' ) {
				$start_day_of_week = 0;
			}
		}

		//Get day of week
		$day_of_week = date('w', $epoch);
//		Debug::text('Current Day of week: '. $day_of_week, __FILE__, __LINE__, __METHOD__,10);

		$offset = 0;
		if ( $day_of_week < $start_day_of_week ) {
			$offset = 7 + ($day_of_week - $start_day_of_week);
		} else {
			$offset = $day_of_week - $start_day_of_week;
		}

		$retval = mktime(0,0,0,date("m",$epoch),( date("j", $epoch) - $offset) ,date("Y",$epoch) );

//		Debug::text(' Epoch: '. dDate::getDate('DATE+TIME', $epoch) .' Retval: '. dDate::getDate('DATE+TIME', $retval) .' Start Day of Week: '. $start_day_of_week .' Offset: '. $offset, __FILE__, __LINE__, __METHOD__,10);
		return $retval;
	}

	public static function getEndWeekEpoch($epoch = NULL, $start_day_of_week = 0 ) {
		if ( $epoch == NULL OR $epoch == '' ) {
			$epoch = self::getTime();
		}

		$retval = self::getEndDayEpoch( self::getMiddleDayEpoch( self::getBeginWeekEpoch( self::getMiddleDayEpoch($epoch), $start_day_of_week ) ) + (86400*6) );

		return $retval;
	}

	public static function getWeek( $epoch = NULL, $start_day_of_week = 1 ) {
		//Default start_day_of_week to 1 (Monday) as that is what PHP defaults to.
		if ($epoch == NULL OR $epoch == '') {
			$epoch = self::getTime();
		}

		$raw_week = (int)date('W', $epoch);
		if ( $start_day_of_week == 1 ) {
			//Start day of week is set to Monday, so it matches PHP.
			$retval = $raw_week;
		} else {
			$epoch_dow = date('w', $epoch);

			//Get day of week for Jan 1st of this year.
			$jan_1st_dow = dDate::getDayOfWeek( mktime( 12,0,0, dDate::getMonth( $epoch ), 1, dDate::getYear( $epoch ) ) );

			if ( $start_day_of_week == $epoch_dow ) {
				$dow_diff = 1;
			} else {
				$dow_diff = $start_day_of_week*-1;
			}

			$retval = (int)date('W', mktime( 12,0,0, dDate::getMonth( $epoch ), dDate::getDayOfMonth( $epoch ) + $dow_diff, dDate::getYear( $epoch ) ) );

		}

		return $retval;
	}

	public static function getYear($epoch = NULL) {
		if ($epoch == NULL) {
			$epoch = dDate::getTime();
		}

		return date('Y', $epoch);
	}

	public static function getMonth( $epoch = NULL ) {
		if ($epoch == NULL OR $epoch == '') {
			$epoch = self::getTime();
		}

		return date('n', $epoch);
	}

	public static function getDayOfMonth( $epoch = NULL ) {
		if ($epoch == NULL OR $epoch == '') {
			$epoch = self::getTime();
		}

		return date('j', $epoch);
	}

	public static function getHour( $epoch = NULL ) {
		if ($epoch == NULL OR $epoch == '') {
			$epoch = self::getTime();
		}

		return date('G', $epoch);
	}

	public static function getMinute( $epoch = NULL ) {
		if ($epoch == NULL OR $epoch == '') {
			$epoch = self::getTime();
		}

		return date('i', $epoch);
	}

	public static function getSecond( $epoch = NULL ) {
		if ($epoch == NULL OR $epoch == '') {
			$epoch = self::getTime();
		}

		return date('s', $epoch);
	}

	public static function isWeekDay($epoch = NULL) {
		if ($epoch == NULL OR empty($epoch)) {
			$epoch = dDate::getTime();
		}

		$day_of_week = date('w', $epoch);
		//Make sure day is not Sat. or Sun
		if ($day_of_week != 0 AND $day_of_week != 6) {
			//Definitely a business day of week, make sure its not a holiday now.
			return TRUE;
		}

		return FALSE;
	}

	public static function getAnnualWeekDays($epoch = NULL) {
		if ($epoch == NULL OR $epoch == '') {
			$epoch = self::getTime();
		}

		//Get the year of the passed epoch
		$year = date('Y', $epoch);

		$end_date = mktime(0,0,0,1,0, $year + 1 );
		$end_day_of_week = date("w",$end_date);
		$second_end_day_of_week = date("w",$end_date - 86400);
		//Debug::text('End Date: ('.$end_day_of_week.') '. $end_date .' - '. dDate::getDate('DATE+TIME', $end_date), __FILE__, __LINE__, __METHOD__, 10);
		//Debug::text('2nd End Date: ('.$second_end_day_of_week.') '. ( $end_date - 86400 ) .' - '. dDate::getDate('DATE+TIME', ($end_date - 86400 ) ), __FILE__, __LINE__, __METHOD__, 10);

		//Eriks method
		//Always start with 260 days.
		//If the last day of the year is a weekday, add 1
		//If its a leap year, use the 2 last days. If any of them are weekdays, add them.
		$start_days = 260;

		//Debug::text('Leap Year: '. date('L', $end_date), __FILE__, __LINE__, __METHOD__, 10);

		if ( date('L', $end_date) == 1 ) {
			//Leap year
			if ( $end_day_of_week != 0 AND $end_day_of_week != 6) {
				$start_days++;
			}
			if ( $second_end_day_of_week != 0 AND $second_end_day_of_week != 6) {
				$start_days++;
			}

		} else {
			//Not leap year

			if ( $end_day_of_week != 0 AND $end_day_of_week != 6) {
				$start_days++;
			}

		}
		//Debug::text('Days in Year: ('. $year .'): '. $start_days, __FILE__, __LINE__, __METHOD__, 10);


		return $start_days;
	}

	//Loop from filter start date to end date. Creating an array entry for each day.
	function getCalendarArray($start_date, $end_date, $start_day_of_week = 0, $force_weeks = TRUE) {
		if ( $start_date == '' OR $end_date == '' ) {
			return FALSE;
		}

		//Which day begins the week, Mon or Sun?
		//0 = Sun 1 = Mon
		/*
		if ( strtolower($start_day_of_week) == 'mon' OR $start_day_of_week == 1) {
			$start_day_of_week = 1;
		} else {
			$start_day_of_week = 0;
		}
		*/

//		Debug::text(' Start Day Of Week: '. $start_day_of_week , __FILE__, __LINE__, __METHOD__,10);

//		Debug::text(' Raw Start Date: '. dDate::getDate('DATE+TIME', $start_date) .' Raw End Date: '. dDate::getDate('DATE+TIME', $end_date) , __FILE__, __LINE__, __METHOD__,10);

		if ( $force_weeks == TRUE ) {
			$cal_start_date = dDate::getBeginWeekEpoch($start_date, $start_day_of_week);
			//$cal_end_date = dDate::getEndWeekEpoch($end_date, $start_day_of_week);
			$cal_end_date = dDate::getEndWeekEpoch($end_date, $start_day_of_week);
		} else {
			$cal_start_date = $start_date;
			$cal_end_date = $end_date;
		}

//		Debug::text(' Cal Start Date: '. dDate::getDate('DATE+TIME', $cal_start_date) .' Cal End Date: '. dDate::getDate('DATE+TIME', $cal_end_date) , __FILE__, __LINE__, __METHOD__,10);

		$prev_month=NULL;
		//$prev_week=NULL;
		$x=0;
		//Gotta add more then 86400 because of day light savings time.
		//Causes infinite loop without it.
		//Don't add 7200 to Cal End Date because that could cause more then one
		//week to be displayed.

		//for($i=$cal_start_date; $i <= $cal_end_date; $i+=86400) {
		//for($i=$cal_start_date; $i <= ($cal_end_date+7200); $i+=93600) {
		for($i=$cal_start_date; $i <= ($cal_end_date); $i+=93600) {
			if ( $x > 200 ) {
				break;
			}

			$i = dDate::getBeginDayEpoch($i);

			$current_month = date('n', $i);
			//$current_week = date('W', $i);
			$current_day_of_week = date('w', $i);

			if ( $current_month != $prev_month AND $i >= $start_date ) {
				$isNewMonth = TRUE;
			} else {
				$isNewMonth = FALSE;
			}

			//if ( $current_week != $prev_week ) {
			if ( $current_day_of_week == $start_day_of_week ) {
				$isNewWeek = TRUE;
			} else {
				$isNewWeek = FALSE;
			}

			//Display only blank boxes if the date is before the filter start date, or after.
			if ( $i >= $start_date AND $i <= $end_date ) {
				$day_of_week = ( date('D', $i) ); // i18n: these short day strings may not be in .po file.
				$day_of_month = date('j', $i);
				$month_name = ( date('F', $i) ); // i18n: these short month strings may not be defined in .po file.
			} else {
				//Always have the day of the week at least.
				//$day_of_week = ( date('D', $i) ); // i18n: these short day strings may not be in .po file.
				$day_of_week = NULL;
				$day_of_month = NULL;
				$month_name = NULL;
			}

			$retarr[] = array(
							'epoch' => $i,
							'date_stamp' => dDate::getISODateStamp( $i ),
							'start_day_of_week' => $start_day_of_week,
							'day_of_week' => $day_of_week,
							'day_of_month' => $day_of_month,
							'month_name' => $month_name,
							'month_short_name' => substr($month_name,0,3),
							'month' => $current_month,
							'isNewMonth' => $isNewMonth,
							'isNewWeek' => $isNewWeek
							);

			$prev_month = $current_month;
			//$prev_week = $current_week;

			//Debug::text('i: '. $i .' Date: '. dDate::getDate('DATE+TIME', $i), __FILE__, __LINE__, __METHOD__,10);
			$x++;
		}

		return $retarr;
	}

	function inWindow( $epoch, $window_epoch, $window ) {
//		Debug::text(' Epoch: '. dDate::getDate('DATE+TIME', $epoch ) .' Window Epoch: '. dDate::getDate('DATE+TIME', $window_epoch ) .' Window: '. $window , __FILE__, __LINE__, __METHOD__,10);

		if ( $epoch >= ( $window_epoch - $window )
				AND $epoch <= ( $window_epoch + $window ) ) {
//			Debug::text(' Within Window', __FILE__, __LINE__, __METHOD__,10);
			return TRUE;
		}

//		Debug::text(' NOT Within Window', __FILE__, __LINE__, __METHOD__,10);

		return FALSE;
	}

	//Date pair1
	function getTimeOverLapDifference($start_date1, $end_date1, $start_date2, $end_date2) {
		//Find out if Date1 overlaps with Date2
		if ( $start_date1 == '' OR $end_date1 == '' OR $start_date2 == '' OR $end_date2 == '') {
			return FALSE;
		}

		//Debug::text(' Checking if Start Date: '. dDate::getDate('DATE+TIME', $start_date1 ) .' End Date: '. dDate::getDate('DATE+TIME', $end_date1 ) , __FILE__, __LINE__, __METHOD__,10);
		//Debug::text('   Overlap Start Date: '. dDate::getDate('DATE+TIME', $start_date2 ) .' End Date: '. dDate::getDate('DATE+TIME', $end_date2 ) , __FILE__, __LINE__, __METHOD__,10);

		/*
 			  |-----------------------| <-- Date Pair 1
				1. |-------| <-- Date Pair2
					2.   |-------------------------|
		3. |-----------------------|
		4. |------------------------------------------|

		*/
		if 	( ($start_date2 >= $start_date1 AND $end_date2 <= $end_date1) ) { //Case #1
//			Debug::text(' Overlap on Case #1: ', __FILE__, __LINE__, __METHOD__,10);
			$retval = $end_date2 - $start_date2;
		} elseif ( ($start_date2 >= $start_date1 AND $start_date2 <= $end_date1) ) { //Case #2
//			Debug::text(' Overlap on Case #2: ', __FILE__, __LINE__, __METHOD__,10);
			$retval = $end_date1 - $start_date2;
		} elseif ( ($end_date2 >= $start_date1 AND $end_date2 <= $end_date1) ) { //Case #3
//			Debug::text(' Overlap on Case #3: ', __FILE__, __LINE__, __METHOD__,10);
			$retval = $end_date2 - $start_date1;
		} elseif ( ($start_date2 <= $start_date1 AND $end_date2 >= $end_date1) ) { //Case #4
//			Debug::text(' Overlap on Case #4: ', __FILE__, __LINE__, __METHOD__,10);
			$retval = $end_date1 - $start_date1;
		}

		if (  isset($retval) ) {
//			Debug::text(' Overlap Time Difference: '. $retval, __FILE__, __LINE__, __METHOD__,10);
			return $retval;
		}

		return FALSE;
	}

	function isTimeOverLap($start_date1, $end_date1, $start_date2, $end_date2) {
		//Find out if Date1 overlaps with Date2
		if ( $start_date1 == '' OR $end_date1 == '' OR $start_date2 == '' OR $end_date2 == '') {
			return FALSE;
		}

		//Debug::text(' Checking if Start Date: '. dDate::getDate('DATE+TIME', $start_date1 ) .' End Date: '. dDate::getDate('DATE+TIME', $end_date1 ) , __FILE__, __LINE__, __METHOD__,10);
		//Debug::text('   Overlap Start Date: '. dDate::getDate('DATE+TIME', $start_date2 ) .' End Date: '. dDate::getDate('DATE+TIME', $end_date2 ) , __FILE__, __LINE__, __METHOD__,10);

		/*
 			  |-----------------------|
				1. |-------|
					2.   |-------------------------|
		3. |-----------------------|
		4. |------------------------------------------|

		*/
		if 	( ($start_date2 >= $start_date1 AND $end_date2 <= $end_date1) ) { //Case #1
			//Debug::text(' Overlap on Case #1: ', __FILE__, __LINE__, __METHOD__,10);

			return TRUE;
		}

		//Allow case where there are several shifts in a day, ie:
		// 8:00AM to 1:00PM, 1:00PM to 5:00PM, where the end and start times match exactly.
		//if 	( ($start_date2 >= $start_date1 AND $start_date2 <= $end_date1) ) { //Case #2
		if 	( ($start_date2 >= $start_date1 AND $start_date2 < $end_date1) ) { //Case #2
			//Debug::text(' Overlap on Case #2: ', __FILE__, __LINE__, __METHOD__,10);

			return TRUE;
		}

		//Allow case where there are several shifts in a day, ie:
		// 8:00AM to 1:00PM, 1:00PM to 5:00PM, where the end and start times match exactly.
		//if 	( ($end_date2 >= $start_date1 AND $end_date2 <= $end_date1) ) { //Case #3
		if 	( ($end_date2 > $start_date1 AND $end_date2 <= $end_date1) ) { //Case #3
			//Debug::text(' Overlap on Case #3: ', __FILE__, __LINE__, __METHOD__,10);

			return TRUE;
		}

		if 	( ($start_date2 <= $start_date1 AND $end_date2 >= $end_date1) ) { //Case #4
			//Debug::text(' Overlap on Case #4: ', __FILE__, __LINE__, __METHOD__,10);

			return TRUE;
		}

		return FALSE;
	}

	public static function getTimeLockedDate($time_epoch, $date_epoch) {
		$epoch = mktime( 	date('H', $time_epoch ),
							date('i', $time_epoch ),
							date('s', $time_epoch ),
							date('m', $date_epoch ),
							date('j', $date_epoch ),
							date('Y', $date_epoch )
							);
		return $epoch;

	}


	// Function to return "13 mins ago" text from a given time.
	public static function getHumanTimeSince($epoch) {
        if (time() >= $epoch) {

                $epoch_since = time() - $epoch;
				//Debug::text(' Epoch Since: '. $epoch_since, __FILE__, __LINE__, __METHOD__,10);
                switch (true) {

                        case ($epoch_since > 31536000):
                                //Years
                                $num = ( ( ( ( ($epoch_since / 60) / 60) / 24 ) / 30 )  / 12 );
                                $suffix = ('yr');
                                break;
                        //case ($epoch_since > 2592000):
                        case ($epoch_since > ((3600 * 24) * 60)):
                                //Months the above number should be 2 months, so we don't get 0 months showing up.
                                $num = ( ( ( ( ($epoch_since / 60) / 60) / 24 ) / 30 ) );
                                $suffix = ('mth');
                                break;
                        case ($epoch_since > 604800):
                                //Weeks
                                $num = ( ( ( ($epoch_since / 60) / 60) / 24 ) / 7 ) ;
                                $suffix = ('wk');
                                break;
                        case ($epoch_since > 86400):
                                //Days
                                $num = ( ( ($epoch_since / 60) / 60) / 24 );
                                $suffix = ('day');
                                break;
                        case ($epoch_since > 3600):
                                //Hours
                                $num = ( ($epoch_since / 60) / 60);
                                $suffix = ('hr');

                                break;
                        case ($epoch_since > 60):
                                //Mins
                                $num = ($epoch_since / 60);
                                $suffix = ('min');
                                break;
                        default:
                                //Secs
                                $num = $epoch_since;
                                $suffix = ('sec');

                                break;

                }

				if ( $num > 1 ) {
					$suffix .= ('s');
				}

				//Debug::text(' Num: '. $num .' Suffix: '. $suffix, __FILE__, __LINE__, __METHOD__,10);
                return sprintf("%0.01f",$num)." ".$suffix;
        } else {
//			Debug::text(' Returning False', __FILE__, __LINE__, __METHOD__,10);
            return FALSE;
        }

	}

	//Runs strtotime over a string, but if it happens to be an epoch, strtotime
	//returns -1, so in this case, just return the epoch again.
	public static function strtotime($str) {
		if ( is_numeric($str) ) {
			return $str;
		}

		//Debug::text(' Original String: '. $str, __FILE__, __LINE__, __METHOD__,10);
		$retval = strtotime($str);
		//Debug::text(' After strotime String: '. $retval, __FILE__, __LINE__, __METHOD__,10);

		if ( $retval == -1 OR $retval === FALSE ) {
			return $str;
		}

		return $retval;
	}

	public static function isBindTimeStamp( $str ) {
		if ( strpos( $str, '-') === FALSE ) {
			return FALSE;
		}

		return TRUE;
	}
}

?>
