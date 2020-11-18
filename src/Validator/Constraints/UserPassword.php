<?php declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class UserPassword extends Constraint
{
    public $message = 'Password is required.';
    public $entity;
}
