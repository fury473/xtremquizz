<?php

namespace Metinet\XtremQUIZZBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="Metinet\XtremQUIZZBundle\Repository\QuestionRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;
    
    public function getAbsolutePath()
    {
        return null === $this->picture
            ? null
            : $this->getUploadRootDir().'/'.$this->picture;
    }
    
    public function getWebPath()
    {
        return null === $this->picture
            ? null
            : $this->getUploadDir().'/'.$this->picture;
    }
 
    protected function getUploadRootDir() {
        return $_SERVER['DOCUMENT_ROOT'].$this->getUploadDir();
    }
 
    protected function getTmpUploadRootDir() {
        return $_SERVER['DOCUMENT_ROOT'].$this->getUploadDir();
    }
    
    protected function getUploadDir() {
        return '/bundles/metinetxtremquizz/images/';
    }
 
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function uploadPicture() {
        // the file property can be empty if the field is not required
        if (null === $this->picture) {
            return;
        }
        if (!$this->id) {
            $this->picture->move($this->getTmpUploadRootDir(), $this->picture->getClientOriginalName());
        } else {
            $this->picture->move($this->getUploadRootDir(), $this->picture->getClientOriginalName());
        }
        $this->setPicture($this->picture->getClientOriginalName());
    }
    
    private $temp;

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
     * @Assert\File(maxSize="6000000")
     */
    private $file;

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
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->path = $this->getFile()->guessExtension();
        }
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }
        
        $this->getFile()->move($this->getUploadRootDir(), $this->picture);

        // check if we have an old image
        if (isset($this->temp) && file_exists($this->temp)) {
            // delete the old image
            unlink($this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->id.'.'.$this->getFile()->guessExtension()
        );

        $this->setFile(null);
    }
    
    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->temp) && file_exists($this->temp)) {
            unlink($this->temp);
        }
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