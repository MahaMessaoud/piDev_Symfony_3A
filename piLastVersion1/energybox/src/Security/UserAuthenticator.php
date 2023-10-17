<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(UserRepository $userRepository,private UrlGeneratorInterface $urlGenerator)
    {
        $this->userRepository = $userRepository;

    }

    public function authenticate(Request $request): Passport
    {

        $email = $request->request->get('email', '');
        $user = $this->userRepository->findOneBy(['email' => $email]);

        $request->getSession()->set(Security::LAST_USERNAME, $email);
        if ($user && $user->isBlocked()) {
            throw new CustomUserMessageAuthenticationException('Your account has been blocked. Please contact the administrator for assistance.');
        }
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
      /*  if (user::IsVerified() == true) {
            return new RedirectResponse($this->urlGenerator->generate('app_oups_account'));
        }*/
        $user = $token->getUser();

        if (in_array("ROLE_COACH", $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_home_front'));
        }
        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_home_back_office'));
        }
        if (in_array("ROLE_ABONNE", $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_home_front'));
        }
        return new RedirectResponse($this->urlGenerator->generate('app_oups_account'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        $user = $this->userRepository->findOneByUsername($username);

        if (!$user) {
            throw new UsernameNotFoundException('User not found');
        }

        if ($user->isLocked()) {
            throw new LockedException('User is locked');
        }

        return $user;
    }






}
