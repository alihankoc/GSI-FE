<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 20.06.2019
 * Time: 20:57
 */

include 'vendor/autoload.php';
$dotenv =\Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST as $key => $value) {

        if (is_array($value)) {
            print_r($value);
            echo "<br/>";
        } else {
            echo $key . " => " . $value . "\n <br/>";
        }


    }
}