<?php

/**
 * @package Core
 */
class Misc {
	/*
		this method assumes that the form has one or more
		submit buttons and that they are named according
		to this scheme:

		<input type="submit" name="submit:command" value="some value">

		This is useful for identifying which submit button actually
		submitted the form.
	*/
	static function findSubmitButton( $prefix = 'action' ) {
		// search post vars, then get vars.
		$queries = array($_POST, $_GET);
		foreach($queries as $query) {
			foreach($query as $key => $value) {
				//Debug::Text('Key: '. $key .' Value: '. $value, __FILE__, __LINE__, __METHOD__,10);
				$newvar = explode(':', $key, 2);
				//Debug::Text('Explode 0: '. $newvar[0] .' 1: '. $newvar[1], __FILE__, __LINE__, __METHOD__,10);
				 if ( isset($newvar[0]) AND isset($newvar[1]) AND $newvar[0] === $prefix ) {
					$val = $newvar[1];

					// input type=image stupidly appends _x and _y.
					if ( substr($val, strlen($val) - 2) === '_x' ) {
						$val = substr($val, 0, strlen($val) - 2);
					}

					//Debug::Text('Found Button: '. $val, __FILE__, __LINE__, __METHOD__,10);
					return strtolower($val);
				}
			}
		}

		return NULL;
	}

	static function getSortDirectionArray( $text_keys = FALSE ) {
		if ( $text_keys === TRUE ) {
			return array('asc' => 'ASC', 'desc' => 'DESC');
		} else {
			return array(1 => 'ASC', -1 => 'DESC');
		}
	}

	//This function totals arrays where the data wanting to be totaled is deep in a multi-dimentional array.
	//Usually a row array just before its passed to smarty.
	static function ArrayAssocSum($array, $element = NULL, $decimals = NULL) {

		$retarr = array();
		$totals = array();

		foreach($array as $key => $value) {
			if ( isset($element) AND isset($value[$element]) ) {
				foreach($value[$element] as $sum_key => $sum_value ) {
					if ( !isset($totals[$sum_key]) ) {
						$totals[$sum_key] = 0;
					}
					$totals[$sum_key] += $sum_value;
				}
			} else {
				//Debug::text(' Array Element not set: ', __FILE__, __LINE__, __METHOD__,10);
				foreach($value as $sum_key => $sum_value ) {
					if ( !isset($totals[$sum_key]) ) {
						$totals[$sum_key] = 0;
					}
					$totals[$sum_key] += $sum_value;
					//Debug::text(' Sum: '. $totals[$sum_key] .' Key: '. $sum_key .' This Value: '. $sum_value, __FILE__, __LINE__, __METHOD__,10);
				}
			}
		}

		//format totals
		if ( $decimals !== NULL ) {
			foreach($totals as $retarr_key => $retarr_value) {
				//echo "Key: $retarr_key Value: $retarr_value<br>\n";
				//Debug::text(' Number Formatting: '. $retarr_value , __FILE__, __LINE__, __METHOD__,10);
				$retarr[$retarr_key] = number_format($retarr_value, $decimals, '.','');
				//$retarr[$retarr_key] = round( $retarr_value, $decimals );
			}
		} else {
			return $totals;
		}
		unset($totals);

		return $retarr;
	}

	//This function is similar to a SQL group by clause, only its done on a AssocArray
	//Pass it a row array just before you send it to smarty.
	static function ArrayGroupBy($array, $group_by_elements, $ignore_elements = array() ) {

		if ( !is_array($group_by_elements) ) {
			$group_by_elements = array($group_by_elements);
		}

		if ( isset($ignore_elements) AND is_array($ignore_elements) ) {
			foreach($group_by_elements as $group_by_element) {
				//Remove the group by element from the ignore elements.
				unset($ignore_elements[$group_by_element]);
			}
		}

		$retarr = array();
		if ( is_array($array) ) {
			foreach( $array as $row) {
				$group_by_key_val = NULL;
				foreach($group_by_elements as $group_by_element) {
					if ( isset($row[$group_by_element]) ) {
						$group_by_key_val .= $row[$group_by_element];
					}
				}
				//Debug::Text('Group By Key Val: '. $group_by_key_val, __FILE__, __LINE__, __METHOD__,10);

				if ( !isset($retarr[$group_by_key_val]) ) {
					$retarr[$group_by_key_val] = array();
				}

				foreach( $row as $key => $val) {
					//Debug::text(' Key: '. $key .' Value: '. $val , __FILE__, __LINE__, __METHOD__,10);
					if ( in_array($key, $group_by_elements) ) {
						$retarr[$group_by_key_val][$key] = $val;
					} elseif( !in_array($key, $ignore_elements) ) {
						if ( isset($retarr[$group_by_key_val][$key]) ) {
							$retarr[$group_by_key_val][$key] = Misc::MoneyFormat( bcadd($retarr[$group_by_key_val][$key],$val), FALSE);
							//Debug::text(' Adding Value: '. $val .' For: '. $retarr[$group_by_key_val][$key], __FILE__, __LINE__, __METHOD__,10);
						} else {
							//Debug::text(' Setting Value: '. $val , __FILE__, __LINE__, __METHOD__,10);
							$retarr[$group_by_key_val][$key] = $val;
						}
					}
				}
			}
		}

		return $retarr;
	}

