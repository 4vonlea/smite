<?php
function debug($value='')
{
	echo "<pre>";
	print_r($value);
	exit;
}

function number_to_words($number)
{
    $before_comma = trim(to_word($number));
    $after_comma  = trim(comma($number));
    return ucwords($results = $before_comma);
}

function to_word($number)
{
    $words      = "";
    $arr_number = array(
        "",
        "satu",
        "dua",
        "tiga",
        "empat",
        "lima",
        "enam",
        "tujuh",
        "delapan",
        "sembilan",
        "sepuluh",
        "sebelas");

    if ($number < 12) {
        $words = " " . $arr_number[$number];
    } else if ($number < 20) {
        $words = to_word($number - 10) . " belas";
    } else if ($number < 100) {
        $words = to_word($number / 10) . " puluh " . to_word($number % 10);
    } else if ($number < 200) {
        $words = "seratus " . to_word($number - 100);
    } else if ($number < 1000) {
        $words = to_word($number / 100) . " ratus " . to_word($number % 100);
    } else if ($number < 2000) {
        $words = "seribu " . to_word($number - 1000);
    } else if ($number < 1000000) {
        $words = to_word($number / 1000) . " ribu " . to_word($number % 1000);
    } else if ($number < 1000000000) {
        $words = to_word($number / 1000000) . " juta " . to_word($number % 1000000);
    } else {
        $words = "undefined";
    }
    return $words;
}

function comma($number)
{
    $after_comma = stristr($number, ',');
    $arr_number  = array(
        "nol",
        "satu",
        "dua",
        "tiga",
        "empat",
        "lima",
        "enam",
        "tujuh",
        "delapan",
        "sembilan");

    $results = "";
    $length  = strlen($after_comma);
    $i       = 1;
    while ($i < $length) {
        $get = substr($after_comma, $i, 1);
        $results .= " " . $arr_number[$get];
        $i++;
    }
    return $results;
}
/**
 * @params $date DateTime 
 */
function indo_date($date){
    $formatDate = $date->format("Y-n-d-w-H-i-s");
    $splitDate = explode("-",$formatDate);
    $month = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $dayOfWeek = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    return "{$dayOfWeek[$splitDate[3]]}, {$splitDate[2]} {$month[$splitDate[1]]} {$splitDate[0]} ";
}

function news_date($date){
    $newsDate = new DateTime($date);
    $now = new DateTime();
    $diff = $newsDate->diff($now);
    $hours = $diff->h;
    $hours = $hours + ($diff->days*24);

    if($hours <= 24){
        return "$hours Jam yang lalu";
    }else if($hours > 24 && $hours <= 48){
        return "Kemarin";
    }else{
        return indo_date($newsDate);
    }
}

