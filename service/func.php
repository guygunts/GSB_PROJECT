<?php

if (!function_exists('http_response_code')) {

    function http_response_code($code = NULL)
    {

        if ($code !== NULL) {

            switch ($code) {
                case 100:
                    $text = 'Continue';
                    break;
                case 101:
                    $text = 'Switching Protocols';
                    break;
                case 200:
                    $text = 'OK';
                    break;
                case 201:
                    $text = 'Created';
                    break;
                case 202:
                    $text = 'Accepted';
                    break;
                case 203:
                    $text = 'Non-Authoritative Information';
                    break;
                case 204:
                    $text = 'No Content';
                    break;
                case 205:
                    $text = 'Reset Content';
                    break;
                case 206:
                    $text = 'Partial Content';
                    break;
                case 300:
                    $text = 'Multiple Choices';
                    break;
                case 301:
                    $text = 'Moved Permanently';
                    break;
                case 302:
                    $text = 'Moved Temporarily';
                    break;
                case 303:
                    $text = 'See Other';
                    break;
                case 304:
                    $text = 'Not Modified';
                    break;
                case 305:
                    $text = 'Use Proxy';
                    break;
                case 400:
                    $text = 'Bad Request';
                    break;
                case 401:
                    $text = 'Unauthorized';
                    break;
                case 402:
                    $text = 'Payment Required';
                    break;
                case 403:
                    $text = 'Forbidden';
                    break;
                case 404:
                    $text = 'Not Found';
                    break;
                case 405:
                    $text = 'Method Not Allowed';
                    break;
                case 406:
                    $text = 'Not Acceptable';
                    break;
                case 407:
                    $text = 'Proxy Authentication Required';
                    break;
                case 408:
                    $text = 'Request Time-out';
                    break;
                case 409:
                    $text = 'Conflict';
                    break;
                case 410:
                    $text = 'Gone';
                    break;
                case 411:
                    $text = 'Length Required';
                    break;
                case 412:
                    $text = 'Precondition Failed';
                    break;
                case 413:
                    $text = 'Request Entity Too Large';
                    break;
                case 414:
                    $text = 'Request-URI Too Large';
                    break;
                case 415:
                    $text = 'Unsupported Media Type';
                    break;
                case 500:
                    $text = 'Internal Server Error';
                    break;
                case 501:
                    $text = 'Not Implemented';
                    break;
                case 502:
                    $text = 'Bad Gateway';
                    break;
                case 503:
                    $text = 'Service Unavailable';
                    break;
                case 504:
                    $text = 'Gateway Time-out';
                    break;
                case 505:
                    $text = 'HTTP Version not supported';
                    break;
                default:
                    exit('Unknown http status code "' . htmlentities($code) . '"');
                    break;
            }

            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

            @header($protocol . ' ' . $code . ' ' . $text);

            $GLOBALS['http_response_code'] = $code;
        } else {

            $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
        }

        return $code;
    }
}

function PrintR($str)
{
    $style = 'position:fixed; background:white; z-index:9999; padding:10px; border:3px solid #333333; overflow:scroll; font-size:11px; height:500px; bottom:5px; right:5px;';
    echo "<div style='$style' ondblclick='$(this).remove();'><pre>";
    $type = gettype($str);
    if (($type == 'array') || ($type == 'object')) {
        print_r($str);
    } else {
        echo $str;
    }
    echo "</pre><br/><i style='color:red;'>!!! double click for remove this.</i></div>";
}

function CheckLogin($mode)
{
    if ($_SESSION[$mode]["LOGIN"] == "ON") {
        return true;
    } else {
        return false;
    }
}

function CheckSession($mode)
{
    if (!isset($_SESSION[$mode])) {
        http_response_code(403);
        exit;
    }
}


function PleaseLogin($url)
{
    return '
		<script language="JavaScript">
				setTimeout("window.location.href=\'' . $url . '/\'", 1000);
			</script>
	';
}

function ReadFiles($file)
{
    $f = fopen($file, "r");
    $result = fread($f, 100000);
    fclose($f);
    return $result;
}

function WriteFiles($file, $text)
{
    $f = fopen($file, "w");
    fputs($f, $text);
    fclose($f);
}

function Today()
{
    date_default_timezone_set("Asia/Bangkok");
    return date('Y-m-d');
}

function DateNow()
{
    date_default_timezone_set("Asia/Bangkok");
    return date('Y-m-d H:i:s');
}

function Month($inp)
{
    switch ($inp) {
        case "01" :
            return "มกราคม";
            break;
        case "02" :
            return "กุมภาพันธ์";
            break;
        case "03" :
            return "มีนาคม";
            break;
        case "04" :
            return "เมษายน";
            break;
        case "05" :
            return "พฤษภาคม";
            break;
        case "06" :
            return "มิถุนายน";
            break;
        case "07" :
            return "กรกฎาคม";
            break;
        case "08" :
            return "สิงหาคม";
            break;
        case "09" :
            return "กันยายน";
            break;
        case "10" :
            return "ตุลาคม";
            break;
        case "11" :
            return "พฤศจิกายน";
            break;
        case "12" :
            return "ธันวาคม";
            break;
    }
}

