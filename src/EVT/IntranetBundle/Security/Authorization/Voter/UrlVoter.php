<?php

namespace EVT\IntranetBundle\Security\Authorization\Voter;

use Predis\Client;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UrlVoter
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class UrlVoter implements VoterInterface
{
    const VIEW = 'view';
    const CREATE = 'create';
    const EDIT = 'edit';
    const DELETE = 'delete';

    private $redisService;

    public function __construct(Client $redisService)
    {
        $this->redisService = $redisService;
    }

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, [self::VIEW, self::CREATE, self::EDIT, self::DELETE]);
    }

    public function supportsClass($class)
    {
        $supportedClass = 'string';

        return $supportedClass === $class;
    }

    public function vote(TokenInterface $token, $urlName, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!is_string($urlName)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // check if the voter is used correct, only allow one attribute
        // this isn't a requirement, it's just one easy way for you to
        // design your voter
        if (1 !== count($attributes)) {
            throw new InvalidArgumentException('Not valid action');
        }

        // set the attribute to check against
        $attribute = $attributes[0];

        // check if the given attribute is covered by this voter
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // get current logged in user
        $user = $token->getUser();

        // make sure there is a user object (i.e. that the user is logged in)
        if ($user instanceof UserInterface) {
            $username = $user->getUsername();
        } elseif (is_string($user)) {
            $username = $user;
        } else {
            return VoterInterface::ACCESS_DENIED;
        }

        $val = $this->redisService->get('evt_auth:'. $username .':' .$urlName .':' . $attribute);

        if ('1' === $val) {
            return VoterInterface::ACCESS_GRANTED;
        }
        return VoterInterface::ACCESS_DENIED;
    }
}
