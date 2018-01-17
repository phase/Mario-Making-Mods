<?php

setlocale(LC_ALL, "it_IT");
$birthdayExample = "Giugno 26, 1990";
$dateformats = array("", "mm-gg-aaaa", "gg-mm-aaaa", "aaaa-mm-gg", "AAAA-mm-gg", "m/d/Y", "d.m.y", "M j Y", "D jS M Y");
$timeformats = array("", "h:i A", "h:i:s A", "H:i", "H:i:s");

$months = [
    "",
    "Gennaio",
    "Febbraio",
    "Marzo",
    "Aprile",
    "Maggio",
    "Giugno",
    "Luglio",
    "Agosto",
    "Settembre",
    "Ottobre",
    "Novembre",
    "Dicembre"];

$days = [
    "",
    "Domenica",
    "Lunedì",
    "Martedì",
    "Mercoledì",
    "Giovedì",
    "Venerdì",
    "Sabato"];

function PluralWord($s) {
    if($s == "MySQL")
        return $s;

    return $s."s";
}

function Plural($i, $s) {
	if($i == 1)
		return $i." ".$s;
	if(substr($s,-1) == "o")
		$s = substr_replace($s, "i", -1);
	else if(substr($s,-1) == "a")
		$s = substr_replace($s, "e", -1);
    else if(substr($s, -2) == "te")
    	$s = substr_replace($s, "ti", -2);
	else
		$s .= " ";
	return $i." ".$s;
}

function HisHer($user)
{
	return "suo";
}

function stringtotimestamp($str) {
    global $months;
    $parts = explode(" ", $str);
    $day = (int)$parts[1];
    $month = $parts[0];
    $month = str_replace(",", "", $month);
    $year = (int)$parts[2];
    for($m = 1; $m <= 12; $m++) {
        if(strcasecmp($month, $months[$m]) == 0) {
            $month = $m;
            break;
        }
    }
    if((int)$month != $month)
        return 0;
    return mktime(12,0,0, $month, $day, $year);
}

function timestamptostring($t) {
    if($t == 0)
        return "";
    return strftime("%B %#d, %Y", $t);
}