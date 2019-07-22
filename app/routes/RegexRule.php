<?php

namespace app\routes;

class RegexRule
{
    private $_pattern;

    public function __construct($pattern)
    {
        $this->_pattern = $pattern;
    }

    public function getPattern()
    {
        return $this->_pattern;
    }

    public function __toString()
    {
        return $this->getPattern();
    }
}
