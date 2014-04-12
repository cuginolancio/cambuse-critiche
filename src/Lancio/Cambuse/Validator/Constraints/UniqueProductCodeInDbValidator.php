<?php

namespace Lancio\Cambuse\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueProductCodeInDbValidator extends ConstraintValidator
{
    private $repository;

    public function __construct($repository) {
        $this->repository = $repository;
    }


    public function validate($value, Constraint $constraint)
    {
        if ($value === $constraint->code) {
            return;
        }
        $product = $this->repository->findByCode($value);
        
        if ($product)
        {
            $this->context->addViolation(
                $constraint->message, 
                array('%string%' => $value)
            );
        }
    }

}