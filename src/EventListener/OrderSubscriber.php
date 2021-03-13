<?php

namespace App\EventListener;

use App\Entity\Order;
use App\Entity\SonataUserUser;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderSubscriber implements EventSubscriber
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage) {
        $this->tokenStorage = $tokenStorage;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Order) {
            return;
        }
        $entityManager = $args->getObjectManager();
        $loggedInUser = $this->tokenStorage->getToken()->getUser();
        $entity->setUser($loggedInUser);
        $randomEmployee = $entityManager->getRepository(SonataUserUser::class)->getRandomUserByRole(SonataUserUser::ROLE_EMPLOYEE);
        $entity->setEmployee($randomEmployee);
    }
}