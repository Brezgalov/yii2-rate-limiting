## Как пользоваться

Подключить в behaviours контроллера:


    'rateLimiter' => [
        'class' => RateLimiter::class,
        'user' => \Yii::createObject([
            'class' => IpLimiterCache::class,
            'only' => ['my-action'],
            'requests' => 1,
            'perSec' => 120,
        ]),
    ],