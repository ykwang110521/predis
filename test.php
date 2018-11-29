<?php
require 'vendor/autoload.php';

use Ykwang\Predis;

$config['host'] = '127.0.0.1';
$config['port'] = 6379;
$config['prefix'] = 'wrLI_'; // 可以设置为空
$config['auth'] = ''; // 本地默认密码为空
$redis = new Predis($config);
//$redis->select(rand(0,3));
$redis->set('key','value的'.rand(1,999),60);
$redis->setex('key1', '60ab', 'aaaa');
$res = $redis->get('key');

$redis->del('age');
$exists = $redis->exists('name1');

$redis->set('key','value'.rand(1,999),60);
$redis->expire('key',600);


$redis->set('key1', 60);
$redis->expire('key1',time() + 86400);

$ttl = $redis->ttl('key');

$res = $redis->get('key11');

$res = $redis->incrBy('key11',0);

$arr = array('aaa'=>'aaaa','bbb'=>'bbbbb');
$redis->mset($arr);

$redis->hSet('hash_key','field'.rand(1,99),'value'.rand(1,999));

$value = $redis->hGet('hash_key', 'field70');

$redis->hdel('hash_key', 'field31');

$value = $redis->hExists('hash_key', 'field4qq0');

$res = $redis->hGetAll('hash_key');

$res = $redis->hIncrBy('hash_key','field40',20);

$res = $redis->hLen('hash_key');

$res = $redis->rPush('list_key','value'.rand(1,99));

$res = $redis->rPop('list_key');

$res = $redis->sAdd('set_key1','value'.rand(1,99));

$res = $redis->sAdd('set_key2','value'.rand(1,99));

$res = $redis->sCard('set_key');

$res = $redis->sIsMember('set_key','value151');

$res = $redis->sMembers('set_key');

$res = $redis->sDiff('set_key1','set_key2');

$res = $redis->sInter('set_key1','set_key2');

$res = $redis->srem('set_key', 'value24');

$res = $redis->sRandmember('set_key');

$res = $redis->sPop('set_key');

$rand = rand(1,99);
$res = $redis->zAdd('zset_key', $rand, 'value'.$rand);

$res = $redis->zCard('zset_key');

$res =$redis->zRange('zset_key', 0, -1);

$res =$redis->zRevRange('zset_key', 0, -1);

$res = $redis->zRangeByScore('zset_key', 10,40);

var_dump($res);die;
