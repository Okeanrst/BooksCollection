<?php

namespace OkeanrstBooks\Validator;

use DoctrineModule\Validator\NoObjectExists as DoctrineNoObjectExists;

class CustomNoObjectExists extends DoctrineNoObjectExists
{
	public function isValid($value)
	{
		$val = explode(" ", $value);
		$cleanedValue = $this->cleanSearchValue($val);
        $match        = $this->objectRepository->findOneBy($cleanedValue);

        if (is_object($match)) {
            $this->error(self::ERROR_OBJECT_FOUND, $value);

            return false;
        }

        return true;
	}
}