## Как пользоваться
Установка через composer:

    composer require brezgalov/yii2-rate-limiting --prefer-dist

Для php8:

    composer require brezgalov/yii2-rate-limiting --prefer-dist --ignore-platform-reqs

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