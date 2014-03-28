<?php

namespace Lancio\Cambuse\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


class UniqueProductCodeInDb extends Constraint
{
    public $message= 'Questo codice è già presente';

    public function validatedBy()
    {
        return 'unique.product.validator';
//        return 'uniqueproductcodeindb';
//              return get_class($this).'Validator';
    }

}
