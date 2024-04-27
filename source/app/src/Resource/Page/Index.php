<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page;

use BEAR\Resource\ResourceObject;

class Index extends ResourceObject
{
    public function onGet(string $name = ''): static
    {
        $this->body['name'] = $name;

        $memcached = new \Memcached();
        $memcached->addServer('bear-memcached', 11211);

        $a = $memcached->get('HELLO');
        $this->body['a'] = $a;

        $r = $memcached->set('HELLO', 'WORLD');
        $this->body['r'] = $r ? 'OK' : 'NG';

        $b = $memcached->get('HELLO');
        $this->body['b'] = $b;

        return $this;
    }
}
