<?php

namespace app\requests;

use app\core\Application;
use app\models\PollAnswer;

class VoteRequest extends FormRequest
{
    public $name;
    public $poll;
    public $answer;

    public function validateData()
    {
        return !empty($this->name) && mb_strlen($this->name) < 255
            && $this->isAnswerExists();
    }

    public function isAnswerExists()
    {
        $answer = Application::get()->getEntityManager()->getRepository(PollAnswer::class)->find($this->answer);
        return $answer && $answer->getPoll() == $this->poll;
    }

}