	static function ArrayAvg($arr) {

		if ((!is_array($arr)) OR (!count($arr) > 0)) {
			return FALSE;
		}

		return array_sum($arr) / count($arr);
	}

	static function prependArray($prepend_arr, $arr) {
		if ( !is_array($prepend_arr) AND is_array($arr) ) {
			return $arr;
		} elseif ( is_array($prepend_arr) AND !is_array($arr) ) {
			return $prepend_arr;
		} elseif ( !is_array($prepend_arr) AND !is_array($arr) ) {
			return FALSE;
		}

		$retarr = $prepend_arr;

		foreach($arr as $key => $value) {
			$retarr[$key] = $value;
		}

		return $retarr;
	}

	/*
		When passed an array of input_keys, and an array of output_key => output_values,
		this function will return all the output_key => output_value pairs where
		input_key == output_key
	*/
	static function arrayIntersectByKey( $keys, $options ) {
		if ( is_array($keys) and is_array($options) ) {
			foreach( $keys as $key ) {
				if ( isset($options[$key]) ) {
					$retarr[$key] = $options[$key];
				}
			}

			if ( isset($retarr) ) {
				return $retarr;
			}
		}

		//Return NULL because if we return FALSE smarty will enter a
		//"blank" option into select boxes.
		return NULL;
	}

	/*
		Returns all the output_key => output_value pairs where
		the input_keys are not present in output array keys.

	*/
	static function arrayDiffByKey( $keys, $options ) {
		if ( is_array($keys) and is_array($options) ) {
			foreach( $options as $key => $value ) {
				if ( !in_array($key, $keys) ) {
					$retarr[$key] = $options[$key];
				}
			}

			if ( isset($retarr) ) {
				return $retarr;
			}
		}

		//Return NULL because if we return FALSE smarty will enter a
		//"blank" option into select boxes.
		return NULL;
	}

	static function array_diff_assoc_recursive($array1, $array2) {
		foreach($array1 as $key => $value) {
			if ( is_array($value) ) {
				  if ( !isset($array2[$key]) ) {
					  $difference[$key] = $value;
				  } elseif( !is_array($array2[$key]) ) {
					  $difference[$key] = $value;
				  } else {
					  $new_diff = self::array_diff_assoc_recursive($value, $array2[$key]);
					  if ( $new_diff !== FALSE ) {
							$difference[$key] = $new_diff;
					  }
				  }
			  } elseif ( !isset($array2[$key]) OR $array2[$key] != $value ) {
				  $difference[$key] = $value;
			  }
		}

		if ( !isset($difference) ) {
			return FALSE;
		}

		return $difference;
	}

	static function trimSortPrefix( $value, $trim_arr_value = FALSE ) {
		if ( is_array($value) AND count($value) > 0 ) {
			foreach( $value as $key => $val ) {
				if ( $trim_arr_value == TRUE ) {
					$retval[$key] = preg_replace('/^-[0-9]{3,4}-/i', '', $val);
				} else {
					$retval[preg_replace('/^-[0-9]{3,4}-/i', '', $key)] = $val;
				}
			}
		} else {
			$retval = preg_replace('/^-[0-9]{3,4}-/i', '', $value );
		}

		if ( isset($retval) ) {
			return $retval;
		}

		return $value;
	}

