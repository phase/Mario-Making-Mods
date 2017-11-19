<?php

setcookie('forcelayout', 1, time()+365*24*3600, URL_ROOT, "", false, true);
die(header('Location: '.pageLink('home')));