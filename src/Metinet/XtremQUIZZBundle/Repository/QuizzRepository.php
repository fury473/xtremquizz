<?php

namespace Metinet\XtremQUIZZBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * QuizzRepository
 */
class QuizzRepository extends EntityRepository
{
    public function getNbQuizz() {
        return $this->_em->createQuery('
			SELECT
				COUNT(i)
			FROM
				MetinetXtremQUIZZBundle:Quizz i
                        WHERE
                                i.state = 1
		');
    }
    
    public function getNbQuizzJoues() {
        return $this->_em->createQuery('
			SELECT
				COUNT(i)
			FROM
				MetinetXtremQUIZZBundle:Quizz i
                        WHERE
                                i.nbLaunches > 0 AND i.state = 1
		');
    }
    
    public function getTopQuizz(){
        return $this->_em->createQuery('
                SELECT
                        i.id, i.title, i.picture
                FROM
                        MetinetXtremQUIZZBundle:Quizz i
                WHERE
                        i.state = 1
                ORDER BY
                        i.nbLaunches DESC
        ')->setMaxResults(3);
    }
    
    public function getFlopQuizz(){
        return $this->_em->createQuery('
                SELECT
                        i.id, i.title, i.picture
                FROM
                        MetinetXtremQUIZZBundle:Quizz i
                WHERE
                        i.state = 1
                ORDER BY
                        i.nbLaunches ASC
        ')->setMaxResults(3);
    }

    public function getLastQuizzId() {
        return $this->_em->createQuery('
			SELECT
				i.id, i.title, i.picture, i.winPoints
			FROM
				MetinetXtremQUIZZBundle:Quizz i
                        WHERE
                                i.state = 1
                        ORDER BY
                                i.createdAt DESC
		')->setMaxResults(4);
    }
    public function getPromotedQuizz() {
        return $this->_em->createQuery('
			SELECT
				i.id, i.title, i.picture, i.winPoints
			FROM
				MetinetXtremQUIZZBundle:Quizz i
                        WHERE
                                i.isPromoted = 1 AND i.state = 1
                        ORDER BY
                                i.createdAt DESC
		')->setMaxResults(1);
    }
    public function getQuizzByTheme($idTheme) {
        return $this->_em->createQuery("
                    SELECT
                            i.id, i.title, i.picture, i.shortDesc, i.winPoints
                    FROM
                            MetinetXtremQUIZZBundle:Quizz i
                    WHERE
                            i.theme = $idTheme AND i.state = 1
            ");
    }
        public function getAllQuizz() {
        return $this->_em->createQuery("
                    SELECT
                            i.id, i.title, i.picture, i.shortDesc, i.winPoints
                    FROM
                            MetinetXtremQUIZZBundle:Quizz i
                    WHERE
                            i.state = 1
            ");
    }
    
     public function getNbQuizzTheme($idTheme) {
        return $this->_em->createQuery("
                    SELECT
                            COUNT(i)
                    FROM
                            MetinetXtremQUIZZBundle:Quizz i
                    WHERE
                            i.theme = $idTheme AND i.state = 1
            ");
    }
}
