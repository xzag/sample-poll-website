<?php

namespace app\jobs;

use app\core\Configurable;
use app\services\QueueService;

abstract class Job extends Configurable
{
    /**
     * @var QueueService
     */
    protected $_queueService;

    public function __construct($queueService)
    {
        $this->_queueService = $queueService;
    }

    public function queue()
    {
        $this->_queueService->queue('socket-event-processor', (string)$this);
    }

    public function create($handleImmediately = false)
    {
        if (!$handleImmediately) {
            return $this->queue();
        } else {
            return $this->handle();
        }
    }

    abstract public function getQueueId();
    abstract function handle();
}