<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-21
 * Time: 下午2:29
 */

namespace App\HttpController;


use App\Utility\Pools\MysqlPool;
use App\Utility\Pools\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Http\AbstractInterface\REST;

class Duck extends REST
{
    public function GETInfo()
    {
        $fd = $this->request()->getSwooleRequest()->fd;
        echo $fd.PHP_EOL;
        $ip = ServerManager::getInstance()->getSwooleServer()->connection_info($fd);
        print_r($ip);
        $this->response()->write('duck info......');
    }

    public function GETRedis()
    {
        $redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj();
        $this->response()->write($redis->get('name'));
    }

    public function GETUsers()
    {
        $mysql = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj();
        $json = $mysql->get('test', null, array('name', 'sex'));
        $this->writeJson(200, $json, '成功');
    }

    public function GETNotify() {
        $this->response()->write('notify...');
    }

    public function GETPublish() {
        $redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj();
        $redis->publish('ch1', 'hello world');
        $this->response()->write('publish...');
    }

    public function onRequest($action): ?bool
    {
        return parent::onRequest($action); // TODO: Change the autogenerated stub
    }
}