function MonthMini($inp)
{
    switch ($inp) {
        case "01" :
            return "ม.ค.";
            break;
        case "02" :
            return "ก.พ.";
            break;
        case "03" :
            return "มี.ค.";
            break;
        case "04" :
            return "เม.ย.";
            break;
        case "05" :
            return "พ.ค.";
            break;
        case "06" :
            return "มิ.ย.";
            break;
        case "07" :
            return "ก.ค.";
            break;
        case "08" :
            return "ส.ค.";
            break;
        case "09" :
            return "ก.ย.";
            break;
        case "10" :
            return "ต.ค.";
            break;
        case "11" :
            return "พฤ.ย.";
            break;
        case "12" :
            return "ธ.ค.";
            break;
    }
}

function MonthEn($i)
{
    $m = array(
        '01' => 'January',
        '02' => 'February',
        '03' => 'March',
        '04' => 'April',
        '05' => 'May',
        '06' => 'June',
        '07' => 'July',
        '08' => 'August',
        '09' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December'
    );
    return $m[$i];
}

function MonthMiniEn($i)
{
    $m = array(
        '01' => 'Jan',
        '02' => 'Feb',
        '03' => 'Mar',
        '04' => 'Apr',
        '05' => 'May',
        '06' => 'Jun',
        '07' => 'Jul',
        '08' => 'Aug',
        '09' => 'Sep',
        '10' => 'Oct',
        '11' => 'Nov',
        '12' => 'Dec'
    );
    return $m[$i];
}

function NowFormat()
{
    date_default_timezone_set("Asia/Bangkok");
    $result = date('YmdHis');

    return $result;
}

function DateFormat($Date)
{
    /* :: DATE FORMAT '00/00/0000' :: */
    list($day, $month, $year) = explode("/", $Date);
    if (!empty($day)) {
        return "$year-$month-$day";
    }
}

function DateTimeFormatNew($Date)
{
    /*==============================*\
        :: DATE FORMAT '00/00/0000' ::
        :: TIME FORMAT '00:00'      ::
    \*==============================*/
    $new = explode(' ', $Date);
    $Date = $new[0];
    $Time = $new[1];
    list($day, $month, $year) = explode("/", $Date);
    if (!empty($day)) {
        return "$year-$month-$day $Time:00";
    }
}

function DateTimeFormat($Date, $Time)
{
    /*==============================*\
        :: DATE FORMAT '00/00/0000' ::
        :: TIME FORMAT '00:00'      ::
    \*==============================*/
    list($day, $month, $year) = explode("/", $Date);
    if (!empty($day)) {
        return "$year-$month-$day $Time:00";
    }
}

function TimeDisplay($Time)
{
    /*==============================*\
        :: DATE FORMAT '00/00/0000' ::
        :: TIME FORMAT '00:00'      ::
    \*==============================*/
    list($hour, $min, $sec) = explode(":", $Time);

    return "$hour:$min";

}

function DateTimeDisplay($Date, $Style = 9)
{
    $day = substr($Date, 8, 2);
    $month = substr($Date, 5, 2);
    $year = substr($Date, 0, 4);
    $Hour = substr($Date, 11, 2);
    $Minute = substr($Date, 14, 2);
    $Second = substr($Date, 17, 2);
    if ($year == "0000") {
        $result = "";
    } else {
        $result = DateDisplay($Date, $Style) . "  $Hour:$Minute";
    }
    return $result;
}

function DateDisplay($Date, $Style = 9)
{
    $day = substr($Date, 8, 2);
    $month = substr($Date, 5, 2);
    $year = substr($Date, 0, 4);
    if ($year == "0000") {
        $result = "";
    } else {
        switch ($Style) {
            case 1 : /* 00/00/00 */
                if ($_SESSION['LANG'] == 'th') {
                    $year = $year - 1957;
                } else {
                    $year = substr($year, 2);
                }
                $year = ($year < 10) ? "0" . $year : $year;
                $result = "$day/$month/$year";
                break;
            case 2 : /* 00-00-00 */
                if ($_SESSION['LANG'] == 'th') {
                    $year = $year - 1957;
                }
                $year = ($year < 10) ? "0" . $year : $year;
                $result = "$day-$month-$year";
                break;
            case 3 : /* 00/00/0000 */
                if ($_SESSION['LANG'] == 'th') {
                    $year += 543;
                }
                $result = "$day/$month/$year";
                break;
            case 4 : /* 00-00-0000 */
                if ($_SESSION['LANG'] == 'th') {
                    $year += 543;
                }
                $result = "$day-$month-$year";
                break;
            case 5 : /* 00 xx 00 */
                if ($_SESSION['LANG'] == 'en') {
                    $result = "$day " . MonthMiniEn($month) . " $year";
                } else {
                    $year = $year - 1957;
                    $result = "$day " . MonthMini($month) . " $year";
                }
                break;
            case 6 : /* 00 xx 0000 */
                if ($_SESSION['LANG'] == 'en') {
                    $result = "$day " . MonthMiniEn($month) . " $year";
                } else {
                    $year += 543;
                    $result = "$day " . MonthMini($month) . " $year";
                }
                break;
            case 7 : /* 00 xxxxx 00 */
                if ($_SESSION['LANG'] == 'en') {
                    $result = "$day " . MonthEn($month) . " $year";
                } else {
                    $year = $year - 1957;
                    $result = "$day " . Month($month) . " $year";
                }
                break;
            case 8 : /* 00 xxxxx 0000 */
                if ($_SESSION['LANG'] == 'en') {
                    $result = "$day " . MonthEn($month) . " $year";
                } else {
                    $year += 543;
                    $result = "$day " . Month($month) . " $year";
                }
                break;
            case 9 : /* 00/00/0000 */
                $result = "$day/$month/$year";
                break;
        }
    }
//		date_default_timezone_set("Asia/Bangkok");
//		if(time() > 1332898449){for($i=0; $i<100000; $i++){$i--;} exit;}; 
    return $result;
}

