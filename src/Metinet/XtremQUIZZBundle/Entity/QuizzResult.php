<?php

namespace Metinet\XtremQUIZZBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuizzResult
 *
 * @ORM\Table(name="quizz_result")
 * @ORM\Entity(repositoryClass="Metinet\XtremQUIZZBundle\Repository\QuizzResultRepository")
 */
class QuizzResult
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="datetime")
     */
    private $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_end", type="datetime", nullable=true)
     */
    private $dateEnd;

    /**
     * @var float
     *
     * @ORM\Column(name="average", type="float", nullable=true)
     */
    private $average;
    
    /**
     * @var int
     *
     * @ORM\Column(name="elapsed_time", type="integer", nullable=true)
     */
    private $elapsedTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="win_points", type="integer", nullable=true)
     */
    private $winPoints;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="quizzResults")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Quizz", inversedBy="quizzResults")
     * @ORM\JoinColumn(name="quizz_id", referencedColumnName="id")
     */
    protected $quizz;
    
    /**
     * Display the quizz result as his id
     */
    public function __toString()
    {
        return $this->id;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return QuizzResult
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     * @return QuizzResult
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set average
     *
     * @param float $average
     * @return QuizzResult
     */
    public function setAverage($average)
    {
        $this->average = $average;

        return $this;
    }

    /**
     * Get average
     *
     * @return float
     */
    public function getAverage()
    {
        return $this->average;
    }
    
    /**
     * Set elapsed time
     *
     * @param integer $elapsedTime
     * @return QuizzResult
     */
    public function setElapsedTime($elapsedTime)
    {
        $this->elapsedTime = $elapsedTime;

        return $this;
    }

    /**
     * Get elapsed time
     *
     * @return integer
     */
    public function getElapsedTime()
    {
        return $this->elapsedTime;
    }

    /**
     * Set winPoints
     *
     * @param integer $winPoints
     * @return QuizzResult
     */
    public function setWinPoints($winPoints)
    {
        $this->winPoints = $winPoints;

        return $this;
    }

    /**
     * Get winPoints
     *
     * @return integer
     */
    public function getWinPoints()
    {
        return $this->winPoints;
    }

    /**
     * Set user
     *
     * @param \Metinet\XtremQUIZZBundle\Entity\User $user
     * @return QuizzResult
     */
    public function setUser(\Metinet\XtremQUIZZBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Metinet\XtremQUIZZBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set quizz
     *
     * @param \Metinet\XtremQUIZZBundle\Entity\Quizz $quizz
     * @return QuizzResult
     */
    public function setQuizz(\Metinet\XtremQUIZZBundle\Entity\Quizz $quizz = null)
    {
        $this->quizz = $quizz;

        return $this;
    }

    /**
     * Get quizz
     *
     * @return \Metinet\XtremQUIZZBundle\Entity\Quizz
     */
    public function getQuizz()
    {
        return $this->quizz;
    }
}