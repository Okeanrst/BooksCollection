<?php

namespace OkeanrstBooks\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Stdlib\Hydrator\ClassMethods;
use OkeanrstBooks\Entity\Rubric;
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;

class RubricForm extends Form
{
    private $name = 'rubric-form';

    private $inputFilter;

    private $objectManager;
    
    public function __construct(ObjectManager $objectManager)
	{
        parent::__construct($this->name);
        $this->objectManager = $objectManager;
        //$this->setHydrator(new DoctrineHydrator($objectManager));
        $this->setHydrator(new ClassMethods());
        $this->setObject(new Rubric());
        
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
                    array(
                        'name'    => 'Alpha',
                        'options' => array(
                            'allowWhiteSpace' => true,                            
                        ),
                    ),
                ),
            ));
            $this->inputFilter = $inputFilter;
        }

        $titleInput = $this->inputFilter->get('title');

       	$noObjectExistsValidator = new NoObjectExistsValidator(array(
            'object_repository' => $this->objectManager->getRepository('OkeanrstBooks\Entity\Rubric'),
            'fields'            => 'title'
       	));

       	$titleInput->getValidatorChain()->attach($noObjectExistsValidator);
        
        return $this->inputFilter;
    }
}