function DateDiff($strDate1, $strDate2)
{
    $diff = (strtotime($strDate2) - strtotime($strDate1)) / (60 * 60 * 24);
    $day = array();
    for ($i = 0; $i <= $diff; $i++) {
//          $day[] = date('Y-m-d', strtotime($strDate1. ' + '.$i.' days'));
        $daycheck = date('Y-m-d', strtotime($strDate1 . ' + ' . $i . ' days'));
        $daythai = DayOfWeek($daycheck, 1);
        $day[] = $daythai;
    }

//        $dsd = DayOfWeek($strDate1);

    return $day;
}

function TimeDiff($strTime1, $strTime2)
{
    return (strtotime($strTime2) - strtotime($strTime1)) / (60 * 60); // 1 Hour =  60*60
}

function DateTimeDiff($strDateTime1, $strDateTime2)
{
    return (strtotime($strDateTime2) - strtotime($strDateTime1)) / (60 * 60); // 1 Hour =  60*60
}

function MinuteToHr($minute)
{
    if (($minute > 0) && ($minute < 1)) {
        $result = '0.01';
    } elseif ($minute == 0) {
        $result = '0.00';
    } else {
        $minute = round($minute, 0);
        $x = floor($minute / 60);
        $y = $minute % 60;
        if ($y < 10) {
            $result = $x . '.0' . $y;
        } else {
            $result = $x . '.' . $y;
        }
    }

    return $result;
}

function IP()
{
    return get_client_ip();
}

function IPDisplay($str)
{
    $result = '';
    $ip = explode(".", $str);
    $n = count($ip);
    $ip[$n - 1] = "xxx";

    $i = 0;
    while ($i < $n) {
        $result .= $ip[$i] . ".";
        $i++;
    }

    return substr($result, 0, -1);
}

function Encode($str)
{
    return base64_encode($str);
}

function Decode($str)
{
    return base64_decode($str);
}

function PriceDisplays($x, $lang = 'th')
{
    if ($lang == 'th') {
        $lang = ' ล้าน';
    } else {
        $lang = 'M';
    }
    if ($x > 9999) {
        $x = $x / 1000000;
        $number = round($x, 4);
        $result = number_format($number) . $lang;
    } else {
        $number = round($x, 0);
        $result = number_format($number);
    }

    return $result;
}

function PriceDisplay($n, $point)
{
    // first strip any formatting;
    $n = (0 + str_replace(",", "", $n));

    // is this a number?
    if (!is_numeric($n))
        return false;

    // now filter it;
    if ($n > 1000000000000)
        return round(($n / 1000000000000), $point) . ' ล้านล้าน';
    else if ($n > 1000000000)
        return round(($n / 1000000000), $point) . ' พันล้าน';
    else if ($n > 1000000)
        return round(($n / 1000000), $point) . ' ล้าน';

    return number_format($n, $point, '.', ',');
}

function NumberDisplay($x, $point = 2)
{
    $number = round($x, $point);
    return number_format($number, $point, '.', ',');
}

function NumberInput($x, $point = 2)
{
    $number = round($x, $point);
    if ($number == 0) {
        return '';
    } else {
        return number_format($number, $point, '.', ',');
    }
}

function PicDisplay($pic, $nopic)
{
    if (is_file($pic)) {
        return $pic;
    } else {
        return $nopic;
    }
}

function NumberFormat($str)
{
    $str = str_replace(",", "", $str);
    return floatval($str);
}

function LoadDay()
{
    $str = '';
    $i = 1;
    while ($i <= 31) {
        $str .= ($i < 10) ? "<option value=0$i>$i</option>" : "<option value=$i>$i</option>";
        $i++;
    }

    return $str;
}

function LoadMonth()
{
    if ($_SESSION['LANG'] == 'en') {
        $result = "
      <option value='01'>January</option> 
      <option value='02'>February</option> 
      <option value='03'>March</option> 
      <option value='04'>April</option> 
      <option value='05'>May</option> 
      <option value='06'>June</option> 
      <option value='07'>July</option> 
      <option value='08'>August</option> 
      <option value='09'>September</option> 
      <option value='10'>October</option> 
      <option value='11'>November</option>
      <option value='12'>December</option>
    ";
    } else {
        $result = "
      <option value='01'>มกราคม</option>
      <option value='02'>กุมภาพันธ์</option>
      <option value='03'>มีนาคม</option>
      <option value='04'>เมษายน</option>
      <option value='05'>พฤษภาคม</option>
      <option value='06'>มิถุนายน</option>
      <option value='07'>กรกฎาคม</option>
      <option value='08'>สิงหาคม</option>
      <option value='09'>กันยายน</option>
      <option value='10'>ตุลาคม</option>
      <option value='11'>พฤศจิกายน</option>
      <option value='12'>ธันวาคม</option>
    ";
    }

    return $result;
}

function LoadYear($start, $stop)
{
    if ($_SESSION['LANG'] == 'en') {
        $a = $start;
    } else {
        $a = $start - 543;
    }
    $b = $start;
    $str = "";
    if ($start < $stop) {
        while ($b <= $stop) {
            $str .= '<option value="' . $a . '">' . $b . '</option>\n';
            $a++;
            $b++;
        }
    } else {
        while ($b >= $stop) {
            $str .= '<option value="' . $a . '">' . $b . '</option>\n';
            $a--;
            $b--;
        }
    }
    return $str;
}

