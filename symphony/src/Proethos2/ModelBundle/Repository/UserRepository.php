<?php

namespace Proethos2\ModelBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 */
class UserRepository extends EntityRepository
{
    // public function getPosts($id) {
        
    //     $em = $this->getEntityManager()->getConfiguration();
    //     $qb = $em->getRepository->createQueryBuilder('p');
    //     $qb->join('p.platform', 'f')
    //        ->where($qb->expr()->eq('f.id', $id));
    //     return $qb;
    // }
}
