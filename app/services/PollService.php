<?php

namespace app\services;

use app\exceptions\BadRequestException;
use app\models\Poll;
use app\models\PollAnswer;
use app\models\PollVote;
use app\requests\PollCreateRequest;
use app\requests\VoteRequest;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;

class PollService
{
    /**
     * @var EntityManager
     */
    private $_entityManager;

    public function __construct($entityManager)
    {
        $this->_entityManager = $entityManager;
    }

    /**
     * @param $uid
     * @return Poll|null
     */
    public function getByUid($uid)
    {
        return $this->_entityManager->getRepository(Poll::class)->findOneBy(['uid' => $uid]);
    }

    /**
     * @param PollCreateRequest $request
     * @return Poll
     */
    public function make(PollCreateRequest $request)
    {
        $poll = new Poll();
        $poll->setQuestion($request->question);
        foreach ($request->answers as $answerText) {
            $answer = new PollAnswer();
            $answer->setAnswer($answerText);
            $answer->setPoll($poll);
            $poll->getAnswers()->add($answer);
        }
        $poll->setUid(Uuid::uuid4());
        $this->_entityManager->persist($poll);

        foreach ($poll->getAnswers() as $answer) {
            $this->_entityManager->persist($answer);
        }

        $this->_entityManager->flush();
        return $poll;
    }

    public function vote(VoteRequest $request)
    {
        $answer = $this->_entityManager->find(PollAnswer::class, $request->answer);

        if (!$answer) {
            throw new BadRequestException(sprintf('Answer (%s) not found', $request->answer));
        }

        $vote = new PollVote();
        $vote->setPoll($request->poll);
        $vote->setAnswer($answer);
        $vote->setName($request->name);
        $this->_entityManager->persist($vote);
        $this->_entityManager->flush();
        return $vote;
    }
}
