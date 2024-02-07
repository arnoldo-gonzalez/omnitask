<?php
namespace App\Helpers;

use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\JWTHelper;

class ValidationsHelper {
    private static $valid_pay_methods = ["paypal", "debit_card", "credit_card"];
    private static $invalid_symbols = "/[\,\\;$\+\"\/\<\:\>\&\?\¿\'\.\´\¨]/";

    public static function is_logged(Request $req) {
        if (!$req->hasHeader("Authorization")) return false;

        $header = $req->getHeader("Authorization");
        return substr($header[0], 7);
    }

    public static function email(string $email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match(self::$invalid_symbols, $text);
    }

    public static function text(string $text) {
        return !preg_match(self::$invalid_symbols, $text);
    }

    public static function user_signup(array $user_data) {
        $errors = ["errors" => [], "ok" => true];

        if (!array_key_exists("name", $user_data) || !self::text($user_data["name"]) || (array_key_exists("password", $user_data) && !self::text($user_data["password"])) || strlen($user_data["name"]) > 25) {
            $errors["errors"][] = "Informacion invalida, ni el nombre ni la contraseña pueden contener los siguientes simbolos: " .  self::$invalid_symbols . ", y el nombre debe tener menos de 25 caracteres";
            $errors["ok"] = false;
        }

        if (!array_key_exists("password", $user_data) || strlen($user_data["password"]) > 40 || strlen($user_data["password"]) > 10) {
            $errors["errors"][] = "Informacion invalida, la contraseña debe tener menos de 40 y mas de 10 caracteres";
            $errors["ok"] = false;
        }

        if (!array_key_exists("email", $user_data) || !self::email($user_data["email"])) {
            $errors["errors"][] = "Informacion invalida, por favor, ingrese un email valido";
            $errors["ok"] = false;
        }

        if ($user_data["premium"] !== "true" && $user_data["premium"] !== "false") {
            $errors["errors"][] = "Informacion invalida, por favor, ingrese un tipo de plan valido";
            $errors["ok"] = false;
        }

        if ($user_data["premium"] === "true" && !in_array($user_data["pay_method"], self::$valid_pay_methods) ) {
            $errors["errors"][] = "Informacion invalida, por favor, ingrese un metodo de pago valido";
            $errors["ok"] = false;
        }

        if (!(count($user_data) === 5)) {
            $errors["errors"][] = "Cantidad de contenido inesperada";
            $errors["ok"] = false;
        }

        return $errors;
    }

    public static function user_signin(array $user_data) {
        $errors = ["errors" => [], "ok" => true];

        if (array_key_exists("password", $user_data) && !self::text($user_data["password"])) {
            $errors["errors"][] = "Informacion invalida, la contraseña no puede contener los siguientes simbolos: " .  self::$invalid_symbols;
            $errors["ok"] = false;
        }

        if (!array_key_exists("password", $user_data) || strlen($user_data["password"]) > 40 || strlen($user_data["password"]) > 10) {
            $errors["errors"][] = "Informacion invalida, la contraseña debe tener menos de 40 y mas de 10 caracteres";
            $errors["ok"] = false;
        }

        if (!array_key_exists("email", $user_data) || !self::email($user_data["email"])) {
            $errors["errors"][] = "Informacion invalida, por favor, ingrese un email valido";
            $errors["ok"] = false;
        }

        if (!(count($user_data) === 2)) {
            $errors["errors"][] = "Cantidad de contenido inesperada";
            $errors["ok"] = false;
        }

        return $errors;
    }
}
