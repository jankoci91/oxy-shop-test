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
        $users = [];
        if ($response->isData()) {
            foreach ($response->getContent() as $array) {
                $users[] = $this->mapper->arrayToDto((array) $array);
            }
        }
        return $users;
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

    public function save(User $user): array
    {
        if ($user->id) {
            $response = $this->client->patch($user->id, $user->name, $user->email, $user->password, $user->roles);
        } else {
            $response = $this->client->post($user->name, $user->email, $user->password, $user->roles);
        }
        if ($response->isValidation()) {
            return $response->getContent();
        }
        if ($response->isData()) {
            $this->mapper->arrayToDto($response->getContent(), $user);
            return [];
        }
        throw new ManagerException('Unable to save user');
    }

    public function delete(int $id): void
    {
        $response = $this->client->delete($id);
        if (! $response->isData()) {
            throw new ManagerException("Unable to delete user #$id");
        }
    }
}
