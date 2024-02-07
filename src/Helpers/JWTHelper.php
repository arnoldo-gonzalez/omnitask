<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use DomainException;
use InvalidArgumentException;
use UnexpectedValueException;

class JWTHelper {
    public static function create_jwt(array $data, int|null $exp = null) {
        $exp = isset($exp) ? $exp : strtotime("now") + (60 * 60 * 8);
        $payload = [
            "exp" => $exp,
            "data" => $data
        ];
        
        $jwt = JWT::encode($payload, $_ENV["JWT_KEY"], 'HS256');
        return $jwt;
    }

    public static function decode_jwt($jwt) {
        try {
            $decoded = JWT::decode($jwt, new Key($_ENV["JWT_KEY"], 'HS256'));
            return $decoded;
        } catch (InvalidArgumentException $e) {
            return null;
        } catch (DomainException $e) {
            return null;
        } catch (SignatureInvalidException $e) {
            return null;
        } catch (BeforeValidException $e) {
            return null;
        } catch (ExpiredException $e) {
            return null;
        } catch (UnexpectedValueException $e) {
            return null;
        }
    }
}
