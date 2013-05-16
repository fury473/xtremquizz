<?php

namespace Metinet\XtremQUIZZBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="Metinet\XtremQUIZZBundle\Repository\QuestionRepository")
 */
class Question
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    private $picture;

    /**
     * @ORM\ManyToOne(targetEntity="Quizz", inversedBy="questions")
     * @ORM\JoinColumn(name="quizz_id", referencedColumnName="id")
     */
    protected $quizz;

    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question", cascade={"remove", "persist"})
     * @ORM\OrderBy({"title" = "ASC"})
     */
    protected $answers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Display the question as his title
     */
    public function __toString()
    {
        return $this->title;
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
     * Set title
     *
     * @param string $title
     * @return Question
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return Question
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set quizz
     *
     * @param \Metinet\XtremQUIZZBundle\Entity\Quizz $quizz
     * @return Question
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

    /**
     * Add answers
     *
     * @param \Metinet\XtremQUIZZBundle\Entity\Answer $answers
     * @return Question
     */
    public function addAnswer(\Metinet\XtremQUIZZBundle\Entity\Answer $answers)
    {
        $this->answers[] = $answers;

        return $this;
    }

    /**
     * Remove answers
     *
     * @param \Metinet\XtremQUIZZBundle\Entity\Answer $answers
     */
    public function removeAnswer(\Metinet\XtremQUIZZBundle\Entity\Answer $answers)
    {
        $this->answers->removeElement($answers);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}