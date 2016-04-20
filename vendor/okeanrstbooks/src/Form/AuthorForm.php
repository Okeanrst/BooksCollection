<?php

namespace OkeanrstBooks\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Stdlib\Hydrator\ClassMethods;
use OkeanrstBooks\Entity\Author;


class AuthorForm extends Form
{
    private $name = 'author-form';

    private $inputFilter;
    
    public function __construct(ObjectManager $objectManager)
	{
        parent::__construct($this->name);
        //$this->setHydrator(new DoctrineHydrator($objectManager));
        $this->setHydrator(new ClassMethods());
        $this->setObject(new Author());
        
        $this->add(array(
            'name' => 'id',
            'type'  => 'hidden',            
        ));
        
        $this->add(array(
            'name' => 'lastName',
            'type'  => 'text',            
            'attributes' => array(
                'required' => 'required',
            ),
        ));
        
        $this->add(array(
            'name' => 'name',
            'type'  => 'text',
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
                'name'     => 'lastName',
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
                    /*array(
                        'name'    => 'Regex',
                        'options' => array(
                            'pattern' => '/[A-Za-zа-яёА-ЯЁ -]+/',                            
                        ),
                    ),*/
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'name',
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
                    /*array(
                        'name'    => 'Regex',
                        'options' => array(
                            'pattern' => '/[A-Za-zа-яёА-ЯЁ -]+/',                            
                        ),
                    ),*/
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}