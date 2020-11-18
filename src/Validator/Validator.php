<?php declare(strict_types=1);

namespace App\Validator;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

class Validator implements ValidatorInterface
{
    private SymfonyValidatorInterface $validator;
    private ?ContainerInterface $container;

    public function __construct(SymfonyValidatorInterface $validator, ContainerInterface $container = null)
    {
        $this->validator = $validator;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($data, array $context = [])
    {
        if (null !== $validationGroups = $context['groups'] ?? null) {
            if (
                $this->container &&
                \is_string($validationGroups) &&
                $this->container->has($validationGroups) &&
                ($service = $this->container->get($validationGroups)) &&
                \is_callable($service)
            ) {
                $validationGroups = $service($data);
            } elseif (\is_callable($validationGroups)) {
                $validationGroups = $validationGroups($data);
            }

            if (!$validationGroups instanceof GroupSequence) {
                $validationGroups = (array) $validationGroups;
            }
        }

        $constraints = isset($context['constraints']) && is_array($context['constraints']) ? $context['constraints'] : null;
        $violations = $this->validator->validate($data, $constraints, $validationGroups);
        if (0 !== \count($violations)) {
            throw new ValidationException($violations);
        }
    }
}
