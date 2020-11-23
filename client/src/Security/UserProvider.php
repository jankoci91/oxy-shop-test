<?php declare(strict_types=1);

namespace App\Security;

use App\Client\ApiClient;
use App\Manager\UserManager;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private ApiClient $apiClient;
    private UserManager $userManager;

    public function __construct(ApiClient $apiClient, UserManager $userManager)
    {
        $this->apiClient = $apiClient;
        $this->userManager = $userManager;
    }

    public function loadUserByUsername(string $username)
    {
        if (! $this->apiClient->isAuthorized()) {
            throw new UsernameNotFoundException();
        }
        $user = $this->userManager->getByEmail($username);
        if ($user) {
            return new User($user->email, $user->roles);
        }
        throw new UsernameNotFoundException();
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass(string $class)
    {
        return User::class === $class;
    }
}
