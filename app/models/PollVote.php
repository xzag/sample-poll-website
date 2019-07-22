<?php

namespace app\models;

/**
 * @Entity @Table(name="poll_votes")
 **/
class PollVote
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
    protected $name;

    /**
     * @ManyToOne(targetEntity="Poll", inversedBy="answers")
     **/
    protected $poll;

    /**
     * @ManyToOne(targetEntity="PollAnswer")
     **/
    protected $answer;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPoll()
    {
        return $this->poll;
    }

    public function setPoll(Poll $poll)
    {
        $this->poll = $poll;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function setAnswer(PollAnswer $answer)
    {
        $this->answer = $answer;
    }
}
