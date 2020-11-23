<?php declare(strict_types=1);

namespace App\Security;

use App\Client\ApiClient;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiAuthenticator extends AbstractLoginFormAuthenticator
{
    private ApiClient $apiClient;
    private UserManager $userManager;
    private RouterInterface $router;

    public function __construct(ApiClient $apiClient, UserManager $userManager, RouterInterface $router)
    {
        $this->apiClient = $apiClient;
        $this->userManager = $userManager;
        $this->router = $router;
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('login');
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') !== 'login' || ($request->request->has('email') && $request->request->has('password'));
    }

    public function authenticate(Request $request): PassportInterface
    {
        if ($request->request->has('email') && $request->request->has('password')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            if ($this->apiClient->authorize($email, $password)) {
                $user = $this->userManager->getByEmail($email);
                return new SelfValidatingPassport(new User($user->email, $user->roles));
            }
            throw new BadCredentialsException();
        }
        if ($this->apiClient->isAuthorized()) {
            $email = $this->apiClient->getAuthorizedEmail();
            $user = $this->userManager->getByEmail($email);
            return new SelfValidatingPassport(new User($user->email, $user->roles));
        }
        throw new CustomUserMessageAuthenticationException('Authentication required.');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }
}
