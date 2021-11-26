<?php

namespace App\Security\Guard;

use App\DataTransfertObject\Credentials;
use App\Form\LoginType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class WebAuthenticator extends AbstractFormLoginAuthenticator
{
    /**
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $urlGenerator;

    private FormFactoryInterface $formFactory;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param FormFactoryInterface $formFactory
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(
        UrlGeneratorInterface        $urlGenerator,
        FormFactoryInterface         $formFactory,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->formFactory = $formFactory;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate("security_login");
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        return $request->isMethod(Request::METHOD_POST)
            && $request->attributes->get('_route') === 'security_login';
    }

    public function getCredentials(Request $request)
    {
        $credential = new Credentials('');
        $form = $this->formFactory->create(LoginType::class, $credential)->handleRequest($request);

        if (!$form->isValid()) {
            throw new AuthenticationException('Email and password is not valid.');
        }

        return $credential;
    }

    /**
     * @param Credentials $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface|void|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
//        var_dump($userProvider);
//        var_dump($credentials);
//        var_dump($credentials->getUsername());
//        dd($credentials);
        return $userProvider->loadUserByUsername($credentials->getUsername());
    }

    /**
     * @param Credentials $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
//        dd($credentials->getPassword());
//        dd($this->userPasswordEncoder->isPasswordValid($user, $credentials->getPassword()));
        if ($valid = $this->userPasswordEncoder->isPasswordValid($user, $credentials->getPassword())) {
            return true;
        }

        return new AuthenticationException('Password not valid');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return new RedirectResponse($this->urlGenerator->generate("index"));
    }

}