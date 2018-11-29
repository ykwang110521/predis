<?php

namespace Ykwang;

class Predis
{
    // 主机host
    private $host;

    // 端口
    private $port;

    // redis 实例
    private $redis;

    // key前缀
    private $prefix = '';

    private $connected = false;

    // 密码
    private $auth;

    // 超时时间
    private $timeout = 3;

    public function __construct($config = [])
    {
        try {
            $this->redis = new \Redis();
            $this->host = $config['host'] ? $config['host'] : '127.0.0.1';
            $this->port = $config['port'] ? $config['port'] : 6379;
            $this->connected = $this->redis->pconnect($this->host, $this->port, $this->timeout);
        } catch (RedisException $e) {
            print($e->getMessage());
            exit;
        }

        if (isset($config['prefix'])) $this->prefix = $config['prefix'];

        if (isset($config['auth']) && $config['auth']) {
            $this->auth = $config['auth'];
            $this->redis->auth($this->auth);
        }
    }

    /**
     * 选择db
     * @param $id
     */
    public function select($id)
    {
        $this->redis->select((int)$id);
    }

    /**
     * 关闭连接
     */
    public function close()
    {
        return $this->redis->close();
    }

    /**
     * 获取redis实例，用来执行一些未封装的命令
     * @return Redis
     */
    public function getRedis()
    {
        return $this->redis;
    }

    /**
     * 删除一个key
     * @param $key
     */
    public function del($key)
    {
        $this->redis->del($key);
    }

    /**
     * 判断一个key是否存在
     * @param $key
     * @return bool 存在返回1(ture),否则返回0(false)
     */
    public function exists($key)
    {
        return $this->redis->exists($this->prefix.$key);
    }

    /**
     * 设置一个key生存时间，时间单位为秒
     * @param $key
     * @param $expire
     * @return bool 成功返回1(ture),否则返回0(false)
     */
    public function expire($key, $expire)
    {
        return $this->redis->expire($this->prefix.$key, (int)$expire);
    }

    /**
     * 设置一个key生存时间，其中time 为时间戳
     * @param $key
     * @param $time
     * @return bool 成功返回1(ture),否则返回0(false)
     */
    public function expireAt($key, $time)
    {
        return $this->redis->expireAt($this->prefix.$key, $time);
    }

    /**
     * 返回一个key的剩余生存时间
     * @param $key
     * @return int
     */
    public function ttl($key)
    {
        return $this->redis->ttl($this->prefix.$key);
    }

    /**
     * 获取一个key值
     * @param $key
     * @return bool|string
     */
    public function get($key)
    {
        return $this->redis->get($this->prefix.$key);
    }

    /**
     * 设置一个key 字符串值 value（同时整合setex 可以设置key 有效期，单位为秒）
     * @param $key
     * @param $value
     * @param int $expire
     */
    public function set($key, $value, $expire = 0)
    {
        if ($expire == 0) {
            $this->redis->set($this->prefix.$key, $value);
        } else {
            $this->redis->setex($this->prefix.$key, (int)$expire, $value);
        }
    }

    /**
     * 设置一个key 有有效期的字符串value,单位为秒
     * @param $key
     * @param $expire
     * @param $value
     */
    public function setex($key, $expire, $value)
    {
        $this->redis->setex($this->prefix.$key, (int)$expire, $value);
    }

    /**
     * 设置一个key字符串value ，如果可以key已经存在，不做任何操作
     * @param $key
     * @param $value
     */
    public function setnx($key, $value)
    {
        $this->redis->setnx($this->prefix.$key, $value);
    }


    /**
     * key计数，每次加1
     * @param $key
     * @return int
     */
    public function incr($key)
    {
        return $this->redis->incr($this->prefix.$key);
    }

    /**
     * key计数，value为正整数则加相应数，负整数则减相应数
     * @param $key
     * @param $value
     * @return int
     */
    public function incrBy($key, $value = 0)
    {
        return $this->redis->incrBy($this->prefix.$key, (int)$value);
    }

    /**
     * 批量设置多个key,传入为数组
     * @param array $arr
     */
    public function mset($arr = [])
    {
        $this->redis->mset($arr);
    }


    /**
     * 哈希表 key中的域field的值设为value
     * @param $key
     * @param $field
     * @param $value
     * @return int
     */
    public function hSet($key, $field, $value)
    {
        return $this->redis->hSet($this->prefix.$key, $field, $value);
    }

    /**
     * 获取哈希表 key中给定域 field的值
     * @param $key
     * @param $field
     * @return string
     */
    public function hGet($key, $field)
    {
        return $this->redis->hGet($this->prefix.$key, $field);
    }

    /**
     * 获取哈希表 key中，所有的域和值
     * @param $key
     * @return array
     */
    public function hGetAll($key)
    {
        return $this->redis->hGetAll($this->prefix.$key);
    }

    /**
     * 删除哈希表 key 中的指定域，不存在的域将被忽略
     * @param $key
     * @param $field
     */
    public function hDel($key, $field)
    {
        $this->redis->hDel($this->prefix.$key, $field);
    }

