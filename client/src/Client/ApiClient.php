<?php declare(strict_types=1);

namespace App\Client;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    private string $host;
    private string $tokenEndpoint;
    private HttpClientInterface $client;
    private SessionInterface $session;

    public function __construct(string $host, string $tokenEndpoint, HttpClientInterface $client, SessionInterface $session)
    {
        $this->host = $host;
        $this->tokenEndpoint = $tokenEndpoint;
        $this->client = $client;
        $this->session = $session;
    }

    public function getAuthorizedEmail(): string
    {
        if ($this->isAuthorized()) {
            return $this->getEmail();
        }
        throw new ClientException('Unauthorized');
    }

    public function isAuthorized(): bool
    {
        if (empty($this->getToken())) {
            return false;
        }
        $options = ['headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getToken(),
        ]];
        try {
            $authorized = $this->client->request('GET', $this->host, $options)->getStatusCode() === 200;
            if (! $authorized) {
                $this->setToken('');
                $this->setEmail('');
            }
            return $authorized;
        } catch (TransportExceptionInterface $e) {
            throw new ClientException('Authorization check failed with exception', $e);
        }
    }

    public function authorize(string $email, string $password): bool
    {
        $options = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'email' => $email,
                'password' => $password,
            ]),
        ];
        try {
            $response = $this->client->request('POST', $this->host . $this->tokenEndpoint, $options);
            $statusCode = $response->getStatusCode();
        } catch (TransportExceptionInterface $e) {
            throw new ClientException('Authorization failed with exception', $e);
        }
        if ($statusCode === 200) {
            $content = json_decode($response->getContent(false));
            if (isset($content->token)) {
                $this->setToken($content->token);
                $this->setEmail($email);
                return true;
            }
        }
        if ($statusCode === 401) {
            $this->setToken('');
            $this->setEmail('');
            return false;
        }
        throw new ClientException("Authorization failed with status code $statusCode");
    }

    public function get(string $path): Response
    {
        return $this->request('GET', $path);
    }

    public function post(string $path, array $body): Response
    {
        return $this->request('POST', $path, $body);
    }

    public function patch(string $path, array $body): Response
    {
        return $this->request('PATCH', $path, $body);
    }

    public function delete(string $path): Response
    {
        return $this->request('DELETE', $path);
    }

    private function request($method, $path, array $body = []): Response
    {
        $url = $this->host . $path;
        $options = ['headers' => [
            'Accept' => 'application/json',
            'Content-Type' => $method === 'PATCH' ? 'application/merge-patch+json' : 'application/json',
            'Authorization' => 'Bearer ' . $this->getToken(),
        ]];
        if ($body) {
            $options['body'] = json_encode($body);
        }
        try {
            $response = $this->client->request($method, $url, $options);
            $statusCode = $response->getStatusCode();
            switch ($statusCode) {
                case 200:
                case 201:
                case 204:
                    return Response::data($response->getContent());
                case 400:
                    return Response::validation($response->getContent(false));
                case 404:
                    return Response::noData();
                default:
                    throw new ClientException("$method $url failed with status code $statusCode");
            }
        } catch (TransportExceptionInterface | HttpExceptionInterface $e) {
            throw new ClientException("$method $url failed with exception", $e);
        }
    }

    private function setToken(string $token): void
    {
        $this->session->set('api-auth-token', $token);
    }

    private function getToken(): string
    {
        return $this->session->get('api-auth-token', '');
    }

    private function setEmail(string $email): void
    {
        $this->session->set('api-auth-email', $email);
    }

    private function getEmail(): string
    {
        return $this->session->get('api-auth-email', '');
    }
}