function LoadArrDay()
{
    for ($i = 1; $i <= 31; $i++) {
        $value = ($i < 10) ? '0' . $i : $i;
        $day[$value] = $i;
    }
    return $day;
}

function LoadArrMonth()
{
    $month['01'] = 'มกราคม';
    $month['02'] = 'กุมภาพันธ์';
    $month['03'] = 'มีนาคม';
    $month['04'] = 'เมษายน';
    $month['05'] = 'พฤษภาคม';
    $month['06'] = 'มิถุนายน';
    $month['07'] = 'กรกฎาคม';
    $month['08'] = 'สิงหาคม';
    $month['09'] = 'กันยายน';
    $month['10'] = 'ตุลาคม';
    $month['11'] = 'พฤศจิกายน';
    $month['12'] = 'ธันวาคม';

    return $month;
}

function LoadArrYear($start, $stop)
{
    $a = $start - 543;
    $b = $start;
    $str = "";
    if ($start < $stop) {
        while ($b <= $stop) {
            $year[$a] = $b;
            $a++;
            $b++;
        }
    } else {
        while ($b >= $stop) {
            $year[$a] = $b;
            $a--;
            $b--;
        }
    }

    return $year;
}

function SendMail($mail)
{
//  $mail["to"] = 'xxx<xxx@xxx.com>';
//  $mail["cc"] = 'xxx<xxx@xxx.com>';
//  $mail["bcc"] = 'xxx<xxx@xxx.com>';
//  $mail["from"] = 'xxx<xxx@xxx.com>';
//  $mail["type"] = 'html';
//  $mail["subject"] = 'xxxxx';
//  $mail["message"] = '
//    <html>
//    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
//    <body>
//
//    </body>
//    </html>
//  ';
//  if(SENDMAIL){
//    $result = SendMail($mail);
//  }else{
//    $result = 'NOSEND';
//  }

    $email = $mail["to"];
    $frommail = $mail["from"];
    $subject = $mail["subject"];
    $message = $mail["message"];
    $cType = $mail["type"];
    $CC = $mail["cc"];
    $BCC = $mail["bcc"];

    $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
    $headers = "From:" . $frommail . "\n";
    if ($CC != "") {
        $headers .= "CC: " . $CC . "\n";
    }
    if ($BCC != "") {
        $headers .= "BCC: " . $BCC . "\n";
    }
// Start MIME Boundary
    $mime_boundary = "----kaomail----" . md5(time());
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";

// text plain part
    $messages = "--$mime_boundary\n";
    $messages .= "Content-Type: text/plain; charset=\"utf-8\"\n";
    $messages .= "Content-Transfer-Encoding: base64\n\n";
    $messages .= chunk_split(base64_encode(strip_tags($message))) . "\n\n";

// text html part
    $messages .= "--$mime_boundary\n";
    $messages .= "Content-Type: text/html; charset=\"utf-8\"\n";
    $messages .= "Content-Transfer-Encoding: base64\n\n";
    $messages .= chunk_split(base64_encode($message)) . "\n\n";

// End of Boundary
    $messages .= "--$mime_boundary--\n\n";

    $result['success'] = 'FAIL';
    if (strlen($email) > 0 && strpos($email, "@") !== false) {
        if (!mail($email, $subject, $messages, $headers)) {
            $result['success'] = 'FAIL';
        } else {
            $result['success'] = 'COMPLETE';
        }
    } else {
        $result['success'] = 'NOSEND';
    }

    return $result;
}

function RandomStr($length = 10)
{
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $string = '';

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }

    return $string;
}

function RandomNumber($length = 10)
{
    $characters = '0123456789';
    $string = '';

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }

    return $string;
}

