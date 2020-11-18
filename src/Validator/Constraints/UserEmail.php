<?php declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class UserEmail extends Constraint
{
    public $message = 'E-mail is already used.';
    public $entity;
}
