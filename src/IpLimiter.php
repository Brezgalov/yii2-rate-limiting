<?php

namespace Brezgalov\RateLimiting;

use yii\base\Action;
use yii\filters\RateLimitInterface;
use yii\web\Request;

abstract class IpLimiter implements RateLimitInterface
{
    /**
     * @var array|null
     */
    public $only;

    /**
     * @var int
     */
    public $requests = 3;

    /**
     * @var int
     */
    public $perSecs = 60;

    /**
     * @inheritDoc
     */
    public function getRateLimit($request, $action)
    {
        return [$this->requests, $this->perSecs];
    }

    /**
     * @param $action
     * @return bool
     */
    protected function actionShouldOperate(Action $action)
    {
        return empty($this->only) || in_array($action->id, $this->only);
    }

    /**
     * @return CacheInterface
     */
    protected function getCache()
    {
        return $this->cache ?: Yii::$app->get('cache');
    }

    /**
     * @param Request $request
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected function generateKey(Request $request)
    {
        return 'allowance:' . implode(':', [
                $request->getUserIP(),
                $request->getMethod(),
                md5($request->getUrl()),
            ]);
    }

    /**
     * @inheritDoc
     */
    public abstract function loadAllowance($request, $action);

    /**
     * @inheritDoc
     */
    public abstract function saveAllowance($request, $action, $allowance, $timestamp);
}
