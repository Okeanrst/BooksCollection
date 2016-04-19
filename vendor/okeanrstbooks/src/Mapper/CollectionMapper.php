<?php

namespace OkeanrstBooks\Mapper;

use Doctrine\ORM\EntityManagerInterface;

class CollectionMapper
{
    protected $em;   
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;        
    }
    
    public function findBookById(int $id)
    {
        return $this->em->find('OkeanrstBooks\Entity\Book', $id);
    }
    
    public function getAllBooks()
    {
        return $this->em->getRepository('OkeanrstBooks\Entity\Book')->findAll();
    }
    
    public function deleteBook(int $id)
    {
	    $entity = $this->getEntityManagerService()->find('OkeanrstBooks\Entity\Book', $id);
		if ($entity) {
		    $this->em->remove($entity);
		    return $this->em->flush();
		}
		return false;
	}
    
    public function findAuthorById(int $id)
    {
        return $this->em->find('OkeanrstBooks\Entity\Author', $id);
    }
    
    public function findRubricById(int $id)
    {
        return $this->em->find('OkeanrstBooks\Entity\Rubric', $id);        
    }
    
    public function findBooksByRubricId(int $id)
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());
        return $er->findOneBy(array('username' => $username));
    }
    
    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }
}