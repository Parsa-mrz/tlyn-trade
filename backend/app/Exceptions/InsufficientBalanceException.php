<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception thrown when a user tries to perform an action
 * requiring more balance than is available in their wallet.
 */
class InsufficientBalanceException extends Exception
{
    /**
     * Create a new InsufficientBalanceException instance.
     *
     * @param string $message The exception message (default: 'Insufficient balance').
     * @param int $code The HTTP status code or custom error code (default: 422).
     */
    public function __construct(string $message = 'Insufficient balance', int $code = 422)
    {
        parent::__construct($message, $code);
    }
}
