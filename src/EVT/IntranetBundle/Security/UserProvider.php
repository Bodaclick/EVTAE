<?php
namespace EVT\IntranetBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use EVT\IntranetBundle\Security\EVTUser;

class UserProvider implements UserProviderInterface
{
    private $client;
    private $session;

    public function __construct($client, $session)
    {
        $this->client = $client;
        $this->session = $session;
    }

    public function loadUserByUsername($username)
    {
        $userData = $this->client->get('/api/users/' . $username);

        $arrayUser = $userData->getBody();
        if ('200' == $userData->getStatusCode()) {
            $password = $arrayUser['password'];
            $roles = $arrayUser['roles'];
            $salt = $arrayUser['salt'];

            $this->session->set('_role', strtolower(str_replace('ROLE_', '', $roles[0])));

            return new EVTUser($username, $password, $salt, $roles);
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof EVTUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'EVT\IntranetBundle\Security\EVTUser';
    }
}
