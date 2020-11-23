<?php declare(strict_types=1);

namespace App\Mapper;

use App\Dto\User;

class UserMapper
{
    public function dtoToArray(User $dto): array
    {
        return [
            'id' => $dto->id,
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
            'roles' => $dto->roles,
        ];
    }

    public function arrayToDto(array $array): User
    {
        $dto = new User();
        if (isset($array['id'])) {
            $dto->id = $array['id'];
        }
        if (isset($array['name'])) {
            $dto->name = $array['name'];
        }
        if (isset($array['email'])) {
            $dto->email = $array['email'];
        }
        if (isset($array['password'])) {
            $dto->password = $array['password'];
        }
        if (isset($array['roles'])) {
            $dto->roles = $array['roles'];
        }
        return $dto;
    }
}
