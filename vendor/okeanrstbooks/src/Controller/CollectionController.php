<?php

namespace OkeanrstBooks\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use OkeanrstBooks\Form\BookForm;
use Doctrine\ORM\EntityManager;

class CollectionController extends AbstractActionController
{
    protected $collection;
    
    protected $em;

    public function __construct($collectionService, EntityManager $entityManager)
    {
        $this->collection = $collectionService;
        $this->em = $entityManager;
    }    
    
    
    //���������� ����������� ������ ���� ����, ������ �� �������� ������ ������, �������. � ������� ��������� �����������-������� ������ ��� ���������� �����.
    public function collectionAction()
    {
        $books = $this->collection->getAllBooksPaginator((int)$this->params()->fromQuery('page', 1), 10);        
        if ($books) {
            //$bookForm = new BookForm($this->em);            
            return new ViewModel(array('books' => $books));
        } else {
            //$this->flashMessenger()->addErrorMessage('');
            //$this->layout()->setTemplate('layout/layout');
            //return $this->redirect()->toRoute('books');
            return new ViewModel();
        }        
    }
    
    //���������� �� ���������� ����� � ������������ ���������, ��� ������� ����������� ������ �� ��������������-����� � ������������ ������ 
    public function bookAction()
    {
        
    }
    
    //���������� ����������� ������ ���� ������,  � ������� ��������� �����������-������� ������ ��� �������������(��������, �������)
    public function rubricsAction()
    {
        
    }
    
    //���������� ����������� ������ ���� �������,  � ������� ��������� �����������-������� ������ ��� �������������(��������, �������)
    public function authorsAction()
    {
        
    }
    
    //� action ��������� �����, ��������� ��������, ������ (�������, ���, ��������� action ����������� ���������) �������� ������� �� ������
    public function newBookAction()
    {
        var_dump('newBookAction');
    }
    
    //� action ��������� �����, ����������� ��������, ������ (�������, ���, ��������� action ����������� ���������) �������� ������� �� ������,
    public function editBookAction()
    {
        
    }
    
    //� action ��������� �����, ���. ���� �������������
    public function deleteBookAction()
    {
        
    }
    
    public function newAuthorAction()
    {
        
    }
    
    public function editAuthorAction()
    {
        
    }
    
    public function deleteAuthorAction()
    {
        
    }
    
    public function newRubricAction()
    {
        
    }
    
    public function editRubricAction()
    {
        
    }
    
    public function deleteRubricAction()
    {
        
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