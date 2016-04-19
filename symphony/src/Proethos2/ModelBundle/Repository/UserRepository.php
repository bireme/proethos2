<?php

namespace Proethos2\ModelBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 */
class UserRepository extends EntityRepository
{
    private function getQueryBuilder()
    {
        $em = $this->getEntityManager();
     
        $queryBuilder = $em->getRepository('Proethos2ModelBundle:User')
        ->createQueryBuilder('p');
     
        return $queryBuilder;
    }
}
