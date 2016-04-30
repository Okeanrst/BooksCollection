<?php

namespace OkeanrstBooks\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Filephoto
 *
 * @ORM\Table(name="Filephoto")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Filephoto
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
     * @ORM\Column(name="path", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="mimeType", type="string", length=64, precision=0, scale=0, nullable=true, unique=false)
     */
    private $mimeType;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="decimal", precision=0, scale=0, nullable=false, unique=false)
     */
    private $size;


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

    /** @ORM\PreUpdate */
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        if ($eventArgs->getEntity() instanceof Filephoto) {
            if ($eventArgs->hasChangedField('name')) {                
                @unlink('./public'.$eventArgs->getOldValue('path'));
            }
        }
    }

    /** @ORM\PreRemove */
    public function preRemove($eventArgs)
    {
        if ($eventArgs->getEntity() instanceof Filephoto) {                           
            @unlink('./public'.$eventArgs->getEntity()->getPath());            
        }
    }    
}

