Yii2-redis-cluster
======================

`yiisoft/Yii2-redis` 只支持单机`redis`，因为近期的需求是在`cluster`模式下使用，所以简单包装一个轮子。

**扩展仅支持`cluster`集群模式**

依赖
------------

- PHP >= 5.6.0 
- Redis >= 3.0
- ext-redis >= 3.0.0
- Yii2 ~2.0.4

安装
------------


```
composer require --prefer-dist crazydb/yii2-redis-cluster
```

配置
-------------


```php
return [
    //....
    'components' => [
        'redisCluster' => [
            'class' => 'crazydb\redis\Connection',
            'hosts' => [
                'localhost:6379'
            ]
        ],
        'cache' => [
            'class' => 'crazydb\redis\Cache',
            'redisCluster' => [
                'hosts' => [
                    'localhost:6379'
                ]
            ]
        ],
        'session' => [
            'class' => 'crazydb\redis\Session',
            'redisCluster' => [
                'hosts' => [
                    'localhost:6379'
                ]
            ]
        ]
    ]
];
```

注意事项
------------------

1. 未完全测试
2. 仅支持`cluster`集群模式