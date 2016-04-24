<?php

namespace OkeanrstBooks\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Stdlib\Hydrator\ClassMethods;
use OkeanrstBooks\Entity\Book;


class BookForm extends Form
{
    private $name = 'book-form';

    private $objectManager;

    private $inputFilter;
    
    public function __construct(ObjectManager $objectManager)
	{
        parent::__construct($this->name);
        $this->objectManager = $objectManager;        
        //$this->setHydrator(new ClassMethods());
        $this->setHydrator(new DoctrineHydrator($objectManager));
        //$this->setObject(new Book());
        
        
        $this->add(array(
            'name' => 'id',
            'type'  => 'hidden',            
            'attributes' => array(
                'required' => 'required',
            ),
        ));
        
        $this->add(array(
            'name' => 'title',
            'type'  => 'text',            
            'attributes' => array(
                'required' => 'required',
            ),
        ));
        
        $this->add(array(
            'name' => 'photofile',
            'type'  => 'file',           
        ));
        
        $this->add(array(
            'name' => 'author',
            'type'  => 'text',            
            'attributes' => array(
                'required' => 'required',
            ),
        ));
        
        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'author',
                'attributes' => array(
                    'required' => false,
                    //'value' => true
                ),
                'options' => array(
                    'object_manager'     => $this->objectManager,
                    'target_class'       => 'OkeanrstBooks\Entity\Author',
                    'property'           => 'lastName',
                    'display_empty_item' => true,
                    'empty_item_label'   => '---',
                    'label_generator' => function($targetEntity) {
                        return $targetEntity->getLastName() . ' - ' . $targetEntity->getName();
                    },                    
                ),
            )
        );

        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'rubric',
				'attributes' => array(
                    'required' => false,
                    'multiple' => true,
                    //'value' => true
                ),
                'options' => array(
                    'object_manager'     => $this->objectManager,
                    'target_class'       => 'OkeanrstBooks\Entity\Rubric',
                    'property'           => 'title',
                    'display_empty_item' => true,
                    'empty_item_label'   => '---',					
                ),
            )
        );        
        
        $this->add(array(
            'name' => 'bookfile',
            'type'  => 'file',            
            'attributes' => array(
                'required' => 'required',
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
			'options' => array(
                'csrf_options' => array(
                    'timeout' => 3600
                )
            )
        ));
        
        $this->add(array(
            'type' => 'submit',
			'name' => 'submit',
            'attributes' => array(                
                'value' => 'Save',
				'class' => 'btn btn-primary'
            )
        ));
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
 
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'title',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 64,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'author',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 64,
                        ),
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name'     => 'photofile',
                'required'   => false,
                'filters' => array(
                    array(
                        'name' => 'filerenameupload',
                        'options' => array(
                            'use_upload_extension' => true,
                            'target'    => './public/data/img/',
                            'randomize' => true,
                            'overwrite' => false
                        )
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'filesize',
                        'options' => array(
                            'max' => '1MB'
                        )
                    ),
                    array(
                        'name' => 'filemimetype',
                        'options' => array(
                            'mimeType' => array('image/jpeg', 'image/png', 'image/bmp', 'image/gif')
                        )
                    ),
                    array(
                        'name' => 'fileimagesize',
                        'options' => array(
                            'minWidth' => 80,
                            'minHeight' => 80
                        )
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name'     => 'bookfile',
                'required'   => false,
                'filters' => array(
                    array(
                        'name' => 'filerenameupload',
                        'options' => array(
                            'use_upload_extension' => true,
                            'target'    => './public/data/books',
                            'randomize' => true,
                            'overwrite' => false
                        )
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'filesize',
                        'options' => array(
                            'max' => '50MB'
                        )
                    ),
                    /*array(
                        'name' => 'filemimetype',
                        'options' => array(
                            'mimeType' => array('image/jpeg', 'image/png', 'image/bmp', 'image/gif')
                        )
                    ),*/                    
                )
            ));
            
            

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}