<?php

$link = getServerDomainNoSlash().'/'.actionLink("profile", $user['id'], "", $user['name']);

PostReport("New user: ".$user['name']." -- ".$link);