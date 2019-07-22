<?php

namespace app\services;

use app\services\queue\QueueProvider;

class QueueService
{
    /**
     * @var QueueProvider
     */
    private $_provider;

    public function __construct(QueueProvider $provider)
    {
        $this->_provider = $provider;
    }

    public function queue($queueId, $data)
    {
        $this->_provider->add($queueId, $data);
    }
}
