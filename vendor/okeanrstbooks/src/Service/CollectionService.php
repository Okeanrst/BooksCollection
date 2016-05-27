<?php

namespace OkeanrstBooks\Service;

use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Common\Collections\ArrayCollection;

class CollectionService
{
    protected $mapper;

    protected $imageService;

    protected $config;  
    
    public function __construct($collectionMapper, $imageService, array $config)
    {
        $this->mapper = $collectionMapper;
        $this->imageService = $imageService;
        $this->config = $config; 
    }
    
    public function getAllBooks()
    {
        return $this->mapper->getAllBooks();
    }
    
    public function getAllBooksPaginator($page = 1, $itemCount = 5)
    {
        $result = $this->getAllBooks();
        if ($result) {
            return $this->getPaginator($result, $page, $itemCount);
        }
        return false;
    }
    
    public function getBooksByRubricId($id)
    {
        return $this->mapper->getBooksByRubricId($id);
    }
    
    public function getBooksByRubricPaginator($id, $page = 1, $itemCount = 5)
    {
        $result = $this->getBooksByRubricId($id);
        if ($result) {
            return $this->getPaginator($result, $page, $itemCount);
        }
        return false;
    }
    
    public function getAllAuthorsPaginator($page = 1, $itemCount = 10)
    {
        $result = $this->mapper->getAllAuthors();
        if ($result) {
            return $this->getPaginator($result, $page, $itemCount);
        }
        return false;
    }

    public function getAllRubricsPaginator($page = 1, $itemCount = 10)
    {
        $result = $this->mapper->getAllRubrics();
        if ($result) {
            return $this->getPaginator($result, $page, $itemCount);
        }
        return false;
    }

    public function getBooksByAuthorId($id)
    {
        //$author = $this->mapper->getAuthorById($id);
        //return $this->mapper->getBooksByAuthor($author);
        return $this->mapper->getBooksByAuthorId((int) $id);
    }
    
    public function getBooksByAuthorPaginator($id, $page = 1, $itemCount = 5)
    {
        $result = $this->getBooksByAuthorId($id);
        if ($result) {
            return $this->getPaginator($result, $page, $itemCount);
        }
        return false;
    }
    
    public function getBooksByAuthorAndRubric($author_id, $rubric_id)
    {
        return $this->mapper->getBooksByAuthorAndRubric((int) $author_id, (int) $rubric_id);
    }
    
    public function getBooksByAuthorAndRubricPaginator($author_id, $rubric_id, $page = 1, $itemCount = 5)
    {
        $result = $this->getBooksByAuthorAndRubric($author_id, $rubric_id);
        if ($result) {            
            return $this->getPaginator($result, $page, $itemCount);
        }
        return false;
    }
    
    public function addBook(\OkeanrstBooks\Entity\Book $book, $data)
    {
        $photofile = new \OkeanrstBooks\Entity\Filephoto();
        $bookfile = new \OkeanrstBooks\Entity\Filebook();                   

        $pathPhoto = str_replace('\\', '/', $data['photofile']['tmp_name']);
        $pathBook = str_replace('\\', '/', $data['bookfile']['tmp_name']);
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $photoType = finfo_file($finfo, $pathPhoto);
        $photoType = ($photoType)? $photoType : $data['photofile']['type'];
        $photoType = ($photoType)? $photoType : '';
        $photofile->setMimeType($photoType);
        $bookType = finfo_file($finfo, $pathBook);
        $bookType = ($bookType)? $bookType : $data['bookfile']['type'];
        $bookType = ($bookType)? $bookType : '';        
        $bookfile->setMimeType($bookType);
        finfo_close($finfo);

        $width = $this->config['bookfoto']['size']['width'];
        $height = $this->config['bookfoto']['size']['height'];
        $this->imageService->makePreview($pathPhoto, $width, $height);

        $pathPhoto = substr($pathPhoto, stripos($pathPhoto, 'public') + 6);
        $pathBook = substr($pathBook, stripos($pathBook, 'public') + 6);

        $photofile->setPath($pathPhoto);
        $bookfile->setPath($pathBook);        

        $photofile->setSize($data['photofile']['size']);
        $bookfile->setSize($data['bookfile']['size']);

        $namePhoto = substr($pathPhoto, strripos($pathPhoto, '/')+1);
        $nameBook = substr($pathBook, strripos($pathBook, '/')+1);

        $photofile->setName($namePhoto);
        $bookfile->setName($nameBook);

        $book->setPhotofile($photofile);
        $book->setBookfile($bookfile);

        if (isset($data['photofile'])) {
            unset($data['photofile']);
        }
        if (isset($data['bookfile'])) {
            unset($data['bookfile']);
        }

        $this->hydrateBook($book, $data);

        $this->mapper->save($book);
    }

