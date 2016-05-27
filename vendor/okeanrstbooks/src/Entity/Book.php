<?php

namespace OkeanrstBooks\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToMany(targetEntity="OkeanrstBooks\Entity\Author", inversedBy="book")
     * @ORM\JoinTable(name="books_authors")
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
        $this->rubric = new ArrayCollection();
        $this->author = new ArrayCollection();
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
     * @param \Doctrine\Common\Collections\Collection $rubrics
     *
     * @return Book
     */
    public function addRubric(Collection $rubrics)
    {
        foreach ($rubrics as $rubric) {            
            $this->rubric->add($rubric);
        }        

        return $this;
    }

    /**
     * Remove rubric
     *
     * @param \Doctrine\Common\Collections\Collection $rubrics
     */
    public function removeRubric(Collection $rubrics)
    {
        foreach ($rubrics as $rubric) {            
            $this->rubric->removeElement($rubric);
        }        
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
     * Add author
     *
     * @param \Doctrine\Common\Collections\Collection $authors
     *
     * @return Book
     */
    public function addAuthor(Collection $authors)
    {
        foreach ($authors as $author) {
            $collectionBooks = new ArrayCollection();
            $collectionBooks->add($this);
            $author->addBook($collectionBooks);
            $this->author->add($author);
        }        

        return $this;
    }

    /**
     * Remove author
     *
     * @param \Doctrine\Common\Collections\Collection $authors
     */
    public function removeAuthor(Collection $authors)
    {
        foreach ($authors as $author) {
            $collectionBooks = new ArrayCollection();
            $collectionBooks->add($this);
            $author->removeBook($collectionBooks);
            $this->author->removeElement($author);
        }
    }

    /**
     * Get author
     *
     * @return \Doctrine\Common\Collections\Collection
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
