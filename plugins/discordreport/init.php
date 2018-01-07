<?php

$WebHookdata = ["content" => str_replace('@', '[at]', $stuff), "username" => Settings::get("WebHookName"), "avatar_url" => Settings::Get("webhookimage")];

function HelpReport($stuff)
{
    $curl = curl_init(Settings::get("helpwebhook"));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($WebHookdata));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $fartz = curl_exec($curl);
}

function DevReport($stuff)
{
    $curl = curl_init(Settings::Get("devwebhook"));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($Webhookdata));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $fartz = curl_exec($curl);
}

function PostReport($stuff)
{
    $curl = curl_init(Settings::Get("ForumWebhook"));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($Webhookdata));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $fartz = curl_exec($curl);
}