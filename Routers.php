<?php
require 'Library/Twelve/Utils/Utils.php';
use Twelve\Utils\Utils;
echo "Current URL: ";
Utils::var_dump($_SERVER['REQUEST_URI']);
// Assume this is the matched route
$routes[] = array(
    'pattern' => '/index.php/index/home/{name}',
    'controller' => 'SiteName:Index:Home, name',
    'params' => array(
        'name' => '(\w+)'
    )
);
$routes[] = array(
    'pattern' => '/Contact/homes/{name}',
    'controller' => 'SiteName:Contact:homes, name',
    'params' => array(
        'name' => '(\w+)'
    )
);
$routes[] = array(
    'pattern' => '/Exec/homed/{name}',
    'controller' => 'SiteName:Exec:homed, name',
    'params' => array(
        'name' => '(\w+)'
    )
);
$routes[] = array(
    'pattern' => '/Indexx/homex/{name}',
    'controller' => 'SiteName:Indexx:homex, name',
    'params' => array(
        'name' => '(\w+)'
    )
);
Utils::var_dump($routes);

foreach ($routes as $route) {
    // Store a copy of the URI we can modify
    $uri = $route['pattern'];
    // Find {params} in URI
    if (preg_match_all('/\{(?:[^\}]+)\}/', $uri, $matches, PREG_SET_ORDER)) {

        // Loop through params
        foreach ($matches[0] as $param) {

            // Swap {param} with a placeholder
            $uri = str_replace($param, "%s", $uri);
        }

        // Build final regex
        $regex = '/^' . preg_quote($uri, '/') . '$/';
        $regex = vsprintf($regex, $route['params']);

        Utils::var_dump($regex);
        // Quick test
        if (preg_match($regex, $_SERVER['REQUEST_URI'])) {
            echo $route['controller'];
            Utils::var_dump(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            Utils::var_dump('Match!');

            return true;
        }
    }
}
