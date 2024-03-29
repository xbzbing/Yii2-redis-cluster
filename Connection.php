<?php

namespace crazydb\redis;

use Yii;
use yii\base\Component;
use yii\base\UnknownMethodException;
use RedisCluster;
use Exception;

/**
 * Class Connection
 * @package crazydb\redis
 *
 *
 * @method mixed append($key, $value) Append a value to a key. <https://redis.io/commands/append>
 * @method mixed bitcount($key, $start = null, $end = null) Count set bits in a string. <https://redis.io/commands/bitcount>
 * @method mixed bitfield($key, ...$operations) Perform arbitrary bitfield integer operations on strings. <https://redis.io/commands/bitfield>
 * @method mixed bitop($operation, $destkey, ...$keys) Perform bitwise operations between strings. <https://redis.io/commands/bitop>
 * @method mixed bitpos($key, $bit, $start = null, $end = null) Find first bit set or clear in a string. <https://redis.io/commands/bitpos>
 * @method mixed blpop($keys, $timeout) Remove and get the first element in a list, or block until one is available. <https://redis.io/commands/blpop>
 * @method mixed brpop($keys, $timeout) Remove and get the last element in a list, or block until one is available. <https://redis.io/commands/brpop>
 * @method mixed brpoplpush($source, $destination, $timeout) Pop a value from a list, push it to another list and return it; or block until one is available. <https://redis.io/commands/brpoplpush>
 * @method mixed decr($key) Decrement the integer value of a key by one. <https://redis.io/commands/decr>
 * @method mixed decrby($key, $decrement) Decrement the integer value of a key by the given number. <https://redis.io/commands/decrby>
 * @method mixed del(...$keys) Delete a key. <https://redis.io/commands/del>
 * @method mixed discard() Discard all commands issued after MULTI. <https://redis.io/commands/discard>
 * @method mixed dump($key) Return a serialized version of the value stored at the specified key.. <https://redis.io/commands/dump>
 * @method mixed eval($script, $numkeys, $keys, ...$args) Execute a Lua script server side. <https://redis.io/commands/eval>
 * @method mixed evalsha($sha1, $numkeys, $keys, ...$args) Execute a Lua script server side. <https://redis.io/commands/evalsha>
 * @method mixed exec() Execute all commands issued after MULTI. <https://redis.io/commands/exec>
 * @method mixed exists(...$keys) Determine if a key exists. <https://redis.io/commands/exists>
 * @method mixed expire($key, $seconds) Set a key's time to live in seconds. <https://redis.io/commands/expire>
 * @method mixed expireat($key, $timestamp) Set the expiration for a key as a UNIX timestamp. <https://redis.io/commands/expireat>
 * @method mixed geoadd($key, $longitude, $latitude, $member, ...$more) Add one or more geospatial items in the geospatial index represented using a sorted set. <https://redis.io/commands/geoadd>
 * @method mixed geohash($key, ...$members) Returns members of a geospatial index as standard geohash strings. <https://redis.io/commands/geohash>
 * @method mixed geopos($key, ...$members) Returns longitude and latitude of members of a geospatial index. <https://redis.io/commands/geopos>
 * @method mixed geodist($key, $member1, $member2, $unit = null) Returns the distance between two members of a geospatial index. <https://redis.io/commands/geodist>
 * @method mixed georadius($key, $longitude, $latitude, $radius, $metric, ...$options) Query a sorted set representing a geospatial index to fetch members matching a given maximum distance from a point. <https://redis.io/commands/georadius>
 * @method mixed georadiusbymember($key, $member, $radius, $metric, ...$options) Query a sorted set representing a geospatial index to fetch members matching a given maximum distance from a member. <https://redis.io/commands/georadiusbymember>
 * @method mixed get($key) Get the value of a key. <https://redis.io/commands/get>
 * @method mixed getbit($key, $offset) Returns the bit value at offset in the string value stored at key. <https://redis.io/commands/getbit>
 * @method mixed getrange($key, $start, $end) Get a substring of the string stored at a key. <https://redis.io/commands/getrange>
 * @method mixed getset($key, $value) Set the string value of a key and return its old value. <https://redis.io/commands/getset>
 * @method mixed hdel($key, ...$fields) Delete one or more hash fields. <https://redis.io/commands/hdel>
 * @method mixed hexists($key, $field) Determine if a hash field exists. <https://redis.io/commands/hexists>
 * @method mixed hget($key, $field) Get the value of a hash field. <https://redis.io/commands/hget>
 * @method mixed hgetall($key) Get all the fields and values in a hash. <https://redis.io/commands/hgetall>
 * @method mixed hincrby($key, $field, $increment) Increment the integer value of a hash field by the given number. <https://redis.io/commands/hincrby>
 * @method mixed hincrbyfloat($key, $field, $increment) Increment the float value of a hash field by the given amount. <https://redis.io/commands/hincrbyfloat>
 * @method mixed hkeys($key) Get all the fields in a hash. <https://redis.io/commands/hkeys>
 * @method mixed hlen($key) Get the number of fields in a hash. <https://redis.io/commands/hlen>
 * @method mixed hmget($key, ...$fields) Get the values of all the given hash fields. <https://redis.io/commands/hmget>
 * @method mixed hmset($key, $field, $value, ...$more) Set multiple hash fields to multiple values. <https://redis.io/commands/hmset>
 * @method mixed hset($key, $field, $value) Set the string value of a hash field. <https://redis.io/commands/hset>
 * @method mixed hsetnx($key, $field, $value) Set the value of a hash field, only if the field does not exist. <https://redis.io/commands/hsetnx>
 * @method mixed hstrlen($key, $field) Get the length of the value of a hash field. <https://redis.io/commands/hstrlen>
 * @method mixed hvals($key) Get all the values in a hash. <https://redis.io/commands/hvals>
 * @method mixed incr($key) Increment the integer value of a key by one. <https://redis.io/commands/incr>
 * @method mixed incrby($key, $increment) Increment the integer value of a key by the given amount. <https://redis.io/commands/incrby>
 * @method mixed incrbyfloat($key, $increment) Increment the float value of a key by the given amount. <https://redis.io/commands/incrbyfloat>
 * @method mixed keys($pattern) Find all keys matching the given pattern. <https://redis.io/commands/keys>
 * @method mixed lindex($key, $index) Get an element from a list by its index. <https://redis.io/commands/lindex>
 * @method mixed linsert($key, $where, $pivot, $value) Insert an element before or after another element in a list. <https://redis.io/commands/linsert>
 * @method mixed llen($key) Get the length of a list. <https://redis.io/commands/llen>
 * @method mixed lpop($key) Remove and get the first element in a list. <https://redis.io/commands/lpop>
 * @method mixed lpush($key, ...$values) Prepend one or multiple values to a list. <https://redis.io/commands/lpush>
 * @method mixed lpushx($key, $value) Prepend a value to a list, only if the list exists. <https://redis.io/commands/lpushx>
 * @method mixed lrange($key, $start, $stop) Get a range of elements from a list. <https://redis.io/commands/lrange>
 * @method mixed lrem($key, $count, $value) Remove elements from a list. <https://redis.io/commands/lrem>
 * @method mixed lset($key, $index, $value) Set the value of an element in a list by its index. <https://redis.io/commands/lset>
 * @method mixed ltrim($key, $start, $stop) Trim a list to the specified range. <https://redis.io/commands/ltrim>
 * @method mixed mget(...$keys) Get the values of all the given keys. <https://redis.io/commands/mget>
 * @method mixed migrate($host, $port, $key, $destinationDb, $timeout, ...$options) Atomically transfer a key from a Redis instance to another one.. <https://redis.io/commands/migrate>
 * @method mixed monitor() Listen for all requests received by the server in real time. <https://redis.io/commands/monitor>
 * @method mixed mset(...$keyValuePairs) Set multiple keys to multiple values. <https://redis.io/commands/mset>
 * @method mixed msetnx(...$keyValuePairs) Set multiple keys to multiple values, only if none of the keys exist. <https://redis.io/commands/msetnx>
 * @method mixed multi() Mark the start of a transaction block. <https://redis.io/commands/multi>
 * @method mixed object($subcommand, ...$argumentss) Inspect the internals of Redis objects. <https://redis.io/commands/object>
 * @method mixed persist($key) Remove the expiration from a key. <https://redis.io/commands/persist>
 * @method mixed pexpire($key, $milliseconds) Set a key's time to live in milliseconds. <https://redis.io/commands/pexpire>
 * @method mixed pexpireat($key, $millisecondsTimestamp) Set the expiration for a key as a UNIX timestamp specified in milliseconds. <https://redis.io/commands/pexpireat>
 * @method mixed pfadd($key, ...$elements) Adds the specified elements to the specified HyperLogLog.. <https://redis.io/commands/pfadd>
 * @method mixed pfcount(...$keys) Return the approximated cardinality of the set(s) observed by the HyperLogLog at key(s).. <https://redis.io/commands/pfcount>
 * @method mixed pfmerge($destkey, ...$sourcekeys) Merge N different HyperLogLogs into a single one.. <https://redis.io/commands/pfmerge>
 * @method mixed psetex($key, $milliseconds, $value) Set the value and expiration in milliseconds of a key. <https://redis.io/commands/psetex>
 * @method mixed psubscribe(...$patterns) Listen for messages published to channels matching the given patterns. <https://redis.io/commands/psubscribe>
 * @method mixed pttl($key) Get the time to live for a key in milliseconds. <https://redis.io/commands/pttl>
 * @method mixed publish($channel, $message) Post a message to a channel. <https://redis.io/commands/publish>
 * @method mixed punsubscribe(...$patterns) Stop listening for messages posted to channels matching the given patterns. <https://redis.io/commands/punsubscribe>
 * @method mixed quit() Close the connection. <https://redis.io/commands/quit>
 * @method mixed readonly() Enables read queries for a connection to a cluster slave node. <https://redis.io/commands/readonly>
 * @method mixed readwrite() Disables read queries for a connection to a cluster slave node. <https://redis.io/commands/readwrite>
 * @method mixed rename($key, $newkey) Rename a key. <https://redis.io/commands/rename>
 * @method mixed renamenx($key, $newkey) Rename a key, only if the new key does not exist. <https://redis.io/commands/renamenx>
 * @method mixed restore($key, $ttl, $serializedValue, $REPLACE = null) Create a key using the provided serialized value, previously obtained using DUMP.. <https://redis.io/commands/restore>
 * @method mixed rpop($key) Remove and get the last element in a list. <https://redis.io/commands/rpop>
 * @method mixed rpoplpush($source, $destination) Remove the last element in a list, prepend it to another list and return it. <https://redis.io/commands/rpoplpush>
 * @method mixed rpush($key, ...$values) Append one or multiple values to a list. <https://redis.io/commands/rpush>
 * @method mixed rpushx($key, $value) Append a value to a list, only if the list exists. <https://redis.io/commands/rpushx>
 * @method mixed sadd($key, ...$members) Add one or more members to a set. <https://redis.io/commands/sadd>
 * @method mixed scard($key) Get the number of members in a set. <https://redis.io/commands/scard>
 * @method mixed sdiff(...$keys) Subtract multiple sets. <https://redis.io/commands/sdiff>
 * @method mixed sdiffstore($destination, ...$keys) Subtract multiple sets and store the resulting set in a key. <https://redis.io/commands/sdiffstore>
 * @method mixed select($index) Change the selected database for the current connection. <https://redis.io/commands/select>
 * @method mixed set($key, $value, ...$options) Set the string value of a key. <https://redis.io/commands/set>
 * @method mixed setbit($key, $offset, $value) Sets or clears the bit at offset in the string value stored at key. <https://redis.io/commands/setbit>
 * @method mixed setex($key, $seconds, $value) Set the value and expiration of a key. <https://redis.io/commands/setex>
 * @method mixed setnx($key, $value) Set the value of a key, only if the key does not exist. <https://redis.io/commands/setnx>
 * @method mixed setrange($key, $offset, $value) Overwrite part of a string at key starting at the specified offset. <https://redis.io/commands/setrange>
 * @method mixed shutdown($saveOption = null) Synchronously save the dataset to disk and then shut down the server. <https://redis.io/commands/shutdown>
 * @method mixed sinter(...$keys) Intersect multiple sets. <https://redis.io/commands/sinter>
 * @method mixed sinterstore($destination, ...$keys) Intersect multiple sets and store the resulting set in a key. <https://redis.io/commands/sinterstore>
 * @method mixed sismember($key, $member) Determine if a given value is a member of a set. <https://redis.io/commands/sismember>
 * @method mixed slaveof($host, $port) Make the server a slave of another instance, or promote it as master. <https://redis.io/commands/slaveof>
 * @method mixed smembers($key) Get all the members in a set. <https://redis.io/commands/smembers>
 * @method mixed smove($source, $destination, $member) Move a member from one set to another. <https://redis.io/commands/smove>
 * @method mixed sort($key, ...$options) Sort the elements in a list, set or sorted set. <https://redis.io/commands/sort>
 * @method mixed spop($key, $count = null) Remove and return one or multiple random members from a set. <https://redis.io/commands/spop>
 * @method mixed srandmember($key, $count = null) Get one or multiple random members from a set. <https://redis.io/commands/srandmember>
 * @method mixed srem($key, ...$members) Remove one or more members from a set. <https://redis.io/commands/srem>
 * @method mixed strlen($key) Get the length of the value stored in a key. <https://redis.io/commands/strlen>
 * @method mixed subscribe(...$channels) Listen for messages published to the given channels. <https://redis.io/commands/subscribe>
 * @method mixed sunion(...$keys) Add multiple sets. <https://redis.io/commands/sunion>
 * @method mixed sunionstore($destination, ...$keys) Add multiple sets and store the resulting set in a key. <https://redis.io/commands/sunionstore>
 * @method mixed sync() Internal command used for replication. <https://redis.io/commands/sync>
 * @method mixed ttl($key) Get the time to live for a key. <https://redis.io/commands/ttl>
 * @method mixed type($key) Determine the type stored at key. <https://redis.io/commands/type>
 * @method mixed unsubscribe(...$channels) Stop listening for messages posted to the given channels. <https://redis.io/commands/unsubscribe>
 * @method mixed unlink(...$keys) Delete a key asynchronously in another thread. Otherwise it is just as DEL, but non blocking.. <https://redis.io/commands/unlink>
 * @method mixed unwatch() Forget about all watched keys. <https://redis.io/commands/unwatch>
 * @method mixed wait($numslaves, $timeout) Wait for the synchronous replication of all the write commands sent in the context of the current connection. <https://redis.io/commands/wait>
 * @method mixed watch(...$keys) Watch the given keys to determine execution of the MULTI/EXEC block. <https://redis.io/commands/watch>
 * @method mixed zadd($key, ...$options) Add one or more members to a sorted set, or update its score if it already exists. <https://redis.io/commands/zadd>
 * @method mixed zcard($key) Get the number of members in a sorted set. <https://redis.io/commands/zcard>
 * @method mixed zcount($key, $min, $max) Count the members in a sorted set with scores within the given values. <https://redis.io/commands/zcount>
 * @method mixed zincrby($key, $increment, $member) Increment the score of a member in a sorted set. <https://redis.io/commands/zincrby>
 * @method mixed zinterstore($destination, $numkeys, $key, ...$options) Intersect multiple sorted sets and store the resulting sorted set in a new key. <https://redis.io/commands/zinterstore>
 * @method mixed zlexcount($key, $min, $max) Count the number of members in a sorted set between a given lexicographical range. <https://redis.io/commands/zlexcount>
 * @method mixed zrange($key, $start, $stop, $WITHSCORES = null) Return a range of members in a sorted set, by index. <https://redis.io/commands/zrange>
 * @method mixed zrangebylex($key, $min, $max, $LIMIT = null, $offset = null, $count = null) Return a range of members in a sorted set, by lexicographical range. <https://redis.io/commands/zrangebylex>
 * @method mixed zrevrangebylex($key, $max, $min, $LIMIT = null, $offset = null, $count = null) Return a range of members in a sorted set, by lexicographical range, ordered from higher to lower strings.. <https://redis.io/commands/zrevrangebylex>
 * @method mixed zrangebyscore($key, $min, $max, $WITHSCORES = null, $LIMIT = null, $offset = null, $count = null) Return a range of members in a sorted set, by score. <https://redis.io/commands/zrangebyscore>
 * @method mixed zrank($key, $member) Determine the index of a member in a sorted set. <https://redis.io/commands/zrank>
 * @method mixed zrem($key, ...$members) Remove one or more members from a sorted set. <https://redis.io/commands/zrem>
 * @method mixed zremrangebylex($key, $min, $max) Remove all members in a sorted set between the given lexicographical range. <https://redis.io/commands/zremrangebylex>
 * @method mixed zremrangebyrank($key, $start, $stop) Remove all members in a sorted set within the given indexes. <https://redis.io/commands/zremrangebyrank>
 * @method mixed zremrangebyscore($key, $min, $max) Remove all members in a sorted set within the given scores. <https://redis.io/commands/zremrangebyscore>
 * @method mixed zrevrange($key, $start, $stop, $WITHSCORES = null) Return a range of members in a sorted set, by index, with scores ordered from high to low. <https://redis.io/commands/zrevrange>
 * @method mixed zrevrangebyscore($key, $max, $min, $WITHSCORES = null, $LIMIT = null, $offset = null, $count = null) Return a range of members in a sorted set, by score, with scores ordered from high to low. <https://redis.io/commands/zrevrangebyscore>
 * @method mixed zrevrank($key, $member) Determine the index of a member in a sorted set, with scores ordered from high to low. <https://redis.io/commands/zrevrank>
 * @method mixed zscore($key, $member) Get the score associated with the given member in a sorted set. <https://redis.io/commands/zscore>
 * @method mixed zunionstore($destination, $numkeys, $key, ...$options) Add multiple sorted sets and store the resulting sorted set in a new key. <https://redis.io/commands/zunionstore>
 * @method mixed scan($cursor, $MATCH = null, $pattern = null, $COUNT = null, $count = null) Incrementally iterate the keys space. <https://redis.io/commands/scan>
 * @method mixed sscan($key, $cursor, $MATCH = null, $pattern = null, $COUNT = null, $count = null) Incrementally iterate Set elements. <https://redis.io/commands/sscan>
 * @method mixed hscan($key, $cursor, $MATCH = null, $pattern = null, $COUNT = null, $count = null) Incrementally iterate hash fields and associated values. <https://redis.io/commands/hscan>
 * @method mixed zscan($key, $cursor, $MATCH = null, $pattern = null, $COUNT = null, $count = null) Incrementally iterate sorted sets elements and associated scores. <https://redis.io/commands/zscan>
 *
 * @method bool save(array | String $nodeParams)
 * @method bool bgsave(array | String $nodeParams)
 * @method int dbSize(array | String $nodeParams)
 * @method bool bgrewriteaof(array | String $nodeParams)
 * @method int lastSave(array | String $nodeParams)
 * @method string info(null | string $option = null)
 * @method array role(array | String $nodeParams)
 * @method string randomkey(array | String $nodeParams)
 * @method array time(array | String $nodeParams)
 * @method mixed echo (array | String $nodeParams, String $msg)
 * @method mixed rawCommand(array | String $nodeParams, String $command, $arguments)
 * @method mixed cluster(array | String $nodeParams, string $command, $arguments)
 * @method void client(array | String $nodeParams, String $subCmd, String $args)
 * @method array config(array | String $nodeParams, string $operation, string $key, string $value)
 * @method array|int pubsub(array | String $nodeParams, string $keyword, array | string $argument)
 * @method mixed script(array | String $nodeParams, string $command, string $script)
 * @method void slowLog(array | String $nodeParams, string $command, $argument)
 *
 */
