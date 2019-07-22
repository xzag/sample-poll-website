<?php

namespace app\services\queue;

interface QueueProvider
{
    public function add($queueId, $data);
}
