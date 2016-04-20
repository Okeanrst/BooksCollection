<?php

namespace OkeanrstBooks\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Stdlib\Hydrator\ClassMethods;
use OkeanrstBooks\Entity\Book;


class EditBookForm extends Form
{
    private $name = 'book-form';
    
    public function __construct(ObjectManager $objectManager)
	{
        parent::__construct($this->name);
        //$this->setHydrator(new DoctrineHydrator($objectManager));
        $this->setHydrator(new ClassMethods());
        $this->setObject(new Book());
        
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
        
    }
    
    /**
     * Convert the object to an array.
     *
     * @return array
     */
    /*public function getArrayCopy() 
    {
        return get_object_vars($this);
    }*/
 
    /**
     * Populate from an array.
     *
     * @param array $data
     */
    /*public function exchangeArray ($data = array()) 
    {
        $this->id = $data['id'];
        $this->artist = $data['artist'];
        $this->title = $data['title'];
    }*/
 
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
                            'max'      => 100,
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
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}