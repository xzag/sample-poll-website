<?php

namespace app\controllers;

use app\jobs\VoteNotificationJob;
use app\requests\PollCreateRequest;
use app\requests\VoteRequest;
use app\services\PollService;
use app\services\queue\BeanstalkdProvider;
use app\services\QueueService;

class PollController extends Controller
{
    private $_pollService;
    private $_queueService;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->_pollService = new PollService($this->getApp()->getEntityManager());
        $this->_queueService = new QueueService(new BeanstalkdProvider(getenv('SOCKET_BEANSTALK_HOST')));
    }

    public function create()
    {
        $createRequest = new PollCreateRequest();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $createRequest = PollCreateRequest::make($request->post());
            if ($createRequest->validate()) {
                $poll = $this->_pollService->make($createRequest);
                return $this->redirect('/poll/view/' . $poll->getUid());
            }
        }

        return $this->view('poll/create.html.twig', ['request' => $createRequest]);
    }

    public function get($params)
    {
        $pollUid = $params[0] ?? null;

        $poll = $this->_pollService->getByUid($pollUid);
        if (!$poll) {
            return $this->view('site/error.html.twig', ['error' => 'Poll not exists']);
        }

        $poll->setVoted($_COOKIE['vote_' . $poll->getId()] ?? false);
        $voteRequest = new VoteRequest();
        $request = $this->getRequest();
        if (!$poll->isVoted() && $request->isPost()) {
            $voteRequest = VoteRequest::make($request->post());
            $voteRequest->poll = $poll;
            if ($voteRequest->validate()) {
                $vote = $this->_pollService->vote($voteRequest);
                setcookie(sprintf('vote_%s', $poll->getId()), true, 0, '/');

                $voteNotificationJob = new VoteNotificationJob($this->_queueService);
                $voteNotificationJob->poll = $poll;
                $voteNotificationJob->vote = $vote;
                $voteNotificationJob->create();

                return $this->redirect('/poll/view/' . $poll->getUid());
            }
        }

        return $this->view('poll/view.html.twig', ['poll' => $poll, 'request' => $voteRequest]);
    }
}
