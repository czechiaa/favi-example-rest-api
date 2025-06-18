<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidatorException extends \RuntimeException
{
    private ConstraintViolationListInterface $constraintViolationList;

    public function __construct(
        string $message = '',
        int $code = Response::HTTP_UNPROCESSABLE_ENTITY,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function create(ConstraintViolationListInterface $constraintViolationList): self
    {
        $self = new self();

        $self->constraintViolationList = $constraintViolationList;

        return $self;
    }

    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }
}
