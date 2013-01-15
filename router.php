<?php
// router.php
if (preg_match('#\.php$#', $_SERVER['REQUEST_URI'])) {
    require basename($_SERVER['REQUEST_URI']); // serve php file
} elseif (strpos($_SERVER['REQUEST_URI'], '.') !== false) {
    return false; // serve file as-is
}
