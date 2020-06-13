<?php

namespace Incapsula\API\Adapter;

class IncapsulaException extends \Exception
{
    public static $errors = [
        1 => 'Unexpected error',
        2 => 'Invalid input',
        4 => 'Operation timed-out or server unavailable',
        9403 => 'Unknown/unauthorized account_id',
        9411 => 'Authentication missing or invalid',
        9413 => 'Unknown/unauthorized site_id',
        9414 => 'Feature not permitted',
        9415 => 'Operation not allowed'
    ];

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        // Set default message if one is not provided
        if (empty($message) && isset(self::$errors[$code])) {
            $message = self::$errors[$code];
        }
        parent::__construct($message, $code, $previous);
    }
}
