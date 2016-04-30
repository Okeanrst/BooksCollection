<?php

namespace OkeanrstBooks\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface; 
 
/**
 * Book
 *
 * @ORM\Table(name="books")
 * @ORM\Entity 
 */
class Book
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=64, precision=0, scale=0, nullable=false, unique=false)
     */
    private $title;

    /**
     * @var \OkeanrstBooks\Entity\Filephoto
     *
     * @ORM\OneToOne(targetEntity="OkeanrstBooks\Entity\Filephoto", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="filephotos_id", referencedColumnName="id", unique=true, nullable=true)
     * })
     */
    private $photofile;
    
    /**
     * @ORM\ManyToMany(targetEntity="Rubric")
     * @ORM\JoinTable(name="books_rubrics",
     *      joinColumns={@ORM\JoinColumn(name="book_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="rubric_id", referencedColumnName="id")}
     *      )
     */
    private $rubric;

    /**
     * @var \OkeanrstBooks\Entity\Author
     *
     * @ORM\ManyToOne(targetEntity="OkeanrstBooks\Entity\Author")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $author;

    /**
     * @var \OkeanrstBooks\Entity\Filebook
     *
     * @ORM\OneToOne(targetEntity="OkeanrstBooks\Entity\Filebook", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="filebooks_id", referencedColumnName="id", unique=true, nullable=false)
     * })
     */
    
    private $bookfile;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rubric = new \Doctrine\Common\Collections\ArrayCollection();
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
     *
     * @return Book
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
     * Set photofile
     *
     * @param \OkeanrstBooks\Entity\Filebook $bookfile
     *
     * @return Book
     */
    public function setPhotofile(\OkeanrstBooks\Entity\Filephoto $photofile = null)
    {
        $this->photofile = $photofile;

        return $this;
    }

    /**
     * Get photofile
     *
     * @return \OkeanrstBooks\Entity\Filebook
     */
    public function getPhotofile()
    {
        return $this->photofile;
    }

    /**
     * Add rubric
     *
     * @param \OkeanrstBooks\Entity\Rubric $rubric
     *
     * @return Book
     */
    public function addRubric(\OkeanrstBooks\Entity\Rubric $rubric)
    {
        $this->rubric[] = $rubric;

        return $this;
    }

    /**
     * Remove rubric
     *
     * @param \OkeanrstBooks\Entity\Rubric $rubric
     */
    public function removeRubric(\OkeanrstBooks\Entity\Rubric $rubric)
    {
        $this->rubric->removeElement($rubric);
    }

    /**
     * Get rubric
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRubric()
    {
        return $this->rubric;
    }

    /**
     * Set author
     *
     * @param \OkeanrstBooks\Entity\Author $author
     *
     * @return Book
     */
    public function setAuthor(\OkeanrstBooks\Entity\Author $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \OkeanrstBooks\Entity\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set bookfile
     *
     * @param \OkeanrstBooks\Entity\Filebook $bookfile
     *
     * @return Book
     */
    public function setBookfile(\OkeanrstBooks\Entity\Filebook $bookfile = null)
    {
        $this->bookfile = $bookfile;

        return $this;
    }

    /**
     * Get bookfile
     *
     * @return \OkeanrstBooks\Entity\Filebook
     */
    public function getBookfile()
    {
        return $this->bookfile;
    }

    public function getPath()
    {
        return '/';
    }
    
}
