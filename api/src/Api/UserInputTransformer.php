<?php declare(strict_types=1);

namespace App\Api;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Api\Dto\UserInput as UserDto;
use App\Entity\User as UserEntity;
use App\Validator\Constraints\UserEmail;
use App\Validator\Constraints\UserPassword;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Valid;

class UserInputTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function transform($object, string $to, array $context = [])
    {
        /** @var UserDto $object */
        /** @var UserEntity $entity */
        $entity = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        $object->name = NullObject::equals($object->name) ? ($entity ? $entity->getName() : null) : $object->name;
        $object->email = NullObject::equals($object->email) ? ($entity ? $entity->getEmail() : null) : $object->email;
        // password is omitted intentionally
        $object->roles = NullObject::equals($object->roles) ? ($entity ? $entity->getRoles() : null) : $object->roles;
        $this->validator->validate($object, ['constraints' => [
            new Valid(),
            new UserPassword(['entity' => $entity]),
            new UserEmail(['entity' => $entity]),
        ]]);
        $entity = $entity ?? new UserEntity();

        $entity->setName($object->name);
        $entity->setEmail($object->email);
        $entity->setRoles($object->roles);
        if (! empty($object->password)) {
            $entity->setPassword($this->passwordEncoder->encodePassword($entity, $object->password));
        }

        return $entity;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $to === UserEntity::class && ! $data instanceof UserEntity && null !== ($context['input']['class'] ?? null);
    }
}
