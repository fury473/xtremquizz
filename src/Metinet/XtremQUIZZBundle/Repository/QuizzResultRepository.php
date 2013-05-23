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
    
    public function getUserAvarageTime($user_id) {
        $quizzResults = $this->createQueryBuilder('q')
            ->select('q.elapsedTime')
            ->where('q.user = :user')
            ->setParameters(array('user' => $user_id))
            ->getQuery()
            ->getResult();
        $elapsedTimes = array();
        foreach($quizzResults as $quizzResult) {
            if(!is_null($quizzResult['elapsedTime'])) {
                array_push($elapsedTimes, $quizzResult['elapsedTime']);
            }
        }
        
        $average = null;
        $nbTimes = count($elapsedTimes);
        if($nbTimes > 0) {
            $average = 0;
            foreach($elapsedTimes as $elapsedTime) {
                $average += $elapsedTime;
            }
            $average = $average/$nbTimes;
        }
    
        return $average;
    }
}