	static function FileDownloadHeader($file_name, $type, $size) {
		if ( $file_name == '' OR $size == '') {
			return FALSE;
		}

		$agent = trim($_SERVER['HTTP_USER_AGENT']);
		if ((preg_match('|MSIE ([0-9.]+)|', $agent, $version)) OR
			(preg_match('|Internet Explorer/([0-9.]+)|', $agent, $version))) {
			//header('Content-Type: application/x-msdownload');
			Header('Content-Type: '. $type);
			if ($version == '5.5') {
				header('Content-Disposition: filename="'.$file_name.'"');
			} else {
				header('Content-Disposition: attachment; filename="'.$file_name.'"');
			}
		} else {
			Header('Content-Type: '. $type);
			Header('Content-disposition: inline; filename='.$file_name);
		}

		Header('Content-Length: '. $size);

		return TRUE;
	}

	static function MoneyFormat($value, $pretty = TRUE) {

		if ( $pretty == TRUE ) {
			$thousand_sep = ',';
		} else {
			$thousand_sep = '';
		}

		return number_format( $value, 2, '.', $thousand_sep);
	}

	static function TruncateString( $str, $length, $start = 0 ) {
		$retval = trim( substr( $str, $start, $length ) );
		if ( strlen( $str ) > $length ) {
			$retval .= '...';
		}

		return $retval;
	}

	static function HumanBoolean($bool) {
		if ( $bool == TRUE ) {
			return 'Yes';
		} else {
			return 'No';
		}
	}

	static function getBeforeDecimal($float) {
		$float_array = split("\.", $float);

		return $float_array[0];
	}

	static function getAfterDecimal($float) {
		$float_array = split("\.", $float);

		return $float_array[1];
	}

	static function calculatePercent( $current, $maximum, $precision = 0 ) {
		if ( $maximum == 0 ) {
			return 100;
		}

		$percent = round( ( ( $current / $maximum ) * 100 ), (int)$precision );

		if ( $precision == 0 ) {
			$percent = (int)$percent;
		}

		return $percent;
	}

	//Takes an array with columns, and a 2nd array with column names to sum.
	static function sumMultipleColumns($data, $sum_elements) {
		if (!is_array($data) ) {
			return FALSE;
		}

		if (!is_array($sum_elements) ) {
			return FALSE;
		}

		$retval = 0;

		foreach($sum_elements as $sum_element ) {
			if ( isset($data[$sum_element]) ) {
				$retval = bcadd( $retval, $data[$sum_element]);
				//Debug::Text('Found Element in Source Data: '. $sum_element .' Retval: '. $retval, __FILE__, __LINE__, __METHOD__,10);
			}
		}

		return $retval;
	}

	static function getPointerFromArray( $array, $element, $start = 1 ) {
		//Debug::Arr($array, 'Source Array: ', __FILE__, __LINE__, __METHOD__,10);
		//Debug::Text('Searching for Element: '. $element, __FILE__, __LINE__, __METHOD__,10);
		$keys = array_keys( $array );
		//Debug::Arr($keys, 'Source Array Keys: ', __FILE__, __LINE__, __METHOD__,10);

		//Debug::Text($keys, 'Source Array Keys: ', __FILE__, __LINE__, __METHOD__,10);
		$key = array_search( $element, $keys );

		if ( $key !== FALSE ) {
			$key = $key + $start;
		}

		//Debug::Arr($key, 'Result: ', __FILE__, __LINE__, __METHOD__,10);
		return $key;
	}

	static function AdjustXY( $coord, $adjust_coord) {
		return $coord + $adjust_coord;
	}

	function writeBarCodeFile($file_name, $num, $print_text = TRUE, $height = 60 ) {
		if ( !class_exists('Image_Barcode') ) {
			require_once(Environment::getBasePath().'/classes/Image_Barcode/Barcode.php');
		}

		ob_start();
		Image_Barcode::draw($num, 'code128', 'png', FALSE, $print_text, $height);
		$ob_contents = ob_get_contents();
		ob_end_clean();

		if ( file_put_contents($file_name, $ob_contents) > 0 ) {
			//echo "Writing file successfull<Br>\n";
			return TRUE;
		} else {
			//echo "Error writing file<Br>\n";
			return FALSE;
		}
	}

