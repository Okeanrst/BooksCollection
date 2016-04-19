<?php
namespace OkeanrstBooks\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @Gedmo\Uploadable(path="data/books", callback="myCallbackMethod", filenameGenerator="SHA1", allowOverwrite=true, appendNumber=true)
 */
class File
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column
     * @Gedmo\UploadableFilePath
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=64, nullable=false, unique=false)
     * @Gedmo\UploadableFileName
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64, nullable=true, unique=true)
     * @Gedmo\UploadableFileMimeType
     */
    private $mimeType;

    /**
     * @ORM\Column(type="decimal")
     * @Gedmo\UploadableFileSize
     */
    private $size;
    
    /**
     * @ORM\Column(type="boolean", nullable=false) 
     */
    private $isfoto;

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
     * Set path
     *
     * @param string $path
     *
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return File
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return File
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set isfoto
     *
     * @param boolean $isfoto
     *
     * @return File
     */
    public function setIsfoto($isfoto)
    {
        $this->isfoto = $isfoto;

        return $this;
    }

    /**
     * Get isfoto
     *
     * @return boolean
     */
    public function getIsfoto()
    {
        return $this->isfoto;
    }
    
    public function myCallbackMethod(array $info)
    {
        // Do some stuff with the file..
    }
    
}