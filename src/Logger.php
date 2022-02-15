<?php

namespace Brezgalov\LiveProfBehavior;

class Logger extends \Badoo\LiveProfiler\Logger
{
    /**
     * Logger constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->logfile = \Yii::getAlias('@app/runtime/logs/live.profiler.log');
    }
}