<?php 

namespace Dada;

interface CacheAdapterInterface {

    public function get($key);
    public function set($key, $value);
}