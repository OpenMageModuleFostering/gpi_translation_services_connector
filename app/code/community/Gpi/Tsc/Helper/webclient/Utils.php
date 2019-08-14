<?php

/**
 * 
 *
 */
class Utils {

	/**
	 * 
	 * @return
	 */
	private static function getDefaultKey() {
		$bytes = new byte[0];
		try {
			$bytes =  "Gl0b4l12a7i0n".getBytes("UTF-8");
		} catch ($e) {			
		
        }
		return $bytes;
	}	
	
	/**
	 * 
	 * @param bytes
	 * @return
	 */
	public static function decodeUTF8($bytes) {
		return new String(bytes, UTF8_CHARSET);
        /*string utf8_decode ( string $data )
            
            $output_utf8 = mb_convert_encoding($output, 'utf-8', 'utf-8')*/
	}
	
	/**
	 * 
	 * @param string
	 * @return
	 */
	public static function encodeUTF8($string) {
		return string.getBytes(UTF8_CHARSET);
	}
	
	/**
	 * 
	 * @param object
	 * @return
	 */
	public static function encrypt($object) {
		if (null == $object) 
			return "";
		
		$json = serialize($object);
		
		$key = self::getDefaultKey();
		$data = self::encodeUTF8(json);
		$encryptedBytes = XXTEA::encrypt($key, $data);
		return base64_encode($encryptedBytes);
	}
	
	/**
	 * 
	 * @param value
	 * @param classOfT
	 * @return
	 */
	public static function decrypt($value) {
		$key = self::getDefaultKey();
		$data = base64_decode($value);		
		$decryptedBytes = XXTEA::decrypt($key, $data);
		$json = self::decodeUTF8($decryptedBytes);
		
		return unserialize($json);
	}
}
