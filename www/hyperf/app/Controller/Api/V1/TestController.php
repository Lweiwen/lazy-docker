<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Controller\BaseController;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Cache\Annotation\CachePut;
use Hyperf\Redis\Redis;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;

class TestController extends BaseController
{
    protected $cache;

    protected $redis;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->cache = $this->container->get(CacheInterface::class);
        $this->redis = $this->container->get(Redis::class);
    }

    public function index(int $id)
    {
        $url = $this->request->url();
        $url2 = $this->request->fullUrl();
        var_dump($url,$url2);die;
//        $this->redis->set('name', '王美丽');
//        echo $this->redis->get('name');
//
//        $user = [
//            'id'   => 1,
//            'name' => 'user'
//        ];
//        if ($this->cache->get('user')) {
//            echo '获取缓存数据' . PHP_EOL;
//            var_dump($this->cache->get('user'));
//            return $this->response->success('获取缓存数据', $this->cache->get('user'));
//        } else {
//            $this->cache->set('user', $user);
//            return $this->response->success('非缓存数据', $user);
//        }
    }

    public function updateUser(int $id)
    {
        $user = [
            'user_id' => 1,
            'name' => 'lww',
        ];
        return $this->response->success($user);
    }

}