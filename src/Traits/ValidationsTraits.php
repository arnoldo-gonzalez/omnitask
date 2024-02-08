<?php
namespace App\Traits;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

use App\Helpers\JWTHelper;
use App\Helpers\ValidationsHelper as ValHelper;

trait ValidationsTraits {
    public static function email(string $email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) < 35;
    }

    public static function name(string $text) {
        return !preg_match(self::$invalid_symbols, $text) && strlen($text) < 25 && strlen($text) > 3;
    }

    public static function password(string $password) {
        return !preg_match(self::$invalid_symbols, $password) && strlen($password) > 3 && strlen($password) <= 40;
    }

    public static function premium(string $premium) {
        return $premium === "true" || $premium === "false";
    }

    public static function pay_method(string $pay_method) {
        return in_array($pay_method, ["paypal", "debit_card", "credit_card"]);
    }

    public static function title(string $text) {
        return !preg_match(self::$invalid_symbols, $text) && strlen($text) <= 50 && strlen($text) > 5;
    }

    public static function subtitle(string $text) {
        return !preg_match(self::$invalid_symbols, $text) && strlen($text) <= 25 && strlen($text) > 1;
    }

    public static function description(string $text) {
        return !preg_match(self::$invalid_symbols, $text) && strlen($text) <= 200 && strlen($text) > 5;
    }

    public static function time_check(array $time) {
        return is_numeric($time[0]) || is_numeric($time[1]) || is_numeric($time[2]) || intval($time[0]) > 12 || intval($time[0]) < 0 || intval($time[1]) > 59 || intval($time[1]) < 0 || intval($time[2]) > 59 || intval($time[2]) < 0;
    }

    public static function datetime(string $datetime) {
        if (preg_match("/\"\\\'\/&/", $datetime)) return false;

        $date = explode(" ", $datetime);
        if (!isset($date) || count($date) !== 2) return false;
        
        $years = explode("-", $date[0]); // Saves an array of 3 values [0 => year, 1 => month, 2 => day]
        if (!isset($years) || count($years) !== 3 || !checkdate($years[1], $years[2], $years[0])) return false;

        $time = explode(":", $date[1]); // Saves an array of 3 values [0 => hours, 1 => minutes, 2 => seconds]
        if (!isset($time) || count($time) !== 3 || !self::time_check($time)) return false;

        return true;
    }
}
