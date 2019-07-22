<?php

namespace app\requests;

use app\core\Application;
use app\core\Configurable;
use app\exceptions\Exception;

class FormRequest extends Configurable
{
    public $csrf;

    private $_valid;

    public static function make(array $data, callable $callback = null)
    {
        return parent::make($data, 'trim');
    }

    public function validateData()
    {
        return true;
    }

    public function validateCSRF()
    {
        if (!hash_equals(Application::get()->getCSRF(), $this->csrf)) {
            return false;
        }

        Application::get()->refreshCSRF(true);
        return true;
    }

    public function validate()
    {
        if (!$this->validateCSRF()) {
            $this->_valid = false;
            return false;
        }

        return $this->_valid = $this->validateData();
    }

    public function isValid()
    {
        if (!isset($this->_valid)) {
            throw new Exception('You should call validate() first');
        }

        return $this->_valid;
    }
}
