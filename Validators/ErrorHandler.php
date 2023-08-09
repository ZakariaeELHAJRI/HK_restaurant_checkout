<?php
class ErrorHandler {
    public static function invalidInputError($message) {
        return array(
            "success" => false,
            "error" => array(
                "code" => 400,
                "message" => $message
            )
        );
    }

    public static function notFoundError($message) {
        return array(
            "success" => false,
            "error" => array(
                "code" => 404,
                "message" => $message
            )
        );
    }

    public static function serverError($message) {
        return array(
            "success" => false,
            "error" => array(
                "code" => 500,
                "message" => $message
            )
        );
    }
    // Add this function badRequestError
    public static function badRequestError($message) {
        return array(
            "success" => false,
            "error" => array(
                "code" => 400,
                "message" => $message
            )
        );
    }
    public static function unauthorizedError($message) {
        return array(
            "success" => false,
            "error" => array(
                "message" => $message,
                "code" => 401
            )
        );
    }

}
?>
