<?php

namespace Brezgalov\RateLimiting;

use Yii;
use yii\base\Action;
use yii\caching\CacheInterface;
use yii\filters\RateLimitInterface;
use yii\web\Request;

class IpLimiterCache extends IpLimiter implements RateLimitInterface
{
    /**
     * @var CacheInterface
     */
    public $cache;

    /**
     * @return CacheInterface
     */
    protected function getCache()
    {
        return $this->cache ?: Yii::$app->get('cache');
    }

    /**
     * @inheritDoc
     */
    public function loadAllowance($request, $action)
    {
        if (!$this->actionShouldOperate($action)) {
            return [1, time()];
        }

        $key = $this->generateKey($request);
        $cache = $this->getCache();

        if (empty($cache) || !($cache instanceof CacheInterface)) {
            throw new \Exception('Кеш модуль недоступен');
        }

        return $cache->get($key);
    }

    /**
     * @inheritDoc
     */
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        if (!$this->actionShouldOperate($action)) {
            return;
        }

        $key = $this->generateKey($request);
        $cache = $this->getCache();

        if (empty($cache) || !($cache instanceof CacheInterface)) {
            throw new \Exception('Кеш модуль недоступен');
        }

        $res = $cache->set($key, [$allowance, $timestamp]);
        if (!$res) {
            throw new \Exception('Запись в кеш недоступна');
        }
    }
}