    /**
     * 判断哈希表 key 中，给定域 field 是否存在
     * @param $key
     * @param $field
     * @return bool
     */
    public function hExists($key, $field)
    {
        return $this->redis->hExists($this->prefix.$key, $field);
    }

    /**
     * 哈希表 key 中的域 field 的值加上增量 increment,value为正整数则加相应数，负整数则减相应数（前提value里面本身是数字）
     * @param $key
     * @param $field
     * @param $value
     * @return int
     */
    public function hIncrBy($key, $field, $value)
    {
        return $this->redis->hIncrBy($this->prefix.$key, $field, (int)$value);
    }

    /**
     * 获取哈希表 key 中域的数量
     * @param $key
     * @return int
     */
    public function hLen($key)
    {
        return $this->redis->hLen($this->prefix.$key);
    }


    /**
     * 插入到列表 key 的表头
     * @param $key
     * @param $value
     * @return int
     */
    public function lPush($key, $value)
    {
        return $this->redis->lPush($this->prefix.$key, $value);
    }

    /**
     * 移除并返回列表 key 的头元素
     * @param $key
     * @return int
     */
    public function lPop($key)
    {
        return $this->redis->lPop($this->prefix.$key);
    }

    /**
     * 插入到列表 key 的表尾
     * @param $key
     * @param $value
     * @return int
     */
    public function rPush($key, $value)
    {
        return $this->redis->rPush($this->prefix.$key, $value);
    }

    /**
     * 移除并返回列表 key 的尾元素
     * @param $key
     * @return string
     */
    public function rPop($key)
    {
        return $this->redis->rPop($this->prefix.$key);
    }

    /**
     * 返回列表 key 的长度
     * @param $key
     * @return int
     */
    public function lLen($key)
    {
        return $this->redis->lLen($this->prefix.$key);
    }

    /**
     * 往集合添加一个元素
     * @param $key
     * @param $value
     * @return int
     */
    public function sAdd($key, $value)
    {
        return $this->redis->sAdd($this->prefix.$key, $value);
    }

    /**
     * 返回集合的个数
     * @param $key
     * @return int
     */
    public function sCard($key)
    {
        return $this->redis->sCard($this->prefix.$key);
    }

    /**
     * 判断一个value是否在集合里面
     * @param $key
     * @param $value
     * @return bool
     */
    public function sIsMember($key, $value)
    {
        return $this->redis->sIsMember($this->prefix.$key, $value);
    }

    /**
     * 返回集合成员
     * @param $key
     * @return array
     */
    public function sMembers($key)
    {
        return $this->redis->sMembers($this->prefix.$key);
    }

    /**
     * 返回两个集合的差集
     * @param $key1
     * @param $key2
     * @return array
     */
    public function sDiff($key1, $key2)
    {
        return $this->redis->sDiff($this->prefix.$key1, $this->prefix.$key2);
    }

    /**
     * 返回两个集合的交集
     * @param $key1
     * @param $key2
     * @return array
     */
    public function sInter($key1, $key2)
    {
        return $this->redis->sInter($this->prefix.$key1, $this->prefix.$key2);
    }

    /**
     * 从集合移除指定的元素
     * @param $key
     * @param $value
     * @return int
     */
    public function srem($key, $value)
    {
        return $this->redis->sRem($this->prefix.$key, $value);
    }

    /**
     * 随机返回一个集合元素,不会删除
     * @param $key
     * @return array|string
     */
    public function sRandmember($key)
    {
        return $this->redis->sRandMember($this->prefix.$key);
    }


    /**
     * 移除并返回集合中的一个随机元素
     * @param $key
     * @return string
     */
    public function sPop($key)
    {
        return $this->redis->sPop($this->prefix.$key);
    }

    /**
     * 将一个member元素及其score 值加入到有序集 key 当中
     * @param $key
     * @param $score
     * @param $value
     * @return int
     */
    public function zAdd($key, $score, $value)
    {
        return $this->redis->zAdd($this->prefix.$key, $score, $value);
    }

    /**
     * 返回有序集 key 的基数，访问为数值
     * @param $key
     * @return int
     */
    public function zCard($key)
    {
        return $this->redis->zCard($this->prefix.$key);
    }

    /**
     * 返回按 score 值递增(从小到大)来排序的有序集合
     * @param $key
     * @param $start
     * @param $end
     * @return array
     */
    public function zRange($key, $start, $end)
    {
        return $this->redis->zRange($this->prefix.$key, $start, $end);
    }

    /**
     * 返回按 score 值递增(从大到小)来排序的有序集合
     * @param $key
     * @param $start
     * @param $end
     * @return array
     */
    public function zRevRange($key, $start, $end)
    {
        return $this->redis->zRevRange($this->prefix.$key, $start, $end);
    }

    /**
     * 返回有序集 key 中，所有 score 值介于 min 和 max 之间(包括等于 min 或 max )的成员
     * @param $key
     * @param string $min
     * @param string $max
     * @return array
     */
    public function zRangeByScore($key, $min='-inf', $max="+inf")
    {
        return $this->redis->zRangeByScore($this->prefix.$key, $min, $max);
    }
}