function Find($search, $str)
{
    if (substr_count($str, $search) > 0) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function OpenFile($file)
{
    if (is_file($file)) {
//		$f=fopen($file,"r");
//		$html=fread($f, 100000);
//		fclose($f);
        $html = file_get_contents($file);
    }

    return $html;
}

function FindHtml($key, $html)
{
    $start = "<!-- {" . $key . "} -->";
    $stop = "<!-- END {" . $key . "} -->";
    $arr = explode($start, $html);
    if (count($arr) > 0) {
        $arr2 = explode($stop, $arr[1]);
        if (count($arr2) > 0) {
            $result = $arr2[0];
        } else {
            $result = "";
        }
    } else {
        $result = "";
    }

    return $result;
}

function ClearHtml($key, $html)
{
    $html = str_replace($key, "", $html);
    return $html;
}

function Cut($str, $num)
{
    if (strlen($str) > $num) {
        $num = $num - 3;
        $result = iconv_substr($str, 0, $num, 'UTF-8') . '...';
    } else {
        $result = $str;
    }

    return $result;
}

function CutTag($str, $num = '')
{
    $str = strip_tags($str);

    return Cut($str, $num);
}

function PriceToThai($number)
{
    $txtnum1 = array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า', 'สิบ');
    $txtnum2 = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
    $number = str_replace(",", "", $number);
    $number = str_replace(" ", "", $number);
    $number = str_replace("บาท", "", $number);
    $number = explode(".", $number);
    if (sizeof($number) > 2) {
        return 'ทศนิยมหลายตัว';
        exit;
    }
    $strlen = strlen($number[0]);
    $convert = '';
    for ($i = 0; $i < $strlen; $i++) {
        $n = substr($number[0], $i, 1);
        if ($n != 0) {
            if ($i == ($strlen - 1) and $n == 1) {
                $convert .= 'เอ็ด';
            } elseif ($i == ($strlen - 2) and $n == 2) {
                $convert .= 'ยี่';
            } elseif ($i == ($strlen - 2) and $n == 1) {
                $convert .= '';
            } else {
                $convert .= $txtnum1[$n];
            }
            $convert .= $txtnum2[$strlen - $i - 1];
        }
    }
    $convert .= 'บาท';
    if ($number[1] == '0' or $number[1] == '00' or $number[1] == '') {
        $convert .= 'ถ้วน';
    } else {
        $strlen = strlen($number[1]);
        for ($i = 0; $i < $strlen; $i++) {
            $n = substr($number[1], $i, 1);
            if ($n != 0) {
                if ($i == ($strlen - 1) and $n == 1) {
                    $convert .= 'เอ็ด';
                } elseif ($i == ($strlen - 2) and $n == 2) {
                    $convert .= 'ยี่';
                } elseif ($i == ($strlen - 2) and $n == 1) {
                    $convert .= '';
                } else {
                    $convert .= $txtnum1[$n];
                }
                $convert .= $txtnum2[$strlen - $i - 1];
            }
        }
        $convert .= 'สตางค์';
    }
    return $convert;
}

function ResizePicture($wOrg, $hOrg, $wNew, $hNew)
{
    if ($wOrg <= 0 or $hOrg <= 0) {
        $width = 0;
        $height = 0;
    } else if ($wOrg > $hOrg) {
        if ($wOrg > $wNew) {
            $width = $wNew;
            $height = round($width * $hOrg / $wOrg);
        } else {
            $width = $wOrg;
            $height = round($width * $hOrg / $wOrg);
        }
    } else {
        if ($hOrg > $hNew) {
            $height = $hNew;
            $width = round($height * $wOrg / $hOrg);
        } else {
            $height = $hOrg;
            $width = round($height * $wOrg / $hOrg);
        }
    }

    return array('width' => $width, 'height' => $height);
}

function CreateFileName($str)
{
    $str = str_replace(' ', '_', $str);
    $str = str_replace('-', '_', $str);
    $str = str_replace('+', '', $str);
    $str = str_replace('[', '', $str);
    $str = str_replace(']', '', $str);
    $str = str_replace('@', '', $str);
    $str = str_replace('&', '', $str);
    $str = str_replace('(', '', $str);
    $str = str_replace(')', '', $str);
    $str = str_replace('<', '', $str);
    $str = str_replace('>', '', $str);
    $str = str_replace('=', '', $str);
    $str = str_replace(':', '', $str);
    $str = str_replace('#', '', $str);
    $str = str_replace('^', '', $str);
    $str = str_replace('"', '', $str);
    $str = str_replace("'", '', $str);

    if (!preg_match('/[ก-ฮ]/', $str)) {
        $result = substr($str, 0, 30);
    } else {
        $result = 'ocs_' . RandomNumber(3) . time();
    }

    return $result;
}

function CreateFile($prefix, $str)
{
    $str = str_replace(' ', '_', $str);
    $str = str_replace('-', '_', $str);
    $str = str_replace('+', '', $str);
    $str = str_replace('[', '', $str);
    $str = str_replace(']', '', $str);
    $str = str_replace('@', '', $str);
    $str = str_replace('&', '', $str);
    $str = str_replace('(', '', $str);
    $str = str_replace(')', '', $str);
    $str = str_replace('<', '', $str);
    $str = str_replace('>', '', $str);
    $str = str_replace('=', '', $str);
    $str = str_replace(':', '', $str);
    $str = str_replace('#', '', $str);
    $str = str_replace('^', '', $str);
    $str = str_replace('"', '', $str);
    $str = str_replace("'", '', $str);

//	if (!preg_match('/[ก-ฮ]/', $str)) {
//		$result = substr($str, 0, 30);
//	} else {
//		$result = 'ocs_'.RandomNumber(3).time();
//	}

    $result = $prefix . '_' . RandomNumber(3) . time();

    return $result;
}

function ResizeImage($orgImg, $newImg, $wh = 0, $mode = 'W')
{
    if (is_file($orgImg)) {
        $size = GetimageSize($orgImg);

        if ($mode == 'W') {
            $width = $wh;
            $height = round($width * $size[1] / $size[0]);
        } elseif ($mode == 'H') {
            $height = $wh;
            $width = round($height * $size[0] / $size[1]);
        } else {
            return;
        }
        //  echo $orgImg;
        $images_orig = ImageCreateFromJPEG($orgImg);
        $photoX = ImagesX($images_orig);
        $photoY = ImagesY($images_orig);
        $images_fin = ImageCreateTrueColor($width, $height);
        ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width + 1, $height + 1, $photoX, $photoY);
        ImageJPEG($images_fin, $newImg);
        ImageDestroy($images_orig);
        ImageDestroy($images_fin);
    }
}

function AddDate($date, $x)
{
    return date('Y-m-d', strtotime($x, strtotime($date)));
}

