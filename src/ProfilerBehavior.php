<?php

namespace Brezgalov\LiveProfBehavior;

use yii\base\Application;
use yii\base\Behavior;

class ProfilerBehavior extends Behavior
{
    public function events()
    {
        return [
            Application::EVENT_BEFORE_REQUEST => 'beforeRequest',
            Application::EVENT_AFTER_REQUEST => 'afterRequest'
        ];
    }

    public function beforeRequest()
    {
        $comp = Profiler::comp();

        $comp->start();

        $comp->timer('main');
    }

    public function afterRequest()
    {
        Profiler::comp()->stopTimer('main');
    }
}