	static function hex2rgb($hex, $asString = true) {
		// strip off any leading #
		if (0 === strpos($hex, '#')) {
			$hex = substr($hex, 1);
		} else if (0 === strpos($hex, '&H')) {
			$hex = substr($hex, 2);
		}

		// break into hex 3-tuple
		$cutpoint = ceil(strlen($hex) / 2)-1;
		$rgb = explode(':', wordwrap($hex, $cutpoint, ':', $cutpoint), 3);

		// convert each tuple to decimal
		$rgb[0] = (isset($rgb[0]) ? hexdec($rgb[0]) : 0);
		$rgb[1] = (isset($rgb[1]) ? hexdec($rgb[1]) : 0);
		$rgb[2] = (isset($rgb[2]) ? hexdec($rgb[2]) : 0);

		return ($asString ? "{$rgb[0]} {$rgb[1]} {$rgb[2]}" : $rgb);
	}

	static function Array2CSV( $data, $columns = NULL, $ignore_last_row = TRUE, $include_header = TRUE ) {
		if ( is_array($data) AND count($data) > 0
				AND is_array($columns) AND count($columns) > 0 ) {

			if ( $ignore_last_row === TRUE ) {
				array_pop($data);
			}

			//Header
			if ( $include_header == TRUE ) {
				foreach( $columns as $column_name ) {
					$row_header[] = $column_name;
				}
				$out = '"'.implode('","', $row_header).'"'."\n";
			} else {
				$out = NULL;
			}

			foreach( $data as $rows ) {
				foreach ($columns as $column_key => $column_name ) {
					if ( isset($rows[$column_key]) ) {
						$row_values[] = str_replace("\"", "\"\"", $rows[$column_key]);
					} else {
						//Make sure we insert blank columns to keep proper order of values.
						$row_values[] = NULL;
					}
				}

				$out .= '"'.implode('","', $row_values).'"'."\n";
				unset($row_values);
			}

			return $out;
		}

		return FALSE;
	}

	static function inArrayByKeyAndValue( $arr, $search_key, $search_value ) {
		if ( !is_array($arr) AND $search_key != '' AND $search_value != '') {
			return FALSE;
		}

		//Debug::Text('Search Key: '. $search_key .' Search Value: '. $search_value, __FILE__, __LINE__, __METHOD__,10);
		//Debug::Arr($arr, 'Hay Stack: ', __FILE__, __LINE__, __METHOD__,10);

		foreach( $arr as $arr_key => $arr_value ) {
			if ( isset($arr_value[$search_key]) ) {
				if ( $arr_value[$search_key] == $search_value ) {
					return TRUE;
				}
			}
		}

		return FALSE;
	}

	//This function is used to quickly preset array key => value pairs so we don't
	//have to have so many isset() checks throughout the code.
	static function preSetArrayValues( $arr, $keys, $preset_value = NULL ) {
		foreach( $keys as $key ) {
			if ( !isset($arr[$key]) ) {
				$arr[$key] = $preset_value;
			}
		}

		return $arr;
	}

	function parseCSV($file, $head = FALSE, $first_column = FALSE, $delim="," , $len = 9216, $max_lines = NULL ) {
		if ( !file_exists($file) ) {
//			Debug::text('Files does not exist: '. $file, __FILE__, __LINE__, __METHOD__, 10);
			return FALSE;
		}

		$return = false;
		$handle = fopen($file, "r");
		if ( $head !== FALSE ) {
			if ( $first_column !== FALSE ) {
			   while ( ($header = fgetcsv($handle, $len, $delim) ) !== FALSE) {
				   if ( $header[0] == $first_column ) {
					   //echo "FOUND HEADER!<br>\n";
					   $found_header = TRUE;
					   break;
				   }
			   }

			   if ( $found_header !== TRUE ) {
				   return FALSE;
			   }
			} else {
			   $header = fgetcsv($handle, $len, $delim);
			}
		}

		$i=1;
		while ( ($data = fgetcsv($handle, $len, $delim) ) !== FALSE) {
			if ( $head AND isset($header) ) {
				foreach ($header as $key => $heading) {
					$row[$heading] = ( isset($data[$key]) ) ? $data[$key] : '';
				}
				$return[] = $row;
			} else {
				$return[] = $data;
			}

			if ( $max_lines !== NULL AND $max_lines != '' AND $i == $max_lines ) {
				break;
			}

			$i++;
		}

		fclose($handle);

		return $return;
	}