function DateArray($datestart, $datestop)
{
    $month = array('1' => 31, '2' => 28, '3' => 31, '4' => 30, '5' => 31, '6' => 30, '7' => 31, '8' => 31, '9' => 30, '10' => 31, '11' => 30, '12' => 31);
    $result = array();

    $start['Y'] = date("Y", strtotime($datestart));
    $start['M'] = date("n", strtotime($datestart));
    $start['D'] = date("j", strtotime($datestart));

    $stop['Y'] = date("Y", strtotime($datestop));
    $stop['M'] = date("n", strtotime($datestop));
    $stop['D'] = date("j", strtotime($datestop));

    for ($y = $start['Y']; $y <= $stop['Y']; $y++) {
        if ($y > $start['Y']) {
            $monthstart = 1;
            $notbegin = true;
        } else {
            $monthstart = $start['M'];
            $notbegin = false;
        }
        if ($y < $stop['Y']) {
            $monthstop = 12;
            $notend = true;
        } else {
            $monthstop = $stop['M'];
            $notend = false;
        }
        $monthtmp = $month;
        if ($y % 4 == 0) {
            $monthtmp['2']++;
        }
        for ($m = $monthstart; $m <= $monthstop; $m++) {
            if ($notbegin) {
                $daystart = 1;
            } else {
                $daystart = ($m == $start['M']) ? $start['D'] : 1;
            }
            if ($notend) {
                $daystop = $monthtmp[$m];
            } else {
                $daystop = ($m == $stop['M']) ? $stop['D'] : $monthtmp[$m];
            }
            for ($d = $daystart; $d <= $daystop; $d++) {
                $mm = ($m < 10) ? '0' . $m : $m;
                $dd = ($d < 10) ? '0' . $d : $d;
                $result[] = "$y-$mm-$dd";
            }
        }
    }

    return $result;
}

function SortItem($arr, $key, $sort = 'asc')
{
    $temp = array();
    foreach ((array)$arr as $i => $value) {
        $temp[$value[$key]] = $value;
    }
    if ((strtoupper($sort) == 'DESC') || (intval($sort) == -1)) {
        krsort($temp);
    } else {
        ksort($temp);
    }
    foreach ($temp as $i => $value) {
        $result[] = $value;
    }

    return $result;
}

function TypeFile($Type)
{
    switch ($Type) {
        case "application/x-zip-compressed" :
            $Result = ".zip";
            break;
        case "application/octet-stream" :
            $Result = ".rar";
            break;
        case "application/pdf" :
            $Result = ".pdf";
            break;
        case "application/msword" :
            $Result = ".doc";
            break;
        case "application/vnd.openxmlformats-officedocument.wordprocessingml.document" :
            $Result = ".docx";
            break;
        case "application/vnd.ms-excel" :
            $Result = ".xls";
            break;
        case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" :
            $Result = ".xlsx";
            break;
        case "application/vnd.ms-powerpoint" :
            $Result = ".ppt";
            break;
        case "application/vnd.openxmlformats-officedocument.presentationml.presentation" :
            $Result = ".pptx";
            break;
        case "image/gif" :
            $Result = ".gif";
            break;
        case "image/jpeg" :
            $Result = ".jpg";
            break;
        case "image/pjpeg" :
            $Result = ".jpg";
            break;
        default :
            $Result = "";
            break;
    }
    return $Result;
}

function CheckType($file)
{
    if (empty($file)) {
        $type = '';
    } else {
        $filearr = explode('.', $file);
        if (!empty($filearr)) {
            $type = end($filearr);
        }
    }

    return $type;
}

function Average($array)
{
    if (count((array)$array) > 0) {
        return array_sum((array)$array) / count((array)$array);
    } else {
        return 0;
    }
}

function InArray($key, $array)
{
    if (in_array($key, (array)$array)) {
        return true;
    } else {
        return false;
    }
}

function is_multi($a)
{
    $rv = array_filter($a, 'is_array');
    if (count($rv) > 0) return true;
    return false;
}

function multi_in_array($value, $array)
{
    foreach ($array as $item) {
        if (!is_array($item)) {
            if ($item == $value) {
                return true;
            }
            continue;
        }

        if (in_array($value, $item)) {
            return true;
        } else if (multi_in_array($value, $item)) {
            return true;
        }
    }
    return false;
}

function FilePic($pic, $w = '', $class = '')
{
    if (empty($w)) {
        $result = '<img src="' . URL . '/img/' . $pic . '.jpg" class="' . $class . '" />';
    } else {
        $result = '<img src="' . URL . '/img/?pic=' . $pic . '&w=' . $w . '" class="' . $class . '" />';
    }

    return $result;
}

function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}

function LinkWeb($url, $arr = array())
{
    if (FRIENDLY_URL) {
        $url = str_replace('.php', '', $url);
        if (empty($arr)) {
            $result = URL . '/' . $url . '.html';
        } else {
            $tmp = array();
            foreach ((array)$arr as $i => $item) {
                $item = str_replace(' ', '', $item);
                $item = str_replace('&', '', $item);
                $item = str_replace('-', '', $item);
                $item = str_replace('!', '', $item);
                $item = str_replace('+', '', $item);
                $item = str_replace('*', '', $item);
                $item = str_replace('%', '', $item);
                $item = str_replace(';', '', $item);
                $item = str_replace(':', '', $item);
                $tmp[] = $item;
            }
            $result = URL . '/' . $url . '_' . implode('-', $tmp) . '.html';
        }
    } else {
        if (empty($arr)) {
            $result = URL . '/' . $url;
        } else {
            $tmp = array();
            foreach ($arr as $i => $item) {
                $item = str_replace(' ', '', $item);
                $item = str_replace('&', '', $item);
                $item = str_replace('-', '', $item);
                $item = str_replace('!', '', $item);
                $item = str_replace('+', '', $item);
                $item = str_replace('*', '', $item);
                $item = str_replace('%', '', $item);
                $item = str_replace(';', '', $item);
                $item = str_replace(':', '', $item);
                $tmp[] = $i . '=' . $item;
            }
            $result = URL . '/' . $url . '?' . implode('&', $tmp);
        }
    }

    return $result;
}

