<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EntityNotFoundException extends HttpException
{
    public function __construct(
        int $statusCode = Response::HTTP_NOT_FOUND,
        string $message = 'The Entity has not been found.',
        ?\Throwable $previous = null,
        array $headers = [],
        int $code = Response::HTTP_NOT_FOUND,
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
