<?php

namespace crazydb\redis;

use Yii;
use yii\di\Instance;
use yii\base\InvalidConfigException;

/**
 * Redis Session implements a session component using [redis](http://redis.io/) as the storage medium.
 *
 * Redis Session requires redis version 3.0.0 or higher to work properly.
 *
 * It needs to be configured with a redis [[Connection]] that is also configured as an application component.
 * By default it will use the `redisCluster` application component.
 *
 * To use redis Session as the session application component, configure the application as follows,
 *
 * ~~~
 * [
 *     'components' => [
 *         'session' => [
 *             'class' => 'crazydb\redis\Session',
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
 *         'session' => [
 *             'class' => 'crazydb\redis\Session',
 *             // 'redisCluster' => 'redisCluster' // id of the connection application component
 *         ],
 *     ],
 * ]
 * ~~~
 *
 */
class Session extends \yii\web\Session
{
    /**
     * @var Connection|string|array the RedisCluster [[Connection]] object or the application component ID of the RedisCluster [[Connection]].
     * This can also be an array that is used to create a RedisCluster [[Connection]] instance in case you do not want do configure
     * redis connection as an application component.
     * After the Session object is created, if you want to change this property, you should only assign it
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
     * Initializes the RedisCluster Session component.
     * This method will initialize the [[redisCluster]] property to make sure it refers to a valid redisCluster connection.
     * @throws InvalidConfigException if [[redisCluster]] is invalid.
     */
    public function init()
    {
        $this->redisCluster = Instance::ensure($this->redisCluster, Connection::class);
        if ($this->keyPrefix === null) {
            $this->keyPrefix = substr(md5(Yii::$app->id), 0, 5) . ':session:';
        }
        parent::init();
    }

    /**
     * Returns a value indicating whether to use custom session storage.
     * This method overrides the parent implementation and always returns true.
     * @return boolean whether to use custom storage.
     */
    public function getUseCustomStorage()
    {
        return true;
    }

    /**
     * Session read handler.
     * Do not call this method directly.
     * @param string $id session ID
     * @return string the session data
     */
    public function readSession($id)
    {
        $data = $this->redisCluster->get($this->calculateKey($id));
        return $data === false || $data === null ? '' : $data;
    }

    /**
     * Session write handler.
     * Do not call this method directly.
     * @param string $id session ID
     * @param string $data session data
     * @return boolean whether session write is successful
     */
    public function writeSession($id, $data)
    {
        return (bool)$this->redisCluster->setex($this->calculateKey($id), $this->getTimeout(), $data);
    }

    /**
     * Session destroy handler.
     * Do not call this method directly.
     * @param string $id session ID
     * @return boolean whether session is destroyed successfully
     */
    public function destroySession($id)
    {
        return (bool)$this->redisCluster->del($this->calculateKey($id));
    }

    /**
     * Generates a unique key used for storing session data in cache.
     * @param string $id session variable name
     * @return string a safe cache key associated with the session variable name
     */
    protected function calculateKey($id)
    {
        return $this->keyPrefix . md5(json_encode([__CLASS__, $id]));
    }
}
