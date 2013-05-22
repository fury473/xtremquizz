<?php

namespace Metinet\XtremQUIZZBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * QuizzResultRepository
 */
class QuizzResultRepository extends EntityRepository
{ 
    public function getByUserIdAndQuizzId($user_id, $quizz_id) {
        $qb = $this->createQueryBuilder('q')
            ->where('q.user = :user')
            ->andWhere('q.quizz = :quizz')
            ->setParameters(array('user' => $user_id, 'quizz' => $quizz_id))
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
        return $qb;
    }
    
    public function getIdByUserIdAndQuizzId($user_id, $quizz_id) {
        $qb = $this->createQueryBuilder('q')
            ->select('q.id')
            ->where('q.user = :user')
            ->andWhere('q.quizz = :quizz')
            ->setParameters(array('user' => $user_id, 'quizz' => $quizz_id))
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
        return $qb;
    }
}
