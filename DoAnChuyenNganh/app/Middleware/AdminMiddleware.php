<?php
require_once "./core/Session.php";

class AdminMiddleware
{
    public static function requireAdmin()
    {
        $user = Session::get("user");

        if (!$user || $user['role'] != "admin") {
            echo json_encode(["error" => "Admin only"]);
            exit;
        }
    }
}
