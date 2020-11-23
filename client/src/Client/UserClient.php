<?php declare(strict_types=1);

namespace App\Client;

class UserClient
{
    private string $userEndpoint;
    private ApiClient $client;

    public function __construct(string $userEndpoint, ApiClient $client)
    {
        $this->userEndpoint = $userEndpoint;
        $this->client = $client;
    }

    public function getRoles(): array
    {
        return [
            'ROLE_ADMIN' => 'Admin',
            'ROLE_USER' => 'User',
        ];
    }

    public function getAll(): Response
    {
        return $this->client->get($this->userEndpoint);
    }

    public function getById(int $id): Response
    {
        return $this->client->get("$this->userEndpoint/{$id}");
    }

    public function getByEmail(string $email): Response
    {
        $response = $this->client->get("$this->userEndpoint/?email=$email");
        if ($response->isData()) {
            return Response::data(json_encode($response->getContent()[0]));
        }
        return $response;
    }

    public function post(string $name, string $email, string $password, array $roles): Response
    {
        return $this->client->post($this->userEndpoint, [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'roles' => $roles,
        ]);
    }

    public function patch(int $id, string $name, string $email, ?string $password, array $roles): Response
    {
        $body = [
            'name' => $name,
            'email' => $email,
            'roles' => $roles,
        ];
        if ($password) {
            $body['password'] = $password;
        }
        return $this->client->patch("$this->userEndpoint/{$id}", $body);
    }

    public function delete(int $id): Response
    {
        return $this->client->delete("$this->userEndpoint/{$id}");
    }
}