class Connection extends Component
{
    public $connectionTimeout = 5.0;

    public $readTimeout = 5.0;

    /**
     * @var array Redis cluster. Defaults is [].
     */
    public $hosts = [];

    /**
     * @var string redis cluster auth, default is NULL.
     */
    public $auth = null;

    /**
     * @var bool persistent link or not, default is false.
     */
    public $persistent = false;

    /**
     * @var RedisCluster
     */
    protected $instance;


    /**
     * Establishes a DB connection.
     * It does nothing if a DB connection has already been established.
     */
    public function init()
    {
        $this->instance = new RedisCluster(null, $this->hosts, $this->connectionTimeout, $this->readTimeout, $this->persistent, $this->auth);
    }

    /**
     * @param $nodeParams
     * @return bool
     */
    public function ping($nodeParams)
    {
        try {
            return $this->instance->ping($nodeParams) === '+PONG';
        } catch (Exception $e) {
            Yii::error('error on auth:' . $e->getMessage());
            return false;
        }
    }

    /**
     * @return bool aways return true
     */
    public function flushdb()
    {
        Yii::warning('flushDB: never flush redis');
        return true;
    }


    /**
     * @return bool aways return true
     */
    public function flushAll()
    {
        Yii::warning('flushAll: never flush redis');
        return true;
    }

    public function __call($name, $params)
    {
        if (method_exists($this->instance, $name))
            return call_user_func_array([$this->instance, $name], $params);
        throw new UnknownMethodException('Calling unknown method: ' . get_class($this) . "::$name()");
    }
}
