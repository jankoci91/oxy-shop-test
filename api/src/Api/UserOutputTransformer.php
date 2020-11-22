<?php declare(strict_types=1);

namespace App\Api;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Api\Dto\UserOutput as UserDto;
use App\Entity\User as UserEntity;

class UserOutputTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = [])
    {
        /** @var UserEntity $object */
        $dto = new UserDto();

        $dto->id = $object->getId();
        $dto->name = $object->getName();
        $dto->email = $object->getEmail();
        $dto->roles = $object->getRoles();

        return $dto;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $to === UserDto::class && $data instanceof UserEntity;
    }
}
