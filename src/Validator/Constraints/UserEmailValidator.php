<?php declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Api\Dto\UserInput;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UserEmailValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (! $value instanceof UserInput) {
            throw new UnexpectedValueException($value, UserInput::class);
        }
        if (! $constraint instanceof UserEmail) {
            throw new UnexpectedTypeException($constraint, UserEmail::class);
        }
        $entity = $this->entityManager->getRepository(User::class)->findOneBy([User::EMAIL => $value->email]);
        if ($entity && (is_null($constraint->entity) || $constraint->entity->getId() !== $entity->getId())) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('email')
                ->addViolation();
        }
    }
}
