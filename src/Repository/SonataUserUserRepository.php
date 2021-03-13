<?php

namespace App\Repository;

class SonataUserUserRepository extends AbstractEntityRepository
{
    public function getRandomUserByRole($role)
    {
        $users = $this->createQueryBuilder('u')
            ->select('u.id')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%'.$role.'%')
            ->getQuery()
            ->getArrayResult();

        $userIds = array_column($users, 'id');

        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userIds[array_rand($userIds, 1)])
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}