<?php

namespace OkeanrstBooks\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Zend\Form\FormInterface;
use Doctrine\ORM\EntityManager;
use OkeanrstBooks\Entity\Book;
use OkeanrstBooks\Entity\Author;
use OkeanrstBooks\Entity\Rubric;
use OkeanrstBooks\Entity\File;
use OkeanrstBooks\Form\BookForm;
use OkeanrstBooks\Form\AuthorForm;
use OkeanrstBooks\Form\RubricForm;

class CollectionController extends AbstractActionController
{
    protected $collection;
    
    protected $em;    

    public function __construct($collectionService, EntityManager $entityManager)
    {
        $this->collection = $collectionService;
        $this->em = $entityManager;        
    }    
    
    
    //показываем постранично список всех книг, ссылки на просмотр только автора, рубрики. В шаблоне проверяем привиллегии-выводим ссылку для добавления книги.
    public function collectionAction()
    {
        $books = $this->collection->getAllBooksPaginator((int)$this->params()->fromQuery('page', 1), 10);        
        $bookForm = new BookForm($this->em);
        if ($books) {
                        
            return new ViewModel(array('collection' => $books, 'bookForm' => $bookForm));
        } else {
            //$this->flashMessenger()->addErrorMessage('');
            //$this->layout()->setTemplate('layout/layout');
            //return $this->redirect()->toRoute('books');
            return new ViewModel(['bookForm' => $bookForm]);
        }        
    }
    
