<?php

namespace OkeanrstBooks\Service;

use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;

class CollectionService
{
    protected $mapper;   
    
    public function __construct($collectionMapper)
    {
        $this->mapper = $collectionMapper;        
    }
    
    public function getAllBooks()
    {
        return $this->mapper->getAllBooks();
    }
    
    public function getAllBooksPaginator($page, $itemCount)
    {
        $result = $this->getAllBooks();
        if ($result) {
            return $this->getPaginator($result, $page, $itemCount);
        }
        return false;
    }
    
    public function getBooksByRubric($id)
    {
        return $this->mapper->getBooksByRubric((int) $id);
    }
    
    public function getBooksByRubricPaginator($id, $page, $itemCount)
    {
        $result = $this->getBooksByRubric($id);
        if ($result) {
            return $this->getPaginator($result, $page, $itemCount);
        }
        return false;
    }
    
    public function getAllAuthorsPaginator($page, $itemCount)
    {
        $result = $this->mapper->getAllAuthors();
        if ($result) {
            return $this->getPaginator($result, $page, $itemCount);
        }
        return false;
    }

    public function getAllRubricsPaginator($page, $itemCount)
    {
        $result = $this->mapper->getAllRubrics();
        if ($result) {
            return $this->getPaginator($result, $page, $itemCount);
        }
        return false;
    }

    public function getBooksByAuthor($id)
    {
        return $this->mapper->getBooksByAuthor((int) $id);
    }
    
    public function getBooksByAuthorPaginator($id, $page, $itemCount)
    {
        $result = $this->getBooksByAuthor($id);
        if ($result) {
            return $this->getPaginator($result, $page, $itemCount);
        }
        return false;
    }
    
    public function getBooksByAuthorAndRubric($author_id, $rubric_id)
    {
        return $this->mapper->getBooksByAuthorAndRubric((int) $author_id, (int) $rubric_id);
    }
    
    public function getBooksByAuthorAndRubricPaginator($author_id, $rubric_id, $page, $itemCount)
    {
        $result = $this->getBooksByAuthorAndRubric($author_id, $rubric_id);
        if ($result) {            
            return $this->getPaginator($result, $page, $itemCount);
        }
        return false;
    }
    
    public function addBook(\OkeanrstBooks\Entity\Book $entity)
    {
        $author = $entity->getAuthor();
        /*if (!is_a($author, 'OkeanrstBooks\Entity\Author')) {
            $author = $this->mapper->findAuthorById($author);
            $entity->setAuthor($author);
        }*/
        return $this->mapper->add($entity);
    }
    
    public function addAuthor(\OkeanrstBooks\Entity\Author $entity)
    {
        return $this->mapper->add($entity);
    }
    
    public function addRubric(\OkeanrstBooks\Entity\Rubric $entity) {
        return $this->mapper->add($entity);
    }
    
    public function __call($name, $arguments)
    {        
        return call_user_func_array(array($this->mapper, $name), $arguments);        
    }

    private function getPaginator($result, $page, $itemCount)
    {
        $adapter = new ArrayAdapter($result);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($itemCount);
        return $paginator;
    }

}