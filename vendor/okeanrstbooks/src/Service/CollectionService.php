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
        $this->mapper->getAllBooks();
    }
    
    public function findAllAccountsPaginator($page, $itemCount)
    {
        $result = $this->getAllBooks();
        if ($result) {
            $adapter = new ArrayAdapter($result);
            $paginator = new Paginator($adapter);
            $paginator->setCurrentPageNumber($page);
            $paginator->setItemCountPerPage($itemCount);
            return $paginator;
        }
        return false;
    }
}