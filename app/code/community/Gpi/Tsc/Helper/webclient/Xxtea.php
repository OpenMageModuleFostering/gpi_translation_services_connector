<?php 

class Xxtea
{
	public static function encrypt($str, $key)
	{
		if ($str == "") {
			return "";
		}
		$v = Xxtea::str2long($str, true);
		$k = Xxtea::str2long($key, false);
		$n = count($v) - 1;

		$z = $v[$n];
		$y = $v[0];
		$delta = 0x9E3779B9;
		$q = floor(6 + 52 / ($n + 1));
		$sum = 0;
		while (0 < $q--) {
			$sum = Xxtea::int32($sum + $delta);
			$e = $sum >> 2 & 3;
			for ($p = 0; $p < $n; $p++) {
				$y = $v[$p + 1];
				$mx = Xxtea::int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ Xxtea::int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
				$z = $v[$p] = Xxtea::int32($v[$p] + $mx);
			}
			$y = $v[0];
			$mx = Xxtea::int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ Xxtea::int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
			$z = $v[$n] = Xxtea::int32($v[$n] + $mx);
		}
		return Xxtea::long2str($v, false);
	}

	public static function decrypt($str, $key)
	{
		if ($str == "") {
			return "";
		}
		$v = Xxtea::str2long($str, false);
		$k = Xxtea::str2long($key, false);
		$n = count($v) - 1;

		$z = $v[$n];
		$y = $v[0];
		$delta = 0x9E3779B9;
		$q = floor(6 + 52 / ($n + 1));
		$sum = Xxtea::int32($q * $delta);
		while ($sum != 0) {
			$e = $sum >> 2 & 3;
			for ($p = $n; $p > 0; $p--) {
				$z = $v[$p - 1];
				$mx = Xxtea::int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ Xxtea::int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
				$y = $v[$p] = Xxtea::int32($v[$p] - $mx);
			}
			$z = $v[$n];
			$mx = Xxtea::int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ Xxtea::int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
			$y = $v[0] = Xxtea::int32($v[0] - $mx);
			$sum = Xxtea::int32($sum - $delta);
		}
		return Xxtea::long2str($v, true);
	}

	private static function long2str($v, $w) 
	{
		$len = count($v);
		$s = array();
		for ($i = 0; $i < $len; $i++)
		{
			$s[$i] = pack("V", $v[$i]);
		}
		if ($w) {
			return substr(join('', $s), 0, $v[$len - 1]);
		}
		else {
			return join('', $s);
		}
	}

	private static function str2long($s, $w) 
	{
		$v = unpack("V*", $s. str_repeat("\0", (4 - strlen($s) % 4) & 3));
		$v = array_values($v);
		if ($w) {
			$v[count($v)] = strlen($s);
		}
		return $v;
	}

	private static function int32($n) 
	{
		while ($n >= 2147483648) $n -= 4294967296;
		while ($n <= -2147483649) $n += 4294967296;
		return (int)$n;
	}
}

?>