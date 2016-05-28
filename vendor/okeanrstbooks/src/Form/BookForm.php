<?php

namespace OkeanrstBooks\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Callback as CallbackValidator;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineModule\Validator\ObjectExists as ObjectExistsValidator;
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
        $this->setHydrator(new DoctrineHydrator($objectManager));        
        $this->setAttribute('enctype','multipart/form-data');
        
        $this->add(array(
            'name' => 'id',
            'type'  => 'hidden',
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
            'attributes' => array(
                'required' => 'required',
            ),           
        ));
        
        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'author',
                'attributes' => array(
                    'required' => true,
                    'multiple' => true,                    
                ),
                'options' => array(
                    'object_manager'     => $this->objectManager,
                    'target_class'       => 'OkeanrstBooks\Entity\Author',
                    //'property'           => 'lastName',
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
                    'required' => true,
                    'multiple' => true,                    
                ),
                'options' => array(
                    'object_manager'     => $this->objectManager,
                    'target_class'       => 'OkeanrstBooks\Entity\Rubric',
                    //'property'           => 'title',
                    'display_empty_item' => true,
                    'empty_item_label'   => '---',
                    'label_generator' => function($targetEntity) {
                        return $targetEntity->getTitle();
                    },					
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
                'required' => false,
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
                    array('name' => 'Int'),
                ),
                /*'validators' => array(
                    array(
                        'name' => 'DoctrineModule\Validator\ObjectExists',
                        'options' => array(
                            'object_repository' => $this->objectManager->getRepository('OkeanrstBooks\Entity\Author'),
                            'fields' => 'id'
                        )
                    )
                )*/                
            ));

            $inputFilter->add(array(
                'name'     => 'rubric',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                )               
            ));
            
            $inputFilter->add(array(
                'name'     => 'photofile',
                'required'   => true,
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
                'required'   => true,
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
                    array(
                        'name' => 'filemimetype',
                        'options' => array(
                            'mimeType' => array('image/jpeg', 'image/png', 'image/bmp', 'image/gif',
                                'image/tiff', 'text/plain', 'text/rtf', 'application/pdf',
                                )
                        )
                    ),
                    /*array(
                        'name' => 'fileexcludemimetype',
                        'options' => array(
                            'mimeType' => array('application/octet-stream', 'text/x-php')
                        )
                    )*/                                        
                )
            ));
            
            

            $this->inputFilter = $inputFilter;
        }

        $rubricInputFilter = $this->inputFilter->get('rubric');
        
        $rubricCallbackValidator = new CallbackValidator(function($value){
            $value = is_object($value) ? array($value) : (array) $value;

            $objectExistsValidator = new ObjectExistsValidator(array(
                'object_repository' => $this->objectManager->getRepository('OkeanrstBooks\Entity\Rubric'),
                'fields'            => 'id'
            ));            

            foreach ($value as $key => $val) {
                if (!$objectExistsValidator->isValid($val)) {
                    return false;
                }
            }
            return true;           
        });
        $rubricCallbackValidator->setMessage("No object matching '%value%' was found", 'callbackValue');
        
        $rubricInputFilter->getValidatorChain()->attach($rubricCallbackValidator);

        $authorInputFilter = $this->inputFilter->get('author');
        
        $authorCallbackValidator = new CallbackValidator(function($value){
            $value = is_object($value) ? array($value) : (array) $value;

            $objectExistsValidator = new ObjectExistsValidator(array(
                'object_repository' => $this->objectManager->getRepository('OkeanrstBooks\Entity\Author'),
                'fields'            => 'id'
            ));            

            foreach ($value as $key => $val) {
                if (!$objectExistsValidator->isValid($val)) {
                    return false;
                }
            }
            return true;           
        });
        $authorCallbackValidator->setMessage("No object matching '%value%' was found", 'callbackValue');
        
        $authorInputFilter->getValidatorChain()->attach($authorCallbackValidator);        

        return $this->inputFilter;
    }
}
