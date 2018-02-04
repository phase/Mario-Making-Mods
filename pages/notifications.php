<?php
if (!defined('BLARG')) die();

$title = 'Notifications';

$notif = getNotifications(true);

RenderTemplate('page_notifs', array(
	'notifs' => $notif));