	function importApplyColumnMap( $column_map, $csv_arr ) {
		if ( !is_array($column_map) ) {
			return FALSE;
		}

		if ( !is_array($csv_arr) ) {
			return FALSE;
		}

		foreach( $column_map as $map_arr ) {
			$timetrex_column = $map_arr['timetrex_column'];
			$csv_column = $map_arr['csv_column'];
			$default_value = $map_arr['default_value'];

			if ( isset($csv_arr[$csv_column]) AND $csv_arr[$csv_column] != '' ) {
				$retarr[$timetrex_column] = trim( $csv_arr[$csv_column] );
				//echo "NOT using default value: ". $default_value ."\n";
			} elseif ( $default_value != '' ) {
				//echo "using Default value! ". $default_value ."\n";
				$retarr[$timetrex_column] = trim( $default_value );
			}
		}

		if ( isset($retarr) ) {
			return $retarr;
		}

		return FALSE;
	}

	function importCallInputParseFunction( $function_name, $input, $default_value = NULL, $parse_hint = NULL ) {
		$full_function_name = 'parse_'.$function_name;

		if ( function_exists( $full_function_name ) ) {
			//echo "      Calling Custom Parse Function for: $function_name\n";
			return call_user_func( $full_function_name, $input, $default_value, $parse_hint );
		}

		return $input;
	}

	static function encrypt( $str, $key = NULL ) {
		if ( $str == '' ) {
			return FALSE;
		}

		if ( $key == NULL OR $key == '' ) {
			global $config_vars;
			$key = $config_vars['other']['salt'];
		}

		$td = mcrypt_module_open('tripledes', '', 'ecb', '');
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		$max_key_size = mcrypt_enc_get_key_size($td);
		mcrypt_generic_init($td, substr($key, 0, $max_key_size), $iv);

		$encrypted_data = base64_encode( mcrypt_generic($td, trim($str) ) );

		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		return $encrypted_data;
	}

	static function decrypt( $str, $key = NULL ) {
		if (  $key == NULL OR $key == '' ) {
			global $config_vars;
			$key = $config_vars['other']['salt'];
		}

		if ( $str == '' ) {
			return FALSE;
		}

		$td = mcrypt_module_open('tripledes', '', 'ecb', '');
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		$max_key_size = mcrypt_enc_get_key_size($td);
		mcrypt_generic_init($td, substr($key, 0, $max_key_size), $iv);

		$unencrypted_data = rtrim( mdecrypt_generic($td, base64_decode( $str ) ) );

		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		return $unencrypted_data;
	}

	static function getJSArray( $values, $name = NULL, $assoc = FALSE) {
		if ( $name != '' AND (bool)$assoc == TRUE ) {
			$retval = 'new Array();';
			if ( is_array($values) AND count($values) > 0 ) {
				foreach( $values as $key => $value ) {
					$retval .= $name.'[\''. $key .'\']=\''. $value .'\';';
				}
			}
		} else {
			$retval = 'new Array("';
			if ( is_array($values) AND count($values) > 0 ) {
				$retval .= implode('","', $values);
			}
			$retval .= '");';
		}

		return $retval;
	}

	static function array_isearch( $str, $array ) {
		foreach ( $array as $key => $value ) {
			if ( strtolower( $value ) == strtolower( $str ) ) {
				return $key;
			}
		}

		return FALSE;
	}

	//Uses the internal array pointer to get array neighnors.
	static function getArrayNeighbors( $arr, $key, $neighbor = 'both' ) {
		$neighbor = strtolower($neighbor);
		//Neighor can be: Prev, Next, Both

		$retarr = array( 'prev' => FALSE, 'next' => FALSE );

		$keys = array_keys($arr);
		$key_indexes = array_flip($keys);

		if ( $neighbor == 'prev' OR $neighbor == 'both' ) {
			if ( isset($keys[$key_indexes[$key]-1]) ) {
				$retarr['prev'] = $keys[$key_indexes[$key]-1];
			}
		}

		if ( $neighbor == 'next' OR $neighbor == 'both' ) {
			if ( isset($keys[$key_indexes[$key]+1]) ) {
				$retarr['next'] = $keys[$key_indexes[$key]+1];
			}
		}
		//next($arr);

		return $retarr;
	}

	//Converts a number between 0 and 25 to the corresponding letter.
	function NumberToLetter( $number ) {
		if ( $number > 25 ) {
			return FALSE;
		}

		return chr($number+65);
	}

	function issetOr( &$var, $default = NULL ) {
		if ( isset($var) ) {
			return $var;
		}

		return $default;
	}
}
?>