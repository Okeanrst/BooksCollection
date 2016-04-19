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
     * @var \OkeanrstBooks\Entity\File
     *
     * @ORM\OneToOne(targetEntity="OkeanrstBooks\Entity\File")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="photo_id", referencedColumnName="id", unique=true, nullable=true, onDelete="CASCADE")
     * })
     */
    private $photofile;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="OkeanrstBooks\Entity\Rubric")
     * @ORM\JoinTable(name="books_rubric",
     *   joinColumns={
     *     @ORM\JoinColumn(name="book_id", referencedColumnName="id", nullable=true)
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="rubric_id", referencedColumnName="id", nullable=true)
     *   }
     * )
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
     * @var \OkeanrstBooks\Entity\File
     *
     * @ORM\OneToOne(targetEntity="OkeanrstBooks\Entity\File")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="file_id", referencedColumnName="id", unique=true, nullable=true, onDelete="CASCADE")
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
     * @param \OkeanrstBooks\Entity\File $photofile
     *
     * @return Book
     */
    public function setPhotofile(\OkeanrstBooks\Entity\File $photofile = null)
    {
        $this->photofile = $photofile;

        return $this;
    }

    /**
     * Get photofile
     *
     * @return \OkeanrstBooks\Entity\File
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
     * @param \OkeanrstBooks\Entity\File $bookfile
     *
     * @return Book
     */
    public function setBookfile(\OkeanrstBooks\Entity\File $bookfile = null)
    {
        $this->bookfile = $bookfile;

        return $this;
    }

    /**
     * Get bookfile
     *
     * @return \OkeanrstBooks\Entity\File
     */
    public function getBookfile()
    {
        return $this->bookfile;
    }
 
    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy() 
    {
        return get_object_vars($this);
    }
 
    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function exchangeArray ($data = array()) 
    {
        $this->id = $data['id'];
        $this->artist = $data['artist'];
        $this->title = $data['title'];
    }
 
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
 
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'title',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'author',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}