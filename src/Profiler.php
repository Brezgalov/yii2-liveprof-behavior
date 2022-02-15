<?php

namespace Brezgalov\LiveProfBehavior;

use Badoo\LiveProfiler\LiveProfiler;
use Badoo\LiveProfiler\SimpleProfiler;
use yii\base\Component;

class Profiler extends Component
{
    /**
     * @var string
     */
    public static $componentName = 'profiler';

    /**
     * @var bool
     */
    public $active;

    /**
     * @var string
     */
    public $app;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $dbHost;

    /**
     * @var string
     */
    public $dbPort;

    /**
     * @var string
     */
    public $dbName;

    /**
     * @var string
     */
    public $dbUser;

    /**
     * @var string
     */
    public $dbPass;

    /**
     * @var LiveProfiler
     */
    public $profiler;

    /**
     * @return Profiler|null
     * @throws \yii\base\InvalidConfigException
     */
    public static function comp()
    {
        return \Yii::$app->has(static::$componentName) ? \Yii::$app->get(static::$componentName) : null;
    }

    /**
     * Profiler constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->active = (bool)@$_ENV['USE_PROFILER'];
        $this->app = @$_ENV['APP_NAME'] ?: null;
        $this->label = \Yii::$app->has('request') ? \Yii::$app->request->getUrl() : 'default';

        $this->dbHost = @$_ENV['PROFILER_DB_HOST'];
        $this->dbPort = @$_ENV['PROFILER_DB_PORT'];
        $this->dbName = @$_ENV['PROFILER_DB_NAME'];
        $this->dbUser = @$_ENV['PROFILER_DB_USER'];
        $this->dbPass = @$_ENV['PROFILER_DB_PASS'];

        parent::__construct($config);

        $this->initProfiler();
    }

    /**
     * init profiler
     */
    public function initProfiler()
    {
        $dsn="mysql://{$this->dbUser}:{$this->dbPass}@{$this->dbHost}:{$this->dbPort}/{$this->dbName}?charset=utf8";

        if (empty($this->profiler)) {
            $this->profiler = LiveProfiler::getInstance();

            $this->profiler
                ->setMode(LiveProfiler::MODE_DB)
                ->setConnectionString($dsn)
                ->setDivider(@$_ENV['PROFILER_DIVIDER_VALUE'] ?: 1)
                ->setTotalDivider(@$_ENV['PROFILER_GLOBAL_DIVIDER_VALUE'] ?: 1)
                ->setLogger(new Logger());
        }

        if ($this->label) {
            $this->profiler->setLabel($this->label);
        }

        if ($this->app) {
            $this->profiler->setApp($this->app);
        }
    }

    /**
     * Start profiling
     */
    public function start()
    {
        if ($this->active) {
            $this->profiler->start();
        }
    }

    /**
     * Reset profiling
     */
    public function drop()
    {
        $this->active = false;
        $this->profiler->reset();
    }

    /**
     * @param null $name
     * @param bool $unique
     * @return mixed|string|null
     */
    public function timer($name = null, $unique = true)
    {
        if (!$this->active) {
            return $name;
        }

        if (empty($name)) {
            $name = uniqid();
        }

        if ($unique) {
            $name = microtime(true) . "_{$name}";
        }

        SimpleProfiler::getInstance()->startTimer($name);

        return $name;
    }

    /**
     * @param $name
     */
    public function stopTimer($name)
    {
        if ($this->active) {
            SimpleProfiler::getInstance()->endTimer($name);
        }
    }
}