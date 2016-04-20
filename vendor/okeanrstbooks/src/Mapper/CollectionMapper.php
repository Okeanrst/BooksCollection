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
    
    public function findBookById($id)
    {
        return $this->em->find('OkeanrstBooks\Entity\Book', $id);
    }
    
    public function getAllBooks()
    {
        return $this->em->getRepository('OkeanrstBooks\Entity\Book')->findAll();
    }
    
    public function getBooksByRubric($id)
    {
        return $this->em= $em->getRepository('OkeanrstBooks\Entity\Book')->findBy(array('rubric' => $id));
    }
    
    public function getBooksByAuthor($id)
    {
        return $this->em= $em->getRepository('OkeanrstBooks\Entity\Book')->findBy(array('author' => $id));
    }
    
    public function getBooksByAuthorAndRubric($author_id, $rubric_id)
    {
        return $this->em= $em->getRepository('OkeanrstBooks\Entity\Book')->findBy(array('author' => $author_id, 'rubric' => $rubric_id));
    }
    
    public function add($entity)
	{
	    return $this->persist($entity);
	}
    
    public function save($entity)
	{
	    return $this->persist($entity);
	}
    
    public function deleteBook($id)
    {
	    $entity = $this->getEntityManagerService()->find('OkeanrstBooks\Entity\Book', $id);
		if ($entity) {
		    $this->em->remove($entity);
		    return $this->em->flush();
		}
		return false;
	}
    
    public function findAuthorById($id)
    {
        return $this->em->find('OkeanrstBooks\Entity\Author', $id);
    }
    
    public function findRubricById($id)
    {
        return $this->em->find('OkeanrstBooks\Entity\Rubric', $id);        
    }
    
    public function findBooksByRubricId($id)
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