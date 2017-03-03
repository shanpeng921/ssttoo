<?php
namespace app\components;/**
 * 
 *
 */

class Tools{
	
	/**
	 * get user's ip 
	 * @return  string  user's ip
	 */
	public static function getClientIp()
	{
		
	
	}
	
	/**
	 * Addslashes chars.
	 */
	 public static function saddslashes($string) 
	 {
		if(is_array($string))
		{
			foreach($string as $key => $val)
			{
				$string[$key] = self::saddslashes($val);
			}
		} 
		else 
		{
			$string = addslashes($string);
		}
		return $string;
	}

	/**
	 * Filter some specical chars.
	 */
	public static function  shtmlSpecialchars($string)
	{
		if(is_array($string))
		{
			foreach($string as $key => $val)
			{
				$string[$key] = self::shtmlspecialchars($val);
			}
		} 
		else 
		{
			$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
				str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
		}
		return $string;
	}

	public static function getSafeValue($value)
	{
		return self::shtmlspecialchars(self::saddslashes($value));
	}

	// errno 0 无错误   1 key错误   2 无返回信息  3 缺少参数
	// result 返回数据
	public static function returnJson($errno , $data = array())
	{
		header("Content-type: text/html; charset=utf-8");
		echo json_encode(array('errno'=>$errno , 'result'=>$data));
		die;
	}

	// errno 0 无错误   1 key错误   2 无返回信息  3 缺少参数
	// result 返回数据
	public static function echoJson($errno , $data = array())
	{
		header("Content-type: text/html; charset=utf-8");
		echo json_encode(array('errno'=>$errno , 'result'=>$data));
		die;
	}



	/**
	 * 
	 * 验证ajax请求
	 * @return boolean
	 */
	public static function checkAjaxRequest()
	{
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
		{
			return true;
		}
		return false;
	}


	public static function objectToArray($obj)
	{
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
		foreach ($_arr as $key => $val)
		{
			$val = (is_array($val) || is_object($val)) ? self::objectToArray($val) : $val;
			$arr[$key] = $val;
		}
		return $arr;
	}
	
	public static function boolImage($bool) {
		return '<img src="'.Yii::app()->baseUrl . '/global/img/icons/' . ($bool ? 'yes': 'no'). '.gif"  />';
	}


	public static function excelColumu($i) {
		if($i < 91) {
			return chr($i);
		} else if($i >= 91 && $i <= 116) {
			return 'A'.chr($i-26);
		} else if($i>116) {
			return 'B'.chr($i-52);
		}
	}

	public static function getFirstLetter($str){     
		$fchar = ord($str{0});  
		if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($str{0});  
		$s1 = iconv("UTF-8","gb2312", $str);  
		$s2 = iconv("gb2312","UTF-8", $s1);  
		if($s2 == $str){$s = $s1;}
		else{$s = $str;}  
		$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;  
		if($asc >= -20319 and $asc <= -20284) return "A";  
		if($asc >= -20283 and $asc <= -19776) return "B";  
		if($asc >= -19775 and $asc <= -19219) return "C";  
		if($asc >= -19218 and $asc <= -18711) return "D";  
		if($asc >= -18710 and $asc <= -18527) return "E";  
		if($asc >= -18526 and $asc <= -18240) return "F";  
		if($asc >= -18239 and $asc <= -17923) return "G";  
		if($asc >= -17922 and $asc <= -17418) return "I";  
		if($asc >= -17417 and $asc <= -16475) return "J";  
		if($asc >= -16474 and $asc <= -16213) return "K";  
		if($asc >= -16212 and $asc <= -15641) return "L";  
		if($asc >= -15640 and $asc <= -15166) return "M";  
		if($asc >= -15165 and $asc <= -14923) return "N";  
		if($asc >= -14922 and $asc <= -14915) return "O";  
		if($asc >= -14914 and $asc <= -14631) return "P";  
		if($asc >= -14630 and $asc <= -14150) return "Q";  
		if($asc >= -14149 and $asc <= -14091) return "R";  
		if($asc >= -14090 and $asc <= -13319) return "S";  
		if($asc >= -13318 and $asc <= -12839) return "T";  
		if($asc >= -12838 and $asc <= -12557) return "W";  
		if($asc >= -12556 and $asc <= -11848) return "X";  
		if($asc >= -11847 and $asc <= -11056) return "Y";  
		if($asc >= -11055 and $asc <= -10247) return "Z";  
		return null;  
	}  
 
	public static function pinyin($zh) {  
	     $ret = "";  
	     $s1 = iconv("UTF-8","gb2312", $zh);  
	     $s2 = iconv("gb2312","UTF-8", $s1);  
	     if($s2 == $zh){$zh = $s1;}  
	     for($i = 0; $i < strlen($zh); $i++){  
	         $s1 = substr($zh,$i,1);  
	         $p = ord($s1);  
	         if($p > 160){  
	             $s2 = substr($zh,$i++,2);  
	             $ret .= self::getFirstLetter($s2);  
	         }else{  
	             $ret .= $s1;  
	         }  
	     }  
	     return $ret;  
	}

