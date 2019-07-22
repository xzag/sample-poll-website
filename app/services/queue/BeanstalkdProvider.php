<?php

namespace app\services\queue;

use Pheanstalk\Pheanstalk;

class BeanstalkdProvider implements QueueProvider
{
    private $_pheanstalk;

    public function __construct($host)
    {
        $this->_pheanstalk = Pheanstalk::create($host);
    }

    public function add($queueId, $data)
    {
        $this->_pheanstalk->useTube($queueId)->put($data);
    }
}