<?php declare(strict_types=1);

namespace App\Mapper;

use App\Dto\User;

class UserMapper
{
    public function dtoToArray(User $dto): array
    {
        return [
            User::ID => $dto->id,
            User::NAME => $dto->name,
            User::EMAIL => $dto->email,
            User::PASSWORD => $dto->password,
            User::ROLES => $dto->roles,
        ];
    }

    public function arrayToDto(array $array, User $dto = null): User
    {
        $dto = $dto ?? new User();
        if (isset($array[User::ID])) {
            $dto->id = $array[User::ID];
        }
        if (isset($array[User::NAME])) {
            $dto->name = $array[User::NAME];
        }
        if (isset($array[User::EMAIL])) {
            $dto->email = $array[User::EMAIL];
        }
        if (isset($array[User::PASSWORD])) {
            $dto->password = $array[User::PASSWORD];
        }
        if (isset($array[User::ROLES])) {
            $dto->roles = $array[User::ROLES];
        }
        return $dto;
    }
}
