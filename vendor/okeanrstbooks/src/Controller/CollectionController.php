<?php

namespace OkeanrstBooks\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
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
        if ($books) {
            //$bookForm = new BookForm($this->em);            
            return new ViewModel(array('collection' => $books));
        } else {
            //$this->flashMessenger()->addErrorMessage('');
            //$this->layout()->setTemplate('layout/layout');
            //return $this->redirect()->toRoute('books');
            return new ViewModel();
        }        
    }
    
    //информация по конкретной книге с возможностью просмотра, при наличии приввилегий ссылки на редактирование-форма с заполненными полями 
    public function bookAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('books/collection');
        }         
        $result = $this->collection->findBookById($id);
        if ($result) {
            $view =  new ViewModel();
            $view->entity = $result;        
            return $view;
        }
        return $this->redirect()->toRoute('books/collection'); 
    }

    public function ajaxNewBookAction()
    {

    }
    
    //в action проверяем права, редактируем название, автора (фамилия, имя, отдельным action выскакивает подсказка) выбираем рубрику из списка,
    public function editBookAction()
    {
        
    }
    
    //в action проверяем права, доп. окно подтверждения
    public function deleteBookAction()
    {
        
    }

    public function getBookByAuthorAction()
    {
        
    }

    public function getBookByRubricAction()
    {
        
    }
    
    //показываем постранично список всех рубрик,  в шаблоне проверяем привиллегии-выводим ссылки для редктирования(добавить, удалить)
    public function rubricsAction()
    {
        
    }
    
    //показываем постранично список всех авторов,  в шаблоне проверяем привиллегии-выводим ссылки для редктирования(добавить, удалить)
    public function authorsAction()
    {
        
    }
    
    //в action проверяем права, добавляем название, автора (фамилия, имя, отдельным action выскакивает подсказка) выбираем рубрику из списка
    public function newBookAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('Error. Access is denied!');
            return $this->redirect()->toRoute('books/collection');            
        }
        $form = new BookForm($this->em);
        $entity = new Book();       
        $form->bind($entity);
        $submit = $form->get('submit');
        $submit->setValue('Add book');       
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);          
            if ($form->isValid()) {                        
                $result = $this->collection->addBook($entity);
                if ($result) {
                    $this->flashMessenger()->addSuccessMessage('Book has been successfully added');                    
                    //return $this->redirect()->toRoute('books/collection');
                    return $this->redirect()->toRoute('books/newabook');
                } else {
                    $this->flashMessenger()->addErrorMessage('Error adding book');
                    return $this->redirect()->toRoute('books/newbook');
                }               
            } else {
                $this->flashMessenger()->addErrorMessage('Error adding book. Data failed');                
                //return $this->redirect()->toRoute('books/newbook');                
                $view =  new ViewModel();
                $view->form = $form;        
                return $view;
            }
            
        }
        $view =  new ViewModel();
        $view->form = $form;        
        
        return $view;
    }    
    
    public function newAuthorAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('Error. Access is denied!');
            return $this->redirect()->toRoute('books/collection');            
        }
        $form = new AuthorForm($this->em);
        $entity = new Author();       
        $form->bind($entity);
        $submit = $form->get('submit');
        $submit->setValue('Add author');        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);          
            if ($form->isValid()) {                        
                $result = $this->collection->addAuthor($entity);
                if ($result) {
                    $this->flashMessenger()->addSuccessMessage('Athor has been successfully added');                    
                    //return $this->redirect()->toRoute('books/collection');
                    return $this->redirect()->toRoute('books/newauthor');
                } else {
                    $this->flashMessenger()->addErrorMessage('Error adding author');
                    return $this->redirect()->toRoute('books/newauthor');
                }               
            } else {
                $this->flashMessenger()->addErrorMessage('Error adding author. Data failed');
                //return $this->redirect()->toRoute('books/newauthor');                
                $view =  new ViewModel();
                $view->form = $form;        
                return $view;
            }
            
        }
        $view =  new ViewModel();
        $view->form = $form;        
        
        return $view;
    }

    public function ajaxNewAuthorAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('Error. Access is denied!');
            return $this->redirect()->toRoute('books/collection');            
        }
    }
    
    public function editAuthorAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('Error. Access is denied!');
            return $this->redirect()->toRoute('books/collection');            
        }
    }
    
    public function deleteAuthorAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('Error. Access is denied!');
            return $this->redirect()->toRoute('books/collection');            
        }
    }
    
    public function newRubricAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('Error. Access is denied!');
            return $this->redirect()->toRoute('books/collection');            
        }
        $form = new RubricForm($this->em);
        $entity = new Rubric();       
        $form->bind($entity);
        $submit = $form->get('submit');
        $submit->setValue('Add rubric');        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);          
            if ($form->isValid()) {                        
                $result = $this->collection->addRubric($entity);
                if ($result) {
                    $this->flashMessenger()->addSuccessMessage('Rubric has been successfully added');                    
                    //return $this->redirect()->toRoute('books/collection');
                    return $this->redirect()->toRoute('books/newrubric');
                } else {
                    $this->flashMessenger()->addErrorMessage('Error adding author');
                    return $this->redirect()->toRoute('books/newrubric');
                }               
            } else {
                $this->flashMessenger()->addErrorMessage('Error adding rubric. Data failed');
                //return $this->redirect()->toRoute('books/newrubric');                
                $view =  new ViewModel();
                $view->form = $form;        
                return $view;
            }
            
        }
        $view =  new ViewModel();
        $view->form = $form;        
        
        return $view;
    }

    public function ajaxNewRubricAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('Error. Access is denied!');
            return $this->redirect()->toRoute('books/collection');            
        }

    }
    
    public function editRubricAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('Error. Access is denied!');
            return $this->redirect()->toRoute('books/collection');            
        }
    }
    
    public function deleteRubricAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            $this->flashMessenger()->addErrorMessage('Error. Access is denied!');
            return $this->redirect()->toRoute('books/collection');            
        }
    }
/*
    public function indexAction()
    {
        return new ViewModel(array(
            'albums' => $this->getEntityManager()->getRepository('Album\Entity\Album')->findAll(),
        ));
    }

    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $album = new Album();
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $album->exchangeArray($form->getData());
                $this->getEntityManager()->persist($album);
                $this->getEntityManager()->flush();

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album', array(
                'action' => 'add'
            ));
        }

        $album = $this->getEntityManager()->find('Album\Entity\Album', $id);
        if (!$album) {
            return $this->redirect()->toRoute('album', array(
                'action' => 'index'
            ));
        }

        $form  = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getEntityManager()->flush();

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $album = $this->getEntityManager()->find('Album\Entity\Album', $id);
                if ($album) {
                    $this->getEntityManager()->remove($album);
                    $this->getEntityManager()->flush();
                }
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return array(
            'id'    => $id,
            'album' => $this->getEntityManager()->find('Album\Entity\Album', $id)
        );
    }*/
}