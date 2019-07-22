<?php

namespace app\requests;

class PollCreateRequest extends FormRequest
{
    public $question;
    public $answers;

    public function validateData()
    {
        return !empty($this->question) && mb_strlen($this->question) < 255
            && !empty($this->answers);
    }
}
