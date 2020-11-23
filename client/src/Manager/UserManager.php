<?php declare(strict_types=1);

namespace App\Manager;

use App\Client\UserClient;
use App\Dto\User;
use App\Mapper\UserMapper;

class UserManager
{
    private UserClient $client;
    private UserMapper $mapper;

    public function __construct(UserClient $client, UserMapper $mapper)
    {
        $this->client = $client;
        $this->mapper = $mapper;
    }

    public function getRoles(): array
    {
        return $this->client->getRoles();
    }

    public function getAll(): array
    {
        $response = $this->client->getAll();
        if ($response->isData()) {
            $users = [];
            foreach ($response->getContent() as $array) {
                $users[] = $this->mapper->arrayToDto((array) $array);
            }
            return $users;
        }
        return [];
    }

    public function getById(int $id): ?User
    {
        $response = $this->client->getById($id);
        if ($response->isData()) {
            return $this->mapper->arrayToDto($response->getContent());
        }
        return null;
    }

    public function getByEmail(string $email): ?User
    {
        $response = $this->client->getByEmail($email);
        if ($response->isData()) {
            return $this->mapper->arrayToDto($response->getContent());
        }
        return null;
    }

    public function save(User $user): User
    {
        if ($user->id) {
            $response = $this->client->patch($user->id, $user->name, $user->email, $user->password, $user->roles);
        } else {
            $response = $this->client->post($user->name, $user->email, $user->password, $user->roles);
        }
        if ($response->isData()) {
            return $this->mapper->arrayToDto($response->getContent());
        }
        throw new \LogicException('todo: validation');
    }
}
