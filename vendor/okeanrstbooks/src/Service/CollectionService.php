<?php

namespace OkeanrstBooks\Service;

use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;

class CollectionService
{
    protected $mapper;

    protected $imageService;   
    
    public function __construct($collectionMapper, $imageService)
    {
        $this->mapper = $collectionMapper;
        $this->imageService = $imageService;     
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

        $this->imageService->makePreview($pathPhoto, 80, 80);

        $pathPhoto = substr($pathPhoto, stripos($pathPhoto, 'public') + 7);
        $pathBook = substr($pathBook, stripos($pathBook, 'public') + 7);

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

        $this->mapper->save($book);
    }

    public function editBook(\OkeanrstBooks\Entity\Book $book, $data)
    {
        /*$photofile = $book->getPhotofile();
        $bookfile = $book->getBookfile();

        $pathPhoto = str_replace('\\', '/', $data['photofile']['tmp_name']);
        $pathBook = str_replace('\\', '/', $data['bookfile']['tmp_name']);

        $namePhoto = substr($pathPhoto, strripos($pathPhoto, '/')+1);
        $nameBook = substr($pathBook, strripos($pathBook, '/')+1);

        $curNamePhoto = $data['curphotofile']['name'];
        $curNameBook = $data['curbookfile']['name'];

        $curPathPhoto = './public/'.$data['curphotofile']['path'];
        $curPathBook = './public/'.$data['curbookfile']['path'];

        $curSizePhoto = $data['curphotofile']['size'];

        if ($photofile->getSize() !== $data['photofile']['size'] && 
            isset($namePhoto) &&  $curNamePhoto !== $namePhoto) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $photoType = finfo_file($finfo, $pathPhoto);
            $photoType = ($photoType)? $photoType : $data['photofile']['type'];
            $photoType = ($photoType)? $photoType : '';
            $photofile->setMimeType($photoType);
            finfo_close($finfo);
            $this->imageService->makePreview($pathPhoto, 80, 80);
            unlink($curPathPhoto);
            $pathPhoto = substr($pathPhoto, stripos($pathPhoto, 'public') + 7);
            $photofile->setPath($pathPhoto);
            $photofile->setName($namePhoto);
            $photofile->setSize($data['photofile']['size']);
        }

        if ($bookfile->getSize() !== $data['bookfile']['size'] && 
            isset($nameBook) &&  $curNameBook !== $nameBook) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $bookType = finfo_file($finfo, $pathBook);
            $bookType = ($bookType)? $bookType : $data['bookfile']['type'];
            $bookType = ($bookType)? $bookType : '';
            $bookfile->setMimeType($bookType);
            finfo_close($finfo);            
            unlink($curPathBook);
            $pathPhoto = substr($pathBook, stripos($pathBook, 'public') + 7);
            $bookfile->setPath($pathBook);
            $bookfile->setName($nameBook);
            $bookfile->setSize($data['bookfile']['size']);
        }

        $this->mapper->save($book);*/
        $curPathPhoto = './public/'.$data['curphotofile']['path'];
        $curPathBook = './public/'.$data['curbookfile']['path'];

        $this->addBook($book, $data);
        unlink($curPathPhoto);
        unlink($curPathBook);
    }

    public function deleteBook(\OkeanrstBooks\Entity\Book $book)
    {

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