<?php

namespace app\models;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @Entity @Table(name="polls")
 **/
class Poll
{
    /**
     * @var UuidInterface|null
     *
     * @Column(type="uuid", unique=true)
     */
    protected $uid;

    /**
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $question;

    /**
     * @OneToMany(targetEntity="PollAnswer", mappedBy="poll")
     * @OrderBy({"id" = "ASC"})
     * @var PollAnswer[] An ArrayCollection of PollAnswer objects.
     **/
    protected $answers = null;

    /**
     * @OneToMany(targetEntity="PollVote", mappedBy="poll")
     * @OrderBy({"id" = "ASC"})
     * @var PollVote[] An ArrayCollection of PollVote objects.
     **/
    protected $votes = null;

    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var bool|null
     */
    protected $voted;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion($question)
    {
        $this->question = $question;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return Poll[]|ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @return Poll[]|ArrayCollection
     */
    public function getVotes()
    {
        return $this->votes;
    }

    public function isVoted()
    {
        return (bool)$this->voted;
    }

    public function setVoted($voted)
    {
        $this->voted = $voted;
    }
}
