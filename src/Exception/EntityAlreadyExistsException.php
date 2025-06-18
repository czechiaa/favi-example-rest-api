<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EntityAlreadyExistsException extends HttpException
{
    public function __construct(
        int $statusCode = Response::HTTP_CONFLICT,
        string $message = 'The entity already exists.',
        ?\Throwable $previous = null,
        array $headers = [],
        int $code = Response::HTTP_CONFLICT,
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
