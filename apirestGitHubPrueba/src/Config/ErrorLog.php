<?php 

namespace App\Config;

date_default_timezone_set('America/Mazatlan');

class ErrorLog 
{
    public static function activaErrorLog(){
        error_reporting(E_ALL);
        ini_set('ignore_repeated_errors', TRUE);
        ini_set('display_errors', FALSE);
        ini_set('Log_errors', TRUE);
        ini_set('error_log', dirname(__DIR__) . '/Logs/php-error-'.date('Ymd').'.log');
    }
}