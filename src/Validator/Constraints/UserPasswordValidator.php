<?php declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Api\Dto\UserInput;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UserPasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (! $value instanceof UserInput) {
            throw new UnexpectedValueException($value, UserInput::class);
        }
        if (! $constraint instanceof UserPassword) {
            throw new UnexpectedTypeException($constraint, UserPassword::class);
        }
        if (empty($constraint->entity) && empty($value->password)) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('password')
                ->addViolation();
        }
    }
}
