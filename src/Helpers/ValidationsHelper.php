<?php
namespace App\Helpers;

use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\JWTHelper;
use App\Traits\ValidationsTraits;

class ValidationsHelper {
    use ValidationsTraits;
    private static $invalid_symbols = "/[\,\\;$\+\"\/\<\:\>\&\?\¿\'\.\´\¨]/";

    public static function is_logged(Request $req) {
        if (!$req->hasHeader("Authorization")) return false;

        $header = $req->getHeader("Authorization");
        $req_jwt = substr($header[0], 7);
        if (!isset($req_jwt)) return null;

        $jwt = JWTHelper::decode_jwt($req_jwt);
        if (!isset($jwt)) return null;

        return $jwt;
    }

    public static function user_signup(array $user_data) {
        $errors = ["errors" => [], "ok" => true];

        if (!array_key_exists("name", $user_data) || !self::name($user_data["name"])) {
            $errors["errors"][] = "Informacion invalida, ni el nombre ni la contraseña pueden contener los siguientes simbolos: " .  self::$invalid_symbols . ", y el nombre debe tener menos de 25 caracteres";
            $errors["ok"] = false;
        }

        if (!array_key_exists("password", $user_data) || !self::password($user_data["password"])) {
            $errors["errors"][] = "Informacion invalida, la contraseña debe tener menos de 40 y mas de 10 caracteres y no puede contener los siguientes simbolos: " . self::$invalid_symbols;
            $errors["ok"] = false;
        }

        if (!array_key_exists("email", $user_data) || !self::email($user_data["email"])) {
            $errors["errors"][] = "Informacion invalida, por favor, ingrese un email valido";
            $errors["ok"] = false;
        }

        if (!self::premium($user_data["premium"])) {
            $errors["errors"][] = "Informacion invalida, por favor, ingrese un tipo de plan valido";
            $errors["ok"] = false;
        }

        if ($user_data["premium"] === "true" && !self::pay_method($user_data["pay_method"]) ) {
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

        if (!array_key_exists("password", $user_data) || !self::password($user_data["password"])) {
            $errors["errors"][] = "Informacion invalida, la contraseña no puede contener los siguientes simbolos: ,;$\{}()[]+\"/:<>&?¿'.´¨) y ademas debe tener mas de 10 caracteres y menos de 40";
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
