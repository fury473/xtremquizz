<?php

namespace Metinet\XtremQUIZZBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * QuestionRepository
 */
class QuestionRepository extends EntityRepository
{
    public function getnbQuestionParQuizz($idQuizz) {
        return $this->_em->createQuery("
                    SELECT
                            COUNT(i)
                    FROM
                            MetinetXtremQUIZZBundle:Question i
                    WHERE
                            i.quizz = $idQuizz
            ");
    }
}
