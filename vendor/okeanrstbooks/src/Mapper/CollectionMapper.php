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
    
    public function getBook(\OkeanrstBooks\Entity\Book $book)
    {
        return $this->em->getRepository('OkeanrstBooks\Entity\Book')->findBy($book);
    }

    public function getBookById($id)
    {
        return $this->em->find('OkeanrstBooks\Entity\Book', $id);
    }
    
    public function getAllBooks()
    {
        return $this->em->getRepository('OkeanrstBooks\Entity\Book')->findAll();
    }

    public function getAllAuthors()
    {
        return $this->em->getRepository('OkeanrstBooks\Entity\Author')->findAll();
    }
    
    public function getAllRubrics()
    {
        return $this->em->getRepository('OkeanrstBooks\Entity\Rubric')->findAll();
    }    

    public function getBooksByRubricId($id)
    {        
        $dql = 'SELECT u FROM OkeanrstBooks\Entity\Book u JOIN u.rubric a WHERE a.id =  ?1';          
        $entity = $this->em->createQuery($dql)
                                ->setParameter(1, $id)                                               
                                ->getResult();            
        return $entity;        
    }
    
    public function getBooksByAuthorId($id)
    {
        return $this->em->getRepository('OkeanrstBooks\Entity\Book')->findBy(array('author' => $id));
    }
    
    public function getBooksByAuthorAndRubric($author_id, $rubric_id)
    {
        return $this->em->getRepository('OkeanrstBooks\Entity\Book')->findBy(array('author' => $author_id, 'rubric' => $rubric_id));
    }
    
    public function add($entity)
	{
	    $this->persist($entity);
	}
    
    public function save($entity)
	{
	    $this->persist($entity);
	}
    
    public function delete($entity)
    {
	    $this->em->remove($entity);
		$this->em->flush();	
	}
    
    public function getAuthorById($id)
    {
        return $this->em->find('OkeanrstBooks\Entity\Author', $id);
    }
    
    public function getRubricById($id)
    {
        return $this->em->find('OkeanrstBooks\Entity\Rubric', $id);        
    }   
    
    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }
}
