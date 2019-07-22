<?php

namespace app\jobs;

use app\exceptions\Exception;
use app\models\Poll;
use app\models\PollVote;

class VoteNotificationJob extends Job
{
    /**
     * @var Poll
     */
    public $poll;

    /**
     * @var PollVote
     */
    public $vote;

    public function handle()
    {
        throw new Exception('Not implemented');
    }

    public function getQueueId()
    {
        return 'vote';
    }

    public function __toString()
    {
        return json_encode([
            'event' => $this->getQueueId(),
            'data' => [
                'poll_id' => $this->poll->getId(),
                'answer_id' => $this->vote->getAnswer()->getId(),
                'name' => $this->vote->getName()
            ]
        ]);
    }
}
