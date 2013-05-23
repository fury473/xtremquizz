<?php

namespace Metinet\XtremQUIZZBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 */
class UserRepository extends EntityRepository {

    public function getNbJoueur() {
        return $this->_em->createQuery('
            SELECT
                COUNT(i)
            FROM
                MetinetXtremQUIZZBundle:User i
        ');
    }

    public function getNbJoueur7jours() {
        $date = date('Y-m-d');
        $NewDate = Date('Y-m-d', strtotime("-7 days"));
        return $this->_em->createQuery("
            SELECT
                COUNT(i)
            FROM
                MetinetXtremQUIZZBundle:User i
            WHERE
                i.createdAt BETWEEN '$NewDate' AND '$date'
        ");
    }

    public function getNbJoueur30jours() {
        $date = date('Y-m-d');
        $NewDate = Date('Y-m-d', strtotime("-30 days"));
        return $this->_em->createQuery("
            SELECT
                COUNT(i)
            FROM
                MetinetXtremQUIZZBundle:User i
            WHERE
                i.createdAt BETWEEN '$NewDate' AND '$date'
        ");
    }

    public function getScoreMoyen() {
        return $this->_em->createQuery("
            SELECT
                AVG(i.points)
            FROM
                MetinetXtremQUIZZBundle:User i
        ");
    }

    public function getPoints() {
        return $this->_em->createQuery("
            SELECT
                i.points
            FROM
                MetinetXtremQUIZZBundle:User i
        ");
    }

    public function getAverageTime($fbUid) {
        return $this->_em->createQuery("
            SELECT
                i.averageTime
            FROM
                MetinetXtremQUIZZBundle:User i
            WHERE
                i.fbUid = $fbUid
        ");
    }

    public function getClassementJoueur($Jpoints, $averageTime) {
        return $this->_em->createQuery("
            SELECT
                COUNT(i)
            FROM
                MetinetXtremQUIZZBundle:User i
            WHERE
                i.points > $Jpoints OR (i.points = $Jpoints AND i.averageTime < $averageTime)
        ");
    }

    public function getClassementAll() {
        return $this->_em->createQuery("
            SELECT
                i.id, i.fbUid, i.firstname, i.lastname, i.username, i.points, i.averageTime
            FROM
                MetinetXtremQUIZZBundle:User i
            ORDER BY
                i.points DESC
        ");
    }

    public function getDoublonsPoints() {
        return $this->_em->createQuery("
            SELECT 
                i.id, i.points, COUNT(i.points) occ
            FROM 
                MetinetXtremQUIZZBundle:User i
            GROUP BY 
                i.points
            HAVING 
                COUNT(i.points) >1
        ");
    }

    public function getRank($limit = null, $offset = 0) {
        $qb = $this->createQueryBuilder('u')
                ->orderBy('u.points', 'DESC');
        if ($limit) {
            $qb->setMaxResults($limit);
        }
        if ($offset) {
            $qb->setFirstResult($offset);
        }
        return $qb->getQuery()->getResult();
    }

    public function get10DerJoueurs() {
        return $this->_em->createQuery('
            SELECT
                i.firstname, i.lastname, i.points, i.fbUid
            FROM
                MetinetXtremQUIZZBundle:User i
            ORDER BY
                i.createdAt DESC
        ')->setMaxResults(10);
    }
}
