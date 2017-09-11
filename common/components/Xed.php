<?php
namespace common\components;
/**
 * Class XED
 * https://github.com/Ejz/Common/blob/master/other/xed.class.php
 */

class XED {
	private static $salt = '@t~jLVgBiJ';
	public static function encrypt($string, $key = null) {
		$len = strlen($string);
		$gamma = '';
		$n = $len>100 ? 8 : 2;
		while( strlen($gamma)<$len )
		{
			$gamma .= substr(pack('H*', sha1($key.$gamma.self::$salt)), 0, $n);
		}
		$res = $string^$gamma;
		$res = self::base64encode($res);
		return $res;
	}
	public static function decrypt($string, $key = null) {
		$string = self::base64decode($string);
		$len = strlen($string);
		$gamma = '';
		$n = $len>100 ? 8 : 2;
		while( strlen($gamma)<$len )
		{
			$gamma .= substr(pack('H*', sha1($key.$gamma.self::$salt)), 0, $n);
		}
		return $string^$gamma;
	}
	private static function base64encode($data) { // URL
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}
	private static function base64decode($data) { // URL
		return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}
}