<?php

namespace app\models;

/**
 * @Entity @Table(name="poll_answers")
 **/
class PollAnswer
{
    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $answer;

    /**
     * @ManyToOne(targetEntity="Poll", inversedBy="answers")
     **/
    protected $poll;

    public function getId()
    {
        return $this->id;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    public function getPoll()
    {
        return $this->poll;
    }

    public function setPoll(Poll $poll)
    {
        $this->poll = $poll;
    }
}
