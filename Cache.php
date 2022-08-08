<?php

namespace crazydb\redis;

use Yii;
use yii\di\Instance;

/**
 * Redis Cache implements a cache application component based on [redis](http://redis.io/) key-value store.
 *
 * Redis Cache requires redis version 3.0.0 or higher to work properly.
 *
 * It needs to be configured with a redis [[Connection]] that is also configured as an application component.
 * By default it will use the `redisCluster` application component.
 *
 * See [[Cache]] manual for common cache operations that redis Cache supports.
 *
 * Unlike the [[Cache]], redis Cache allows the expire parameter of [[set]], [[add]], [[mset]] and [[madd]] to
 * be a floating point number, so you may specify the time in milliseconds (e.g. 0.1 will be 100 milliseconds).
 *
 * To use redis Cache as the cache application component, configure the application as follows,
 *
 * ~~~
 * [
 *     'components' => [
 *         'cache' => [
 *             'class' => 'crazydb\redis\Cache',
 *             'redisCluster' => [
 *                 'hosts' => [
 *                      'localhost:6379'
 *                  ]
 *             ]
 *         ],
 *     ],
 * ]
 * ~~~
 *
 * Or if you have configured the redis [[Connection]] as an application component, the following is sufficient:
 *
 * ~~~
 * [
 *     'components' => [
 *         'cache' => [
 *             'class' => 'crazydb\redis\Cache',
 *             // 'redisCluster' => 'redisCluster' // id of the connection application component
 *         ],
 *     ],
 * ]
 * ~~~
 *
 */
class Cache extends \yii\caching\Cache
{
    /**
     * @var Connection|string|array the RedisCluster [[Connection]] object or the application component ID of the RedisCluster [[Connection]].
     * This can also be an array that is used to create a RedisCluster [[Connection]] instance in case you do not want do configure
     * RedisCluster connection as an application component.
     * After the Cache object is created, if you want to change this property, you should only assign it
     * with a RedisCluster [[Connection]] object.
     */
    public $redisCluster = 'redisCluster';

    /**
     * @var string a string prefixed to every cache key so that it is unique. If not set,
     * it will use a prefix generated from [[Application::id]]. You may set this property to be an empty string
     * if you don't want to use key prefix. It is recommended that you explicitly set this property to some
     * static value if the cached data needs to be shared among multiple applications.
     */
    public $keyPrefix;

    /**
     * Initializes the redisCluster Cache component.
     * This method will initialize the [[redisCluster]] property to make sure it refers to a valid RedisCluster connection.
     * @throws \yii\base\InvalidConfigException if [[redisCluster]] is invalid.
     */
    public function init()
    {
        $this->redisCluster = Instance::ensure($this->redisCluster, Connection::class);
        if ($this->keyPrefix === null) {
            $this->keyPrefix = substr(md5(Yii::$app->id), 0, 5) . ':cache:';
        }
    }


    /**
     * @inheritdoc
     */
    public function exists($key)
    {
        $key = $this->buildKey($key);
        return (bool)$this->redisCluster->exists($key);
    }

    /**
     * @inheritdoc
     */
    protected function getValue($key)
    {
        $key = $this->buildKey($key);
        return $this->redisCluster->get($key);
    }

    /**
     * @inheritdoc
     */
    protected function getValues($keys)
    {
        $keyMap = [];
        foreach ($keys as $key) {
            $keyMap[$key] = $this->buildKey($key);
        }
        $response = $this->redisCluster->mget(array_values($keyMap));
        $result = [];
        $i = 0;
        foreach ($keys as $key) {
            $result[$key] = $response[$i++];
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function setValue($key, $value, $expire)
    {
        $key = $this->buildKey($key);
        if ($expire == 0) {
            return (bool)$this->redisCluster->set($key, $value);
        } else {
            return (bool)$this->redisCluster->setEx($key, $expire, $value);
        }
    }

    /**
     * @inheritdoc
     */
    protected function setValues($data, $expire)
    {
        $failedKeys = [];
        $dataMap = [];
        $keyMap = [];
        foreach ($data as $key => $value) {
            $t = $this->buildKey($key);
            $dataMap[$t] = $value;
            $keyMap[$t] = $key;
        }
        if ($expire == 0) {
            $this->redisCluster->mSet($dataMap);
        } else {
            $expire = (int)$expire;
            $this->redisCluster->multi();
            $this->redisCluster->mSet($dataMap);
            $index = [];
            foreach ($dataMap as $key => $value) {
                $this->redisCluster->expire($key, $expire);
                $index[] = $keyMap[$key];
            }
            $result = $this->redisCluster->exec();
            array_shift($result);
            foreach ($result as $i => $r) {
                if ($r != 1) {
                    $failedKeys[] = $index[$i];
                }
            }
        }

        return $failedKeys;
    }


    /**
     * @inheritdoc
     */
    protected function addValue($key, $value, $expire)
    {
        $key = $this->buildKey($key);
        if (!(bool)$this->redisCluster->setNx($key, $value))
            return false;
        if (intval($expire) > 0) {
            $this->redisCluster->expire($key, $expire);
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    protected function deleteValue($key)
    {
        $key = $this->buildKey($key);
        return (bool)$this->redisCluster->del($key);
    }

    /**
     * @inheritdoc
     */
    protected function flushValues()
    {
        return $this->redisCluster->flushdb();
    }
}
