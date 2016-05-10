<?php

namespace OkeanrstBooks\Validator;

use DoctrineModule\Validator\NoObjectExists as DoctrineNoObjectExists;
use Zend\Validator\Exception\RuntimeException;

class CustomNoObjectExists extends DoctrineNoObjectExists
{
	/**
     * Error constants
     */
    const ERROR_INVALID_VALUE = 'invalid value';

    public function isValid($value)
	{
		$val = explode("`*`", $value);
        try {
            $cleanedValue = $this->cleanSearchValue($val);
        } catch (RuntimeException $e) {
            $this->error(self::ERROR_INVALID_VALUE, $value);
            return false;
        }
		
        $match = $this->objectRepository->findOneBy($cleanedValue);

        if (is_object($match)) {
            $this->error(self::ERROR_OBJECT_FOUND, str_replace("`*`", ' ', $value));
            return false;
        }

        return true;
	}
}