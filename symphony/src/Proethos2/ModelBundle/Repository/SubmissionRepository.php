<?php

namespace Proethos2\ModelBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SubmissionRepository
 */
class SubmissionRepository extends EntityRepository
{
    private function getQueryBuilder()
    {
        $em = $this->getEntityManager();
     
        $queryBuilder = $em->getRepository('Proethos2ModelBundle:Submission')
        ->createQueryBuilder('p');
     
        return $queryBuilder;
    }

    public function findAllInOrder()
    {
        $qb = $this->getQueryBuilder()
        ->orderBy('p.created', 'desc');
     
        return $qb->getQuery()->getResult();
    }
}