	public static function beforeSerialize($string) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = self::getSafeSerialize($val);
			}
		} else {
			$string = self::getSafeSerialize($string);
		}
		return $string;
	}


	public static function getSafeSerialize($v = 0) {
		if(empty($v)) {
			return false;
		}
		$v = (str_replace("\"","“",$v));
		$v = (str_replace("\'","‘",$v)); 
		$v = (str_replace("\\","",$v)); 
		return $v;
	}

	public static function dump($d) {
	    echo '<pre>';
		var_dump($d);
		echo '</pre>';
	}

	//产生随机字符
	public static function random($length, $numeric = 0) {
		PHP_VERSION < '4.2.0' ? mt_srand((double)microtime() * 1000000) : mt_srand();
		$seed = base_convert(md5(print_r($_SERVER, 1).microtime()), 16, $numeric ? 10 : 35);
		$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
		$hash = '';
		$max = strlen($seed) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $seed[mt_rand(0, $max)];
		}
		return $hash;
	}

	//判断字符串是否存在
	public static function strexists($haystack, $needle) {
		return !(strpos($haystack, $needle) === FALSE);
	}

	

	//检查邮箱是否有效
	public static function isEmail($email) {
		return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
	}

	public static function formatWorkTime($hour) {

		if($hour>0&&$hour<=2) {
			return 0.25;
		} else if($hour>2&&$hour<=4) {
			return 0.5;
		} else if($hour>4&&$hour<=6) {
			return 0.75;
		} else if($hour>6) {
			return 1;
		}
	}


	public static function _get_hours($begintime,$endtime)
	{
		$m = strtotime($endtime)-strtotime($begintime);
		$hour = round($m/3600,2);
		$h_arr = explode('.', $hour);
		$h = $h_arr[0];
		$m = round(($h_arr[1] * 6) / 10);
		return $h.'.'.$m;
	}



	public static function getMonthRange($date){
		$ret=array();
		$timestamp=strtotime($date);
		$mdays=date('t',$timestamp);
		$ret['sdate']=date('Y-m-1 00:00:00',$timestamp);
		$ret['edate']=date('Y-m-'.$mdays.' 23:59:59',$timestamp);
		return $ret;
	}


	public static function GetMonth($sign="1" ,$count=1)  
	{  
	    //得到系统的年月  
	    $tmp_date=date("Ym");  
	    //切割出年份  
	    $tmp_year=substr($tmp_date,0,4);  
	    //切割出月份  
	    $tmp_mon =substr($tmp_date,4,2);  
	    $tmp_nextmonth=mktime(0,0,0,$tmp_mon+$count,1,$tmp_year);  
	    $tmp_forwardmonth=mktime(0,0,0,$tmp_mon-$count,1,$tmp_year);  
	    if($sign==0){  
	        //得到当前月的下一个月   
	        return $fm_next_month=date("Y-m",$tmp_nextmonth);          
	    }else{  
	        //得到当前月的上一个月   
	        return $fm_forward_month=date("Y-m",$tmp_forwardmonth);           
	    }  
	}

	
	public static function _get_hours2($begintime,$endtime)
	{
		$m = strtotime($endtime)-strtotime($begintime);
		$hour = round($m/3600,2);
		return $hour;
	}
	

	//生成表单 从现在开始 前推12个月
	public static function getMdsOptions($n)
	{
		$arr = array();
		for ($i = 0; $i < $n; $i++) { 
			$arr[$i]['md'] = Tools::GetMonth(1,$i);
		}
		return $arr;
	}

	/**
	 * [getMailBody description]
	 * @param  [type] $text  [description]
	 * @param  [type] $array [description]
	 * @return [type]        [description]
	 */
	public static function getMailBody($text,$array)
	{
		if(!empty($array)) {
			foreach ($array as $key => $value) {
				$text = str_replace("{$key}",$value,$text);
			}
			return $text;
		}
		return null;
	}

	public static function curlFileGetContents($durl)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $durl);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		//curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
		//curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$r = curl_exec($ch);
		curl_close($ch);
		return $r;
	}

	public static function getRandStr($len)
	{
		$chars = array(
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
			"l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
			"w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
			"H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
			"S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
			"3", "4", "5", "6", "7", "8", "9"
		);
		$charsLen = count($chars) - 1;
		shuffle($chars);
		$output = "";
		for ($i=0; $i<$len; $i++)
		{
			$output .= $chars[mt_rand(0, $charsLen)];
		}
		return $output;
	}

	public static function redirect($url)
	{
		if(header("location: $url")) {

		} else {
			echo "<script language=\"javascript\">";
			echo "location.href=\"$url\"";
			echo "</script>";
		}
	}

	public static function getIP() /*获取客户端IP*/
	{
		if (@$_SERVER["HTTP_X_FORWARDED_FOR"])
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		else if (@$_SERVER["HTTP_CLIENT_IP"])
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		else if (@$_SERVER["REMOTE_ADDR"])
			$ip = $_SERVER["REMOTE_ADDR"];
		else if (@getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (@getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if (@getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");
		else
			$ip = "Unknown";
		return $ip;
	}

}