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

    public function  __construct($client)
    {
        $this->client = $client;
    }

    public function loadUserByUsername($username)
    {
        $userData = $this->client->sendRequest('/users/' . $username);

        $arrayUser = json_decode($userData,true);
        if ($userData) {
            $password = $arrayUser['password'];
            $roles = $arrayUser['roles'];
            $salt = $arrayUser['salt'];

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