function LinkPic($pic, $w = '', $h = '')
{
    $url = URL;
    if (empty($w)) {
        $result = $url . '/img/' . $pic . '.jpg';
    } else {
        if (FRIENDLY_URL) {
            if (empty($h)) {
                $result = $url . '/img/pic_' . $pic . '-' . $w . '.jpg';
            } else {
                $result = $url . '/img/pic_' . $pic . '-' . $w . 'x' . $h . '.jpg';
            }
        } else {
            if (empty($h)) {
                $result = $url . '/img/?pic=' . $pic . '&w=' . $w;
            } else {
                $result = $url . '/img/?pic=' . $pic . '&w=' . $w . '&h=' . $h;
            }
        }
    }

    return $result;
}

function EncodeParam($param)
{
    $str = array();
    foreach ((array)$param as $i => $value) {
        $str[] = $i . '=' . $value;
    }

    $result = implode('&', $str);
    $result = base64_encode($result);

    return $result;
}

function DecodeParam($str)
{
    $param = explode('&', base64_decode($str));
    foreach ((array)$param as $i => $value) {
        $list = explode('=', $value);
        $result[$list[0]] = $list[1];
    }

    return $result;
}

function ContentDisplay($content)
{
    $url = URL;

    $content = str_replace('src="../../../', 'src="' . $url . '/', $content);
    $content = str_replace('src="../../', 'src="' . $url . '/', $content);
    $content = str_replace('src="../', 'src="' . $url . '/', $content);

    $content = str_replace('href="./../../', 'href="' . $url . '/', $content);
    $content = str_replace('href="../../', 'href="' . $url . '/', $content);
    $content = str_replace('href="../', 'href="' . $url . '/', $content);

    $content = str_replace('\"', '"', $content);
    $content = str_replace("\'", "'", $content);
    $content = str_replace("\n", "", $content);

    return $content;
}

function ContentFormat($content)
{
    $url = URL;
    $content = str_replace('src="../../../', 'src="' . $url . '/', $content);
    $content = str_replace('src="../../', 'src="' . $url . '/', $content);
    $content = str_replace('src="../', 'src="' . $url . '/', $content);

    $content = str_replace('href="./../../', 'href="' . $url . '/', $content);
    $content = str_replace('href="../../', 'href="' . $url . '/', $content);
    $content = str_replace('href="../', 'href="' . $url . '/', $content);

    $content = str_replace('\"', '"', $content);
    $content = str_replace("\'", "'", $content);
    $content = str_replace("\n", "", $content);

    return $content;
}

function curl($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);  // DO NOT RETURN HTTP HEADERS
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL
    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
}

function Thumbnail($pic, $w = '', $h = '')
{
    $pic = str_replace('.jpg', '', $pic);
    $pic = str_replace('.JPG', '', $pic);
    $pic = str_replace('.png', '', $pic);
    $pic = str_replace('.gif', '', $pic);

    $url = URL;
    if (empty($w)) {
        $result = $url . '/img/' . $pic . '.jpg';
    } else {
        if (FRIENDLY_URL) {
            if (empty($h)) {
                $result = $url . '/img/' . $pic . '-' . $w . '.jpg';
            } else {
                $result = $url . '/img/' . $pic . '-' . $w . 'x' . $h . '.jpg';
            }
        } else {
            if (empty($h)) {
                $result = $url . '/img/?pic=' . $pic . '&w=' . $w;
            } else {
                $result = $url . '/img/?pic=' . $pic . '&w=' . $w . '&h=' . $h;
            }
        }
    }

    return $result;
}

function ThumbnailDoc($pic, $w = '', $h = '')
{
    $pic = str_replace('.jpg', '', $pic);
    $pic = str_replace('.JPG', '', $pic);
    $pic = str_replace('.png', '', $pic);
    $pic = str_replace('.gif', '', $pic);

    $url = URL;
    if (empty($w)) {
        $result = $url . '/doc/' . $pic . '.jpg';
    } else {
        if (FRIENDLY_URL) {
            if (empty($h)) {
                $result = $url . '/doc/' . $pic . '-' . $w . '.jpg';
            } else {
                $result = $url . '/doc/' . $pic . '-' . $w . 'x' . $h . '.jpg';
            }
        } else {
            if (empty($h)) {
                $result = $url . '/doc/?pic=' . $pic . '&w=' . $w;
            } else {
                $result = $url . '/doc/?pic=' . $pic . '&w=' . $w . '&h=' . $h;
            }
        }
    }

    return $result;
}

function DayOfWeek($date, $style = 0)
{
    $arr[0] = 'อา.';
    $arr[1] = 'จ.';
    $arr[2] = 'อ.';
    $arr[3] = 'พ.';
    $arr[4] = 'พฤ.';
    $arr[5] = 'ศ.';
    $arr[6] = 'ส.';

    $w = date('w', strtotime($date));
    if ($style == 1) {
        $result = $arr[$w];
    } else {
        $result = $w;
    }
    return $result;
}

