<?php

namespace Bilyiv\RequestDataBundle\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class ValidationException extends \Exception
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $errors;

    public function __construct(ConstraintViolationListInterface $errors, string $message = null, int $code = 0, \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }
}

