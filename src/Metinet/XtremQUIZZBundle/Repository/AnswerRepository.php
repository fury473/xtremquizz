<?php

namespace Metinet\XtremQUIZZBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AnswerRepository
 */
class AnswerRepository extends EntityRepository
{
    public function getAnswersToQuestion($question) {
        return $this->createQueryBuilder('a')
                ->where('a.question = :question')
                ->setParameters(array('question' => $question))
                ->getQuery()
                ->getResult();
    }
}
