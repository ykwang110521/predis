# predis
A simple redis operation class package.


### install
composer require ykwang110521/predis 


### demo
```php
require 'vendor/autoload.php';

use Ykwang\Predis;
$config['host'] = '127.0.0.1';
$config['port'] = 6379;
$config['prefix'] = 'wrLI_'; // 可以设置为空
$config['auth'] = ''; // 本地默认密码为空
$redis = new Predis($config);
$rand = rand(1,99);
$redis->set('key'.$rand, 'value'.$rand.'--23432423423');
$res = $redis->get('key'.$rand);
var_dump($res);die;
```
