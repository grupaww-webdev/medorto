<?php

declare(strict_types=1);


namespace App\Validation\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidationException extends \InvalidArgumentException
{
    /** @var ConstraintViolationListInterface */
    private $violationList;

    public function __construct(ConstraintViolationListInterface $violationList)
    {
        parent::__construct('Validation error', 0, null);
        $this->violationList = $violationList;
    }

    public function getViolationList() : ConstraintViolationListInterface
    {
        return $this->violationList;
    }
}
