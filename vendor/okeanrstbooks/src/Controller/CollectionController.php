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

    protected $viewHelper;  

    public function __construct($collectionService, EntityManager $entityManager, $viewHelperManager)
    {
        $this->collection = $collectionService;
        $this->em = $entityManager;
        $this->viewHelperManager = $viewHelperManager;        
    }    
    
    public function indexAction()
    {
        return $this->redirect()->toRoute('books/collection');
    }
    
    public function collectionAction()
    {
        $page = $this->params()->fromRoute('page', 1);
        $itemCount = $this->params()->fromRoute('itemCount', 3);
        $books = $this->collection->getAllBooksPaginator($page , $itemCount);        
        $bookForm = new BookForm($this->em);
        $bookForm->get('photofile')->setAttribute('required', '');
        $bookForm->get('bookfile')->setAttribute('required', '');
        if ($books) {                        
            return new ViewModel(array('collection' => $books, 'bookForm' => $bookForm));
        } else {            
            return new ViewModel(['bookForm' => $bookForm]);
        }        
    }    
     
    public function bookAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Id not found in request');
            return $this->redirect()->toRoute('books/collection');
        }         
        $book = $this->collection->getBookById($id);
        if ($book) {
            $view =  new ViewModel();
            $bookForm = new BookForm($this->em);
            $bookForm->get('photofile')->setAttribute('required', '');
            $bookForm->get('bookfile')->setAttribute('required', '');
            $view->bookForm = $bookForm;
            $view->entity = $book;        
            return $view;
        }
        $this->flashMessenger()->addErrorMessage('Rubric with this id not found');
        return $this->redirect()->toRoute('books/collection'); 
    }    
    
    public function getBooksByAuthorAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Id not found in request');
            return $this->redirect()->toRoute('books/authors');
        }
        $author = $this->collection->getAuthorById($id);
        if (!$author) {
            $this->flashMessenger()->addErrorMessage('Author with this id not found');
            return $this->redirect()->toRoute('books/authors');
        }
        $page = $this->params()->fromRoute('page');
        $page = $page ? $page: 1;
        $itemCount = $this->params()->fromRoute('itemCount');
        $itemCount = $itemCount ? $itemCount : 5;
        $result = $this->collection->getBooksByAuthorPaginator($id, $page, $itemCount);
        if ($result) {
            $bookForm = new BookForm($this->em);
            $bookForm->get('photofile')->setAttribute('required', '');
            $bookForm->get('bookfile')->setAttribute('required', '');
            $view =  new ViewModel();
            $view->collection = $result;
            $view->author = $author;
            $view->bookForm = $bookForm;       
            return $view;
        }
        return $this->redirect()->toRoute('books/authors');
    }

    public function getBooksByRubricAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Id not found in request');
            return $this->redirect()->toRoute('books/rubrics');
        }
        $rubric = $this->collection->getRubricById($id);
        if (!$rubric) {
            $this->flashMessenger()->addErrorMessage('Rubric with this id not found');
            return $this->redirect()->toRoute('books/rubrics');
        }
        $page = $this->params()->fromRoute('page');
        $page = $page ? $page: 1;
        $itemCount = $this->params()->fromRoute('itemCount');
        $itemCount = $itemCount ? $itemCount : 5;
        $result = $this->collection->getBooksByRubricPaginator($id, $page, $itemCount);
        if ($result) {
            $bookForm = new BookForm($this->em);
            $bookForm->get('photofile')->setAttribute('required', '');
            $bookForm->get('bookfile')->setAttribute('required', '');
            $view =  new ViewModel();
            $view->collection = $result;
            $view->rubric = $rubric;
            $view->bookForm = $bookForm;        
            return $view;
        }
        $view =  new ViewModel();
        $bookForm = new BookForm($this->em);
        $view->rubric = $rubric;
        $view->bookForm = $bookForm;
        return $view;        
    }
    
    public function authorsAction()
    {
        $page = $this->params()->fromRoute('page', 1);        
        $itemCount = $this->params()->fromRoute('itemCount', 10);        
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
        $page = $this->params()->fromRoute('page', 1);        
        $itemCount = $this->params()->fromRoute('itemCount', 10);        
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
        $message = ['success' => '', 'error' => ''];
        $request = $this->getRequest();
        if ($request->isPost()) {             
            $form = new BookForm($this->em);
            $submit = $form->get('submit');
            $submit->setValue('Add book');            
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($post);          
            if ($form->isValid()) {    
                $data = $form->getData();
                $book = new Book();
                $this->collection->addBook($book, $data);                
                $message['success'] = 'Book has been successfully added';
            } else {
                $data = $form->getData();
                $this->deleteInvalidFile($data);
                $message['error'] = 'Data is not valid';
                $view->message = $message;
                $view->form = $form;                
                return $view;               
            }            
        }
        $form = new BookForm($this->em);        
        $submit = $form->get('submit');
        $submit->setValue('Add book');
        $view->message = $message;
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
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($post);
            if ($form->isValid()) {    
                $data = $form->getData();                           
                $book = new Book();
                $this->collection->addBook($book, $data);                
                $data = $this->prepareBookLine($book);
                return $response->setContent(Json::encode(['success' => 'Author has been edded', 'data' => $data]));                                              
            }
            $data = $form->getData();
            $this->deleteInvalidFile($data);
            return $response->setContent(Json::encode(['error' => 'Data is not valid']));                                
        }        
        return $response->setContent(Json::encode(array('error' => 'Request mast be a post')));
    }

    public function editBookAction()
    {
        $this->checkAccess();
        $view =  new ViewModel();
        $form = new BookForm($this->em);
        $request = $this->getRequest();         
        if ($request->isPost()) {          
            $id = (int) $this->getRequest()->getPost('id');
            if (!$id) {
            $this->flashMessenger()->addErrorMessage('Id not found in request');
                return $this->redirect()->toRoute('books/collection');
            }
            $book = $this->collection->getBookById($id);
            if (!$book) {
                $this->flashMessenger()->addErrorMessage('Book with this id not found');
                return $this->redirect()->toRoute('books/collection');
            }             
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($post);
            if (!isset($post['photofile']['tmp_name']) || !$post['photofile']['tmp_name']) {
                $form->remove('photofile');
                $form->getInputFilter()->remove('photofile');

            }
            if (!isset($post['bookfile']['tmp_name']) || !$post['bookfile']['tmp_name']) {
                $form->remove('bookfile');            
                $form->getInputFilter()->remove('bookfile');
            }

            if ($form->isValid()) {    
                $data = $form->getData();                
                $this->collection->editBook($book, $data);                
                $this->flashMessenger()->addSuccessMessage('Book has been edit');         
                return $this->redirect()->toRoute('books/collection');                
            } else {
                $data = $form->getData();
                $this->deleteInvalidFile($data);
                $this->flashMessenger()->addErrorMessage('Data is not valid');
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
        $form->setObject(new Book());                
        $form->get('photofile')->setAttribute('required', '');
        $form->get('bookfile')->setAttribute('required', '');
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
            $id = (int) $request->getPost('id');            
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
            if (!isset($post['photofile']['tmp_name']) || !$post['photofile']['tmp_name']) {
                $form->remove('photofile');
                $form->getInputFilter()->remove('photofile');

            }
            if (!isset($post['bookfile']['tmp_name']) || !$post['bookfile']['tmp_name']) {
                $form->remove('bookfile');            
                $form->getInputFilter()->remove('bookfile');
            }
            $form->setData($post);          
            if ($form->isValid()) {    
                $data = $form->getData();                
                //$form->setObject(new Book());
                //$form->bind($book);
                //$form->bindValues();                
                $this->collection->editBook($book, $data);
                $data = $this->prepareBookLine($book);               
                return $response->setContent(Json::encode(['success' => 'Book has been editing', 'data' => $data]));
            } else {
                $data = $form->getData();
                $this->deleteInvalidFile($data);
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
                    $this->collection->deleteBook($book);                    
                    $this->flashMessenger()->addSuccessMessage('Book has been deleted');
                    return $this->redirect()->toRoute('books/collection');                    
                } else {
                    $this->flashMessenger()->addErrorMessage('Error. Book not found!');
                    return $this->redirect()->toRoute('books/collection');
                }
            }            
            return $this->redirect()->toRoute('books/collection');
        }        
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Id not found in request!');
            return $this->redirect()->toRoute('books/collection');
        }
        $book = $this->collection->getBookById($id);
        if (!$book) {
            $this->flashMessenger()->addErrorMessage('Error. Book not found!');
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
        $message = ['success' => '', 'error' => ''];                
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
                $message['success'] = 'Author has been successfully added';
            } else {               
                $message['error'] = 'Data is not valid';
                $view->message = $message;
                $view->form = $form;        
                return $view;
            }            
        }        
        $form = new AuthorForm($this->em);
        $submit = $form->get('submit');
        $submit->setValue('Add author');
        $view->form = $form;
        $view->message = $message;
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
                $data = $this->prepareAuthorLine($author);                                      
                return $response->setContent(Json::encode(['success' => 'Author has been edded', 'data' => $data]));
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
        $view =  new ViewModel();
        $form = new AuthorForm($this->em);
        if ($this->getRequest()->isPost()) {
            $id = (int) $this->getRequest()->getPost('id');
            if (!$id) {
            $this->flashMessenger()->addErrorMessage('Id not found in request');
                return $this->redirect()->toRoute('books/authors');
            }
            $author = $this->collection->getAuthorById($id);
            if (!$author) {
                $this->flashMessenger()->addErrorMessage('Author with this id not found');
                return $this->redirect()->toRoute('books/authors');
            }
            $post = $this->getRequest()->getPost();
            $form->bind($author);
            $form->setData($post);          
            if ($form->isValid()) {
                $this->collection->save($author);
                $this->flashMessenger()->addSuccessMessage('Athor has been successfully editing');                   
                return $this->redirect()->toRoute('books/authors');                
            } else {
                $this->flashMessenger()->addErrorMessage('Error editing author. Data is not valid');
                return $this->redirect()->toRoute('books/editauthor', ['id' => $id]);                
            }
        }
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Id not found in request');
            return $this->redirect()->toRoute('books/authors');
        }
        $author = $this->collection->getAuthorById($id);
        if (!$author) {
            $this->flashMessenger()->addErrorMessage('Book with this id not found');
            return $this->redirect()->toRoute('books/authors');
        }        
        $form->bind($author);
        $submit = $form->get('submit');
        $submit->setValue('Edit author');                
        $view->form = $form;      
        return $view;
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
                    $data = $this->prepareAuthorLine($author);                                       
                    return $response->setContent(Json::encode(['success' => 'Author has been editing', 'data' => $data]));
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
                    $this->collection->deleteAuthor($author);                    
                    $this->flashMessenger()->addSuccessMessage('Author has been deleted');
                    return $this->redirect()->toRoute('books/authors');                    
                } else {
                    $this->flashMessenger()->addErrorMessage('Error. Athor not found!');
                    return $this->redirect()->toRoute('books/authors');
                }
            }
            
            return $this->redirect()->toRoute('books/authors');
        }        
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Id not found in request!');
            return $this->redirect()->toRoute('books/authors');
        }
        $author = $this->collection->getAuthorById($id);
        if (!$author) {
            $this->flashMessenger()->addErrorMessage('Error. Athor not found!');
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
                $this->collection->deleteAuthor($author);                                        
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
        $message = ['success' => '', 'error' => ''];
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
                $message['success'] = 'Rubric has been successfully added';                
            } else {              
                $message['error'] = 'Data is not valid';
                $view->message = $message;
                $view->form = $form;        
                return $view;
            }            
        }        
        $form = new RubricForm($this->em);
        $submit = $form->get('submit');
        $submit->setValue('Add rubric');
        $view->form = $form;
        $view->message = $message;
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
                $data = $this->prepareRubricLine($rubric);
                return $response->setContent(Json::encode(['success' => 'Rubric has been added', 'data' => $data]));
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
                    $data = $this->prepareRubricLine($rubric);                                      
                    return $response->setContent(Json::encode(['success' => 'Rubric has been editing', 'data' => $data]));
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
                    $this->collection->deleteRubric($rubric);                    
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
                $this->collection->deleteRubric($rubric);                                        
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

    private function prepareBookLine($book)
    {
        $urlHelper = $this->viewHelperManager->get('url');
        $data = [];
        $data['id'] = $book->getId();
        $data['title'] = ['href' => $urlHelper('books/book', ['id' => $book->getId()]), 'title' => $book->getTitle()];        
        $author = $book->getAuthor()->getLastName().' '.$book->getAuthor()->getName();
        $data['author'] = ['href' => $urlHelper('books/getbooksbyauthor', ['id' => $book->getId()]), 'value' => $author];
        $rubric = [];
        foreach ($book->getRubric() as $rub) {
            array_push($rubric, ['href' => $urlHelper('books/rubrics', ['id' => $rub->getId()]), 'value' => $rub->getTitle().', ']);
        }
        $data['rubric'] = $rubric;
        $data['img'] = $book->getPhotofile()->getPath();
        $data['view'] = ['href' => $book->getBookfile()->getPath(), 'type' => $book->getBookfile()->getMimeType()];
        $data['edit'] = $urlHelper('books/editbook', ['id' => $book->getId()]);
        $data['del'] = $urlHelper('books/deletebook', ['id' => $book->getId()]);
        return $data;
    }

    private function prepareAuthorLine($author)
    {
        $data = [];
        $data['id'] = $author->getId();
        $urlHelper = $this->viewHelperManager->get('url');        
        $data['author'] = ['href' => $urlHelper('books/getbooksbyauthor', ['id' => $author->getId()]), 'value' => $author->getLastName()];
        $data['name'] = $author->getName();
        $data['edit'] = $urlHelper('books/editauthor', ['id' => $author->getId()]);
        $data['del'] = $urlHelper('books/deleteauthor', ['id' => $author->getId()]);
        return $data;
    }

    private function prepareRubricLine($rubric)
    {
        $data = [];
        $data['id'] = $rubric->getId();
        $urlHelper = $this->viewHelperManager->get('url');        
        $data['rubric'] = ['href' => $urlHelper('books/getbooksbyrubric', ['id' => $rubric->getId()]), 'value' => $rubric->getTitle()];        
        $data['edit'] = $urlHelper('books/editrubric', ['id' => $rubric->getId()]);
        $data['del'] = $urlHelper('books/deleterubric', ['id' => $rubric->getId()]);
        return $data;
    }

    private function deleteInvalidFile($data)
    {
        if(isset($data['photofile']['tmp_name'])) {
            $pathPhoto = str_replace('\\', '/', $data['photofile']['tmp_name']);
            if (file_exists($pathPhoto)) {
                @unlink($pathPhoto);
            }
        }
        if(isset($data['bookfile']['tmp_name'])) {
            $pathBook = str_replace('\\', '/', $data['bookfile']['tmp_name']);
            if (file_exists($pathBook)) {
                @unlink($pathBook);
            }
        }
    }
}   