    //информация по конкретной книге с возможностью просмотра, при наличии приввилегий ссылки на редактирование-форма с заполненными полями 
    public function bookAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('books/collection');
        }         
        $result = $this->collection->getBookById($id);
        if ($result) {
            $view =  new ViewModel();
            $view->entity = $result;        
            return $view;
        }
        return $this->redirect()->toRoute('books/collection'); 
    }    
    
    public function getBooksByAuthorAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('books/collection');
        }
        $author = $this->collection->getAuthorById($id);
        $page = $this->params()->fromRoute('page');
        $page = $page ? $page: 1;
        $itemCount = $this->params()->fromRoute('itemCount');
        $itemCount = $itemCount ? $itemCount : 10;
        $result = $this->collection->getBooksByAuthorPaginator($id, $page, $itemCount);
        if ($result) {
            $bookForm = new BookForm($this->em);
            $view =  new ViewModel();
            $view->collection = $result;
            $view->author = $author;
            $view->bookForm = $bookForm;       
            return $view;
        }
        return $this->redirect()->toRoute('books/collection');
    }

    public function getBooksByRubricAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('books/collection');
        }
        $rubric = $this->collection->getRubricById($id);
        $page = $this->params()->fromRoute('page');
        $page = $page ? $page: 1;
        $itemCount = $this->params()->fromRoute('itemCount');
        $itemCount = $itemCount ? $itemCount : 10;
        $result = $this->collection->getBooksByAuthorPaginator($id, $page, $itemCount);
        if ($result) {
            $bookForm = new BookForm($this->em);
            $view =  new ViewModel();
            $view->collection = $result;
            $view->rubric = $rubric;
            $view->bookForm = $bookForm;        
            return $view;
        }
        return $this->redirect()->toRoute('books/collection');
    }
    
    public function authorsAction()
    {
        $page = $this->params()->fromRoute('page');
        $page = $page ? $page: 1;
        $itemCount = $this->params()->fromRoute('itemCount');
        $itemCount = $itemCount ? $itemCount : 10;
        $result = $this->collection->getAllAuthorsPaginator($page, $itemCount);
        $view =  new ViewModel();
        if ($result) {                        
            $view->collection = $result;            
        }
        $view->authorForm = new AuthorForm($this->em);
        return $view;
    }

    public function rubricsAction()
    {
        $page = $this->params()->fromRoute('page');
        $page = $page ? $page: 1;
        $itemCount = $this->params()->fromRoute('itemCount');
        $itemCount = $itemCount ? $itemCount : 10;
        $result = $this->collection->getAllRubricsPaginator($page, $itemCount);
        $view =  new ViewModel();
        if ($result) {            
            $view->collection = $result;                 
        }
        $view->rubricForm = new RubricForm($this->em);
        return $view;
    }    
    
    public function newBookAction()
    {
        $this->checkAccess();               
        $view =  new ViewModel();
        $form = new BookForm($this->em);         
        if ($this->getRequest()->isPost()) {             
            $book = new Book();
            //$form->bind($book);
            $form->setBindOnValidate(FormInterface::BIND_MANUAL);            
            $request = $this->getRequest();
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($post);          
            if ($form->isValid()) {    
                $data = $form->getData();
                $form->setObject(new Book());
                $form->bind($book);
                $form->bindValues();                
                $this->collection->addBook($book, $data);                
                $this->flashMessenger()->addSuccessMessage('Book has been successfully added');         
                return $this->redirect()->toRoute('books/newbook');                
            } else {
                $view->form = $form;      
                return $view;               
            }            
        }        
        $submit = $form->get('submit');
        $submit->setValue('Add book');
        $view->form = $form;      
        return $view;
    }

    public function ajaxNewBookAction()
    {
        $response = $this->getResponse();
        if(!$this->ajaxCheckAccess()) {
            return $response->setContent(Json::encode(array('error' => 'Error. Access is denied!')));
        }                
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {            
            $form = new BookForm($this->em);
            $book = new Book();               
            //$form->bind($book);
            $form->setBindOnValidate(FormInterface::BIND_MANUAL);
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($post);
            if ($form->isValid()) {    
                $data = $form->getData();
                $form->setObject(new Book());
                $form->bind($book);
                $form->bindValues();                
                $this->collection->addBook($book, $data);                
                return $response->setContent(Json::encode(array('success' => 'Author has been edded')));                                              
            }
            return $response->setContent(Json::encode(['error' => 'Data is not valid']));                                
        }        
        return $response->setContent(Json::encode(array('error' => 'Request mast be a post')));
    }

    public function editBookAction()
    {
        $this->checkAccess();
        $view =  new ViewModel();
        $form = new BookForm($this->em);         
        if ($this->getRequest()->isPost()) {          
            $id = $request->getPost('id');
            if (!$id) {
            $this->flashMessenger()->addErrorMessage('Id not found in request');
                return $this->redirect()->toRoute('books/collection');
            }
            $book = $this->collection->getBookById($id);
            if (!$book) {
                $this->flashMessenger()->addErrorMessage('Book with this id not found');
                return $this->redirect()->toRoute('books/collection');
            }
            //$form->bind($book);
            $form->setBindOnValidate(FormInterface::BIND_MANUAL);            
            $request = $this->getRequest();
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($post);          
            if ($form->isValid()) {    
                $data = $form->getData();
                $data['curphotofile'] = $book->getPhotofile()->getArrayCopy();
                $data['curbookfile'] = $book->getBookfile()->getArrayCopy();                
                $form->setObject(new Book());
                $form->bind($book);
                $form->bindValues();                
                $this->collection->editBook($book, $data);                
                $this->flashMessenger()->addSuccessMessage('Book has been edit');         
                return $this->redirect()->toRoute('books/newbook');                
            } else {
                $this->flashMessenger()->addErrorMessage('Book with this id not found');
                return $this->redirect()->toRoute('books/editbook', ['id' => $id]);                
            }            
        }        
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Id not found in request');
            return $this->redirect()->toRoute('books/collection');
        }
        $book = $this->collection->getBookById($id);
        if (!$book) {
            $this->flashMessenger()->addErrorMessage('Book with this id not found');
            return $this->redirect()->toRoute('books/collection');
        }
        $submit = $form->get('submit');
        $submit->setValue('Edit book');
        $form->bind($book);        
        $view->form = $form;      
        return $view;    
    }

    public function ajaxEditBookAction()
    {
        $response = $this->getResponse();
        if(!$this->ajaxCheckAccess()) {
            return $response->setContent(Json::encode(array('error' => 'Error. Access is denied!')));
        }                
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {            
            $post = $this->getRequest()->getPost();
            $id = $request->getPost('id');            
            $form = new BookForm($this->em);            
            if (!$id) {                
                return $response->setContent(Json::encode(['error' => 'Id not found in request']));
            }
            $book = $this->collection->getBookById($id);
            if (!$book) {                
                return $response->setContent(Json::encode(['error' => 'Book with this id not found']));
            }
            $form->setBindOnValidate(FormInterface::BIND_MANUAL);            
            $request = $this->getRequest();
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($post);          
            if ($form->isValid()) {    
                $data = $form->getData();
                $data['curphotofile'] = $book->getPhotofile()->getArrayCopy();
                $data['curbookfile'] = $book->getBookfile()->getArrayCopy();                
                $form->setObject(new Book());
                $form->bind($book);
                $form->bindValues();                
                $this->collection->editBook($book, $data);                
                return $response->setContent(Json::encode(array('success' => 'Book has been editing')));
            } else {
                $form->bind($book);
                $formData = ['id' => $form->get('id')->getValue(),
                    'title' => $form->get('title')->getValue(),
                    'author' => $form->get('author')->getValue(),
                    'rubric[]' => $form->get('rubric')->getValue()
                ];
                return $response->setContent(Json::encode(['success' => $formData]));
            }                
        }        
        return $response->setContent(Json::encode(array('error' => 'Request mast be a post')));
    }

    public function deleteBookAction()
    {
        $this->checkAccess();        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $book = $this->collection->getBookById($id);
                if ($book) {                   
                    $this->collection->delete($book);                    
                    $this->flashMessenger()->addSuccessMessage('Book has been deleted');
                    return $this->redirect()->toRoute('books/collection');                    
                } else {
                    $this->flashMessenger()->addErrorMessage('Error. Book not found!');
                    return $this->redirect()->toRoute('books/collection');
                }
            }            
            return $this->redirect()->toRoute('books/collection');
        }        
        $id = (int) $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('books/collection');
        }
        $book = $this->collection->getBookById($id);
        if (!$book) {
            return $this->redirect()->toRoute('books/collection');
        }
        $view =  new ViewModel();
        $view->book = $book;       
        return $view;
    }

    public function ajaxDeleteBookAction()
    {
        $response = $this->getResponse();
        if(!$this->ajaxCheckAccess()) {
            return $response->setContent(Json::encode(array('error' => 'Error. Access is denied!')));
        }                
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {            
            $id = (int) $request->getPost('id');            
            if (!$id) {
                return $response->setContent(Json::encode(array('error' => 'Id not found in request')));
            }
            $book = $this->collection->getBookById($id);
            if ($book) {                   
                $this->collection->deleteBook($book);                                        
                return $response->setContent(Json::encode(array('success' => 'Book has been deleted')));
            } else {
                return $response->setContent(Json::encode(array('error' => 'Book not found')));
            }            
        }        
        return $response->setContent(Json::encode(array('error' => 'Request mast be a post')));
    }
    
    public function newAuthorAction()
    {
        $this->checkAccess();
        $view =  new ViewModel();                
        if ($this->getRequest()->isPost()) {
            $form = new AuthorForm($this->em);
            $entity = new Author();       
            $form->bind($entity);
            $submit = $form->get('submit');
            $submit->setValue('Add author');
            $post = $this->getRequest()->getPost();
            $form->setData($post);          
            if ($form->isValid()) {                        
                $this->collection->addAuthor($entity);                
                $this->flashMessenger()->addSuccessMessage('Author has been successfully added');
                return $this->redirect()->toRoute('books/newauthor');            
            } else {               
                $view->form = $form;        
                return $view;
            }            
        }        
        $form = new AuthorForm($this->em);
        $submit = $form->get('submit');
        $submit->setValue('Add author');
        $view->form = $form;
        return $view;
    }

    public function ajaxNewAuthorAction()
    {
        $response = $this->getResponse();
        if(!$this->ajaxCheckAccess()) {
            return $response->setContent(Json::encode(array('error' => 'Error. Access is denied!')));
        }                
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {            
            $post = $this->getRequest()->getPost();            
            $form = new AuthorForm($this->em);
            $author = new Author();            
            $form->bind($author);
            $form->setData($post);          
            if ($form->isValid()) {
                $this->collection->save($author);                                       
                return $response->setContent(Json::encode(array('success' => 'Author has been edded')));
            } else {                    
                $formData = [
                    'lastName' => $form->get('lastName')->getValue(),
                    'name' => $form->get('name')->getValue()
                ];
                return $response->setContent(Json::encode($formData));                    
            }                     
        }        
        return $response->setContent(Json::encode(array('error' => 'Request mast be a post')));
    }
    
    public function editAuthorAction()
    {
        $this->checkAccess();
        $id = (int) $this->params()->fromRoute('id');
        $form = new AuthorForm($this->em);
        if ($id) {
            $author = $this->collection->getAuthorById($id);
            if (!$author) {
                return $this->redirect()->toRoute('books/authors');
            }
            $form->bind($author);
            $view =  new ViewModel();
            $view->form = $form;        
            return $view;
        }       
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $id = (int) $post->get('id');            
            $author = $this->collection->getAuthorById($id);
            $form->bind($author);
            $form->setData($post);          
            if ($form->isValid()) {
                $this->collection->save($author);
                $this->flashMessenger()->addSuccessMessage('Athor has been successfully editing');                   
                return $this->redirect()->toRoute('books/authors');                
            } else {
                $this->flashMessenger()->addErrorMessage('Error editing author. Data is not valid');                
            }
        }
        return $this->redirect()->toRoute('books/authors');
    }

    public function ajaxEditAuthorAction()
    {
        $response = $this->getResponse();
        if(!$this->ajaxCheckAccess()) {
            return $response->setContent(Json::encode(array('error' => 'Error. Access is denied!')));
        }                
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {            
            $post = $this->getRequest()->getPost();
            $id = (int) $request->getPost('id');            
            $form = new AuthorForm($this->em);            
            if (!$id) {                
                return $response->setContent(Json::encode(['error' => 'id not found']));
            }
            $author = $this->collection->getAuthorById($id);
            if ($author) {          
                $form->bind($author);
                $form->setData($post);          
                if ($form->isValid()) {
                    $this->collection->save($author);                                       
                    return $response->setContent(Json::encode(array('success' => 'Author has been editing')));
                } else {                    
                    $formData = ['id' => $form->get('id')->getValue(),
                        'lastName' => $form->get('lastName')->getValue(),
                        'name' => $form->get('name')->getValue()
                    ];
                    return $response->setContent(Json::encode(['success' => $formData]));                    
                }          
                
            } else {
                return $response->setContent(Json::encode(['error' => 'author not found']));
            }            
        }        
        return $response->setContent(Json::encode(array('error' => 'Request mast be a post')));
    }
    
    public function deleteAuthorAction()
    {
        $this->checkAccess();        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $author = $this->collection->getAuthorById($id);
                if ($author) {                   
                    $this->collection->delete($author);                    
                    $this->flashMessenger()->addSuccessMessage('Author has been deleted');
                    return $this->redirect()->toRoute('books/authors');                    
                } else {
                    $this->flashMessenger()->addErrorMessage('Error. Athor not found!');
                    return $this->redirect()->toRoute('books/authors');
                }
            }
            
            return $this->redirect()->toRoute('books/authors');
        }        
        $id = (int) $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('books/authors');
        }
        $author = $this->collection->getAuthorById($id);
        if (!$author) {
            return $this->redirect()->toRoute('books/authors');
        }
        $view =  new ViewModel();
        $view->author = $author;       
        return $view;
    }

    public function ajaxDeleteAuthorAction()
    {
        $response = $this->getResponse();
        if(!$this->ajaxCheckAccess()) {
            return $response->setContent(Json::encode(array('error' => 'Error. Access is denied!')));
        }                
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {            
            $id = (int) $request->getPost('id');            
            if (!$id) {
                return $response->setContent(Json::encode(array('error' => 'Id not found in request')));
            }
            $author = $this->collection->getAuthorById($id);
            if ($author) {                   
                $this->collection->delete($author);                                        
                return $response->setContent(Json::encode(array('success' => 'Author has been deleted')));
            } else {
                return $response->setContent(Json::encode(array('error' => 'Author not found')));
            }            
        }        
        return $response->setContent(Json::encode(array('error' => 'Request mast be a post')));        
    }
    
    public function newRubricAction()
    {
        $this->checkAccess();                
        $view =  new ViewModel();
        if ($this->getRequest()->isPost()) {
            $form = new RubricForm($this->em);
            $entity = new Rubric();       
            $form->bind($entity);
            $submit = $form->get('submit');
            $submit->setValue('Add rubric');
            $post = $this->getRequest()->getPost();
            $form->setData($post);          
            if ($form->isValid()) {                        
                $this->collection->addRubric($entity);                
                $this->flashMessenger()->addSuccessMessage('Rubric has been successfully added');
                return $this->redirect()->toRoute('books/newrubric');            
            } else {              
                $view->form = $form;        
                return $view;
            }            
        }        
        $form = new RubricForm($this->em);
        $submit = $form->get('submit');
        $submit->setValue('Add rubric');
        $view->form = $form;
        return $view;
    }

    public function ajaxNewRubricAction()
    {
        $response = $this->getResponse();
        if(!$this->ajaxCheckAccess()) {
            return $response->setContent(Json::encode(array('error' => 'Error. Access is denied!')));
        }                
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {            
            $post = $this->getRequest()->getPost();            
            $form = new RubricForm($this->em);
            $rubric = new Rubric();            
            $form->bind($rubric);
            $form->setData($post);          
            if ($form->isValid()) {
                $this->collection->save($rubric);                                       
                return $response->setContent(Json::encode(array('success' => 'Rubric has been added')));
            } else {                    
                $formData = [
                    'Title' => $form->get('title')->getValue()
                ];
                return $response->setContent(Json::encode($formData));                    
            }                     
        }        
        return $response->setContent(Json::encode(array('error' => 'Request mast be a post')));

    }
    
    public function editRubricAction()
    {
        $this->checkAccess();
        $id = (int) $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('books/authors');
        }
        $author = $this->collection->getAuthorById($id);
        if (!$author) {
            return $this->redirect()->toRoute('books/authors');
        }
        $form = new AuthorForm($this->em);
        $form->bind($author);
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);          
            if ($form->isValid()) {
                $id = (int) $form->get('id')->getValue();
                $result = $this->collection->getAuthorById($id);
                if ($result) {
                    $this->collection->save($author);
                    $this->flashMessenger()->addSuccessMessage('Athor has been successfully editing');                   
                    return $this->redirect()->toRoute('books/authors');
                } else {
                    $this->flashMessenger()->addErrorMessage('Error editing author');
                    return $this->redirect()->toRoute('books/authors');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('Error editing author. Data is not valid');                
            }
        }
        $view =  new ViewModel();
        $view->form = $form;        
        return $view;

    }

    public function ajaxEditRubricAction()
    {
        $response = $this->getResponse();
        if(!$this->ajaxCheckAccess()) {
            return $response->setContent(Json::encode(array('error' => 'Error. Access is denied!')));
        }                
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {            
            $post = $this->getRequest()->getPost();
            $id = (int) $request->getPost('id');            
            $form = new RubricForm($this->em);            
            if (!$id) {                
                return $response->setContent(Json::encode(['error' => 'Id not found']));
            }
            $rubric = $this->collection->getRubricById($id);
            if ($rubric) {          
                $form->bind($rubric);
                $form->setData($post);          
                if ($form->isValid()) {
                    $this->collection->save($rubric);                                       
                    return $response->setContent(Json::encode(array('success' => 'Rubric has been editing')));
                } else {                    
                    $formData = ['id' => $form->get('id')->getValue(),
                        'title' => $form->get('title')->getValue()                        
                    ];
                    return $response->setContent(Json::encode(['success' => $formData]));                    
                }                
            } else {
                return $response->setContent(Json::encode(['error' => 'Rubric not found']));
            }            
        }        
        return $response->setContent(Json::encode(array('error' => 'Request mast be a post')));
    }
    
    public function deleteRubricAction()
    {
        $this->checkAccess();        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $rubric = $this->collection->getRubricById($id);
                if ($rubric) {                   
                    $this->collection->delete($rubric);                    
                    $this->flashMessenger()->addSuccessMessage('Rubric has been deleted');
                    return $this->redirect()->toRoute('books/rubrics');                    
                } else {
                    $this->flashMessenger()->addErrorMessage('Error. Rubric not found!');
                    return $this->redirect()->toRoute('books/rubrics');    
                }
            }
            
            return $this->redirect()->toRoute('books/rubrics');
        }        
        $id = (int) $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('books/rubrics');
        }
        $rubric = $this->collection->getRubricById($id);
        if (!$rubric) {
            return $this->redirect()->toRoute('books/rubrics');
        }
        $view =  new ViewModel();
        $view->rubric = $rubric;       
        return $view;
    }

    public function ajaxDeleteRubricAction()
    {
        $response = $this->getResponse();
        if(!$this->ajaxCheckAccess()) {
            return $response->setContent(Json::encode(array('error' => 'Error. Access is denied!')));
        }                
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {            
            $id = (int) $request->getPost('id');            
            if (!$id) {
                return $response->setContent(Json::encode(array('error' => 'Id not found in request')));
            }
            $rubric = $this->collection->getRubricById($id);
            if ($rubric) {                   
                $this->collection->delete($rubric);                                        
                return $response->setContent(Json::encode(array('success' => 'Rubric has been deleted')));
            } else {
                return $response->setContent(Json::encode(array('error' => 'Rubric not found')));
            }            
        }        
        return $response->setContent(Json::encode(array('error' => 'Request mast be a post')));
    }

    private function checkAccess()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('Error. Access is denied!');
            return $this->redirect()->toRoute('books/collection');            
        }
    }

    private function ajaxCheckAccess()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {            
            return false;                        
        }
        return true;
    }
}