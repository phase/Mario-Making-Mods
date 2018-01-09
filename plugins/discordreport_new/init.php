<?php

$WebHookdata = array("content" => str_replace('@', '[at]', $stuff), "username" => Settings::get("WebHookName"), "avatar_url" => Settings::get("webhookimage"));

function HelpReport($stuff) {
	$discordhelpcurl = curl_init(Settings::get("helpwebhook"));
	curl_setopt($discordhelpcurl, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($discordhelpcurl, CURLOPT_POSTFIELDS, json_encode($WebHookdata));
	curl_setopt($discordhelpcurl, CURLOPT_RETURNTRANSFER, true);
	return curl_exec($discordhelpcurl);
}

function DevReport($stuff) {
	$discorddevcurl = curl_init(Settings::get("devwebhook"));
	curl_setopt($discorddevcurl, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($discorddevcurl, CURLOPT_POSTFIELDS, json_encode($WebHookdata));
	curl_setopt($discorddevcurl, CURLOPT_RETURNTRANSFER, true);
	return curl_exec($discorddevcurl);
}

function PostReport($stuff) {
	$discordpostcurl = curl_init(Settings::get("ForumWebhook"));
	curl_setopt($discordpostcurl, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($discordpostcurl, CURLOPT_POSTFIELDS, json_encode($WebHookdata));
	curl_setopt($discordpostcurl, CURLOPT_RETURNTRANSFER, true);
	Report('CURL is setup! Webhook URL: '.Settings::get("ForumWebhook").' Stuff: '.$stuff.' Username: '.Settings::get("WebHookName"));
	return curl_exec($discordpostcurl);
}