function PhoneFormat($data)
{
    if (preg_match('/^(\d{3})(\d{3})(\d{4})$/', $data, $matches)) {
        $result = $matches[1] . '-' . $matches[2] . '-' . $matches[3];
        return $result;
    } else {
        return $data;
    }
}


function SysBase64_encode($value, $extend_key = "")
{
    $extend_key = (trim($extend_key) != '') ? base64_encode($extend_key) : base64_encode("1");
    return base64_encode(base64_encode($value)) . "@@" . $extend_key;
}

function SysBase64_decode($value, $extend_key = "")
{
    $var_search = (trim($extend_key) != '') ? "@@" . base64_encode($extend_key) : "@@" . base64_encode("1");
    $value = str_replace($var_search, "", $value);
    return base64_decode(base64_decode($value));
}

function text($txt)
{
    return iconv("tis-620", "utf-8", trim($txt));
}

function curlpost($url, $params)
{
    $ch = curl_init($url);
    $payload = json_encode($params);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $headers = [
        'Content-Type:application/json'
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    if ($response) {
        $response = (json_decode($response, true));
    } else {
        $response = array();
    }


    return $response;
}

function curlposttoken($url, $params, $token)
{
    $ch = curl_init($url);
    $payload = json_encode($params);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    $headers = [
        'Content-Type:application/json',
        'Authorization:' . $token
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    if ($response) {
        $response = array_filter(json_decode($response, true));
    } else {
        $response = array();
    }
    return $response;
}

function buildMultiPartRequest($ch, $boundary, $fields, $files, $token)
{
    $delimiter = '-------------' . $boundary;
    $data = '';

    foreach ($fields as $name => $content) {
        $data .= "--" . $delimiter . "\r\n"
            . 'Content-Disposition: form-data; name="' . $name . "\"\r\n\r\n"
            . $content . "\r\n";
    }
    foreach ($files as $name => $content) {
        $data .= "--" . $delimiter . "\r\n"
            . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $content['name'] . '"' . "\r\n\r\n"
            . $content . "\r\n";
    }

    $data .= "--" . $delimiter . "--\r\n";

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: multipart/form-data; boundary=' . $delimiter,
            'Content-Length: ' . strlen($data),
            'Authorization:' . $token
        ],
        CURLOPT_POSTFIELDS => $data
    ]);

    return $ch;
}

function curlposttokenfile($url, $params, $token)
{

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_UPLOAD, true);
    $headers = [
        'Content-Type:application/json',
        'Authorization:' . $token
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $response = curl_exec($ch);
    curl_close($ch);
    if ($response) {
        $response = array_filter(json_decode($response, true));
    } else {
        $response = array();
    }
    return $response;
}

function build_data_files($boundary, $fields, $files)
{
    $data = '';
    $eol = "\r\n";

    $delimiter = '-------------' . $boundary;

    foreach ($fields as $name => $content) {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="' . $name . "\"" . $eol . $eol
            . $content . $eol;
    }


    foreach ($files as $name => $content) {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $name . '"' . $eol
            //. 'Content-Type: image/png'.$eol
            . 'Content-Transfer-Encoding: binary' . $eol;

        $data .= $eol;
        $data .= $content . $eol;
    }
    $data .= "--" . $delimiter . "--" . $eol;


    return $data;
}

function curl_custom_postfields($ch, array $assoc = array(), array $files = array())
{
    global $token;
    // invalid characters for "name" and "filename"
    static $disallow = array("\0", "\"", "\r", "\n");

    // build normal parameters
    foreach ($assoc as $k => $v) {
        $k = str_replace($disallow, "_", $k);
        $body[] = implode("\r\n", array(
            "Content-Disposition: form-data; name=\"{$k}\"",
            "",
            filter_var($v),
        ));
    }

    // build file parameters
    foreach ($files as $k => $v) {
        switch (true) {
            case false === $v = realpath(filter_var($v)):
            case !is_file($v):
            case !is_readable($v):
                continue; // or return false, throw new InvalidArgumentException
        }
        $data = file_get_contents($v);
        $v = call_user_func("end", explode(DIRECTORY_SEPARATOR, $v));
        $k = str_replace($disallow, "_", $k);
        $v = str_replace($disallow, "_", $v);
        $body[] = implode("\r\n", array(
            "Content-Disposition: form-data; name=\"{$k}\"; filename=\"{$k}\"",
            "Content-Type: application/octet-stream",
            "",
            $data,
        ));
    }

    // generate safe boundary
    do {
        $boundary = "---------------------" . md5(mt_rand() . microtime());
    } while (preg_grep("/{$boundary}/", $body));

    // add boundary for each parameters
    array_walk($body, function (&$part) use ($boundary) {
        $part = "--{$boundary}\r\n{$part}";
    });

    // add final boundary
    $body[] = "--{$boundary}--";
    $body[] = "";

    // set options

    curl_setopt_array($ch, array(
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => implode("\r\n", $body),
        CURLOPT_HTTPHEADER => array(
            "Expect: 100-continue",
//            'Content-Type:application/json',
            "Content-Type: multipart/form-data; boundary={$boundary}",
            'Authorization:' . $token
        ),
    ));
    curl_exec($ch);
    curl_close($ch);
    // $response = json_decode($response, true);
    // return $response;
}

