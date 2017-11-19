<?php
//Copyright EzioisAwesome56 for discord code
//You need a channel webhook URL for this to work correctly
function HeyReport($stuff)
{
	$data = array("content" => $stuff, "username" => Settings::pluginGet("username"), "avatar_url" => Settings::pluginGet("image"),);
    $curl = curl_init(Settings::pluginGet("webhook"));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $fartz = curl_exec($curl);
}
