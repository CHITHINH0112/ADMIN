<?php
require_once "./core/Session.php";

class AuthMiddleware
{
    public static function requireLogin()
    {
        if (!Session::get("user")) {
            echo json_encode(["error" => "You must login"]);
            exit;
        }
    }
}