    public function editBook(\OkeanrstBooks\Entity\Book $book, $data)
    {       
        $photofile = $book->getPhotofile();
        $bookfile = $book->getBookfile();        

        if (isset($data['photofile']) && $data['photofile']['tmp_name']) {
            $pathPhoto = str_replace('\\', '/', $data['photofile']['tmp_name']);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $photoType = finfo_file($finfo, $pathPhoto);
            finfo_close($finfo);
            $this->imageService->makePreview($pathPhoto, 80, 80);
            $pathPhoto = substr($pathPhoto, stripos($pathPhoto, 'public') + 6);
            $namePhoto = substr($pathPhoto, strripos($pathPhoto, '/')+1);
            $photofile->setPath($pathPhoto);
            $photofile->setName($namePhoto);
            $photofile->setSize($data['photofile']['size']);
            $photofile->setMimeType($photoType);
            unset($data['photofile']);                      
        }

        
        if (isset($data['bookfile']) && $data['bookfile']['tmp_name']) { 
            $pathBook = str_replace('\\', '/', $data['bookfile']['tmp_name']);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $bookType = finfo_file($finfo, $pathBook);
            finfo_close($finfo);
            $pathPhoto = substr($pathBook, stripos($pathBook, 'public') + 6);
            $nameBook = substr($pathBook, strripos($pathBook, '/')+1);
            $bookfile->setPath($pathBook);
            $bookfile->setName($nameBook);
            $bookfile->setSize($data['bookfile']['size']);
            $bookfile->setMimeType($bookType);
            unset($data['bookfile']);
        }

        $this->hydrateBook($book, $data);        

        $this->mapper->save($book);        
    }

    public function deleteBook(\OkeanrstBooks\Entity\Book $book)
    {        
        $authors = $book->getAuthor();
        $collectionBooks = new ArrayCollection();
        $collectionBooks->add($book);
        foreach ($authors as $author) {            
            $author->removeBook($collectionBooks);
        }
        $this->mapper->delete($book);
    }

    public function deleteAuthor(\OkeanrstBooks\Entity\Author $author)
    {        
        $books = $author->getBook();
        $collectionAuthors = new ArrayCollection();
        $collectionAuthors->add($author);
        foreach ($books as $book) {            
            $book->removeAuthor($collectionAuthors);
        }
        $this->mapper->delete($author);
    }

    public function deleteRubric(\OkeanrstBooks\Entity\Rubric $rubric)
    {
        $rubricId = $rubric->getId();
        $books = $this->mapper->getBooksByRubricId($rubricId);
        $collectionRubrics = new ArrayCollection();
        $collectionRubrics->add($rubric);
        foreach ($books as $book) {
            $book->removeRubric($collectionRubrics);
        }
        $this->mapper->delete($rubric);
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

    private function hydrateBook(\OkeanrstBooks\Entity\Book $book, array $data)
    {
        /*$curRubricsId = [];
        $newRubricsId = [];
        foreach ($book->getRubric() as $curRubric) {
            array_push($curRubricsId, $curRubric->getId());
        }

        foreach($data['rubric'] as $key => $rubricId) {            
            $rubricId = (int) $rubricId;
            array_push($newRubricsId, $rubricId); 
            if (!in_array ($rubricId, $curRubricsId, true) ) {
                $rubric = $this->getRubricById($rubricId);
                if (is_a($rubric, 'OkeanrstBooks\Entity\Rubric')) {
                    $book->addRubric($rubric);
                } 
            }                   
        }

        foreach ($book->getRubric() as $curRubric) {
            $curRubricId = $curRubric->getId();
            if (!in_array ($curRubricId, $newRubricsId, true)) {
                $book->removeRubric($curRubric);
            }
        }        

        $curAuthorsId = [];
        $newAuthorsId = [];
        foreach($data['author'] as $key => $authorId) {            
            $authorId = (int) $authorId;
            array_push($newAuthorsId, $authorId); 
            if (!in_array ($authorId, $curAuthorsId, true) ) {
                $author = $this->getAuthorById($authorId);
                if (is_a($author, 'OkeanrstBooks\Entity\Author')) {
                    $book->addAuthor($author);
                } 
            }                   
        }

        foreach ($book->getAuthor() as $curAuthor) {
            $curAuthorId = $curAuthor->getId();
            if (!in_array ($curAuthorId, $newAuthorsId, true)) {
                $book->removeAuthor($curAuthor);
            }
        }
        
        $book->setTitle($data['title']);*/
        $objectManager = $this->mapper->getEntityManager();
        (new DoctrineHydrator($objectManager))->hydrate($data, $book);
    }

}
