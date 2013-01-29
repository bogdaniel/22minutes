<?php
$router->add('index', '/index/home/{name}', 'SiteName:Controller:Index:Home', 'Home', ['name' => '(\w+)']);
