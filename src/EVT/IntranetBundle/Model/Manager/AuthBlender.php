<?php

namespace EVT\IntranetBundle\Model\Manager;

use Predis\Client;
use Doctrine\ORM\EntityManager;

/**
 * Class AuthBlender
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class AuthBlender
{
    private $redisService;
    private $entityManager;

    public function __construct(EntityManager $entityManager, Client $redisService)
    {
        $this->entityManager = $entityManager;
        $this->redisService = $redisService;
    }

    public function blendForUser($userName, $role)
    {
        $this->removeAll($userName);

        // get auths for role
        $auths = $this->entityManager->getRepository('EVTIntranetBundle:Auth')
            ->findByUserNameOrRole($userName, $role);

        // for every url -> blend auths
        foreach ($auths as $auth) {

            // view
            $this->setValue($auth->hasView(), 'evt_auth:'. $userName .':' .$auth->getRoute() .':view');

            // Update
            $this->setValue($auth->hasUpdate(), 'evt_auth:'. $userName .':' .$auth->getRoute() .':edit');

            // Create
            $this->setValue($auth->hasCreate(), 'evt_auth:'. $userName .':' .$auth->getRoute() .':create');

            // Delete
            $this->setValue($auth->hasDelete(), 'evt_auth:'. $userName .':' .$auth->getRoute() .':delete');
        }
    }

    private function removeAll($userName)
    {
        $keys = $this->redisService->keys('evt_auth:'.$userName.':*');
        foreach ($keys as $toDeleteKey) {
            $this->redisService->del($toDeleteKey);
        }
    }

    private function setValue($hasAction, $key)
    {
        if ($hasAction) {
            $this->redisService->set($key, '1');
        } else {
            $this->redisService->set($key, '0');
        }
    }
}
