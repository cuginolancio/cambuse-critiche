<?php

namespace Lancio\Cambuse\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueProductCodeInDbValidator extends ConstraintValidator
{

    private $repository;
//    private $security_context;

    public function __construct($repository) {
        $this->repository = $repository;
    }


    public function validate($value, Constraint $constraint)
    {
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