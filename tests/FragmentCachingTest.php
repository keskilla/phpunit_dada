<?php 

class FakeCacheAdapter implements \Dada\CacheAdapterInterface {

    public function get($key)
    {
        return false;
    }

    public function set($key, $value){}
}

class FakeModel {

    private $key;

    public function __construct($key) {
        $this->key = $key;
    }

    public function cache_key() {
        return $this->key;
    }
}

class FragmentCachingTest extends PHPUnit_Framework_TestCase {

    /**
     * $cache = new FragmentCaching($cacheAdapter);
     * $cache->cache('test', function(){...})
     */

    public function testConstructorWithInterface() {

        new \Dada\FragmentCaching(new FakeCacheAdapter());
    }

    // en cache (get)
    public function testCacheWithCache() {
        // nouvelle instance de cache adapter que l'on mock
        // on fait les modification sur get et on retourne l'objet modifier
        $cacheAdapter = $this->getMockBuilder(FakeCacheAdapter::class)->setMethods(['get'])->getMock();
        $cacheAdapter->method('get')->willReturn('en cache');
        // initialisation classic
        $cache = new \Dada\FragmentCaching($cacheAdapter);
        // test : il attend la chaine de caractère : 'en cache'
        $this->expectOutputString('en cache');
        $cache->cache('test', function(){echo 'salut';});
    }

    //sans cache ( get )
    public function testCacheWithoutCache() {

        $cacheAdapter = $this->getMockBuilder(FakeCacheAdapter::class)->setMethods(['get'])->getMock();
        $cacheAdapter->method('get')->willReturn(false);

        $cache = new \Dada\FragmentCaching($cacheAdapter);

        $this->expectOutputString('salut sans cache');
        $cache->cache('test', function(){echo 'salut sans cache';});
    }

    //sans cache ( get , set )
    public function testCacheWithoutCacheSetCache() {

        $cacheAdapter = $this->getMockBuilder(FakeCacheAdapter::class)->setMethods(['get','set'])->getMock();
        $cacheAdapter->method('get')->willReturn(false);
        //résultat attendu : $this->never()->methode..... fonctionne si jamais appelé
        $cacheAdapter->expects($this->once())->method('set')->with('test','salut');
    
        $cache = new \Dada\FragmentCaching($cacheAdapter);
        $cache->cache('test', function(){echo 'salut';});
    }

    public function getInstanceWithExpectedGet($value) {
        $cacheAdapter = $this->getMockBuilder(FakeCacheAdapter::class)
        ->setMethods(['get'])
        ->getMock();
    
        $cacheAdapter->expects($this->once())->method('get')->with($value);
        
        $cache = new \Dada\FragmentCaching($cacheAdapter);
        return $cache;
    }

    /**
     * Mock enable
     * résultat attendu : 1 fois en get avec le paramètre du tableau
     */
    public function testKeyWithArray() {

        $cache = $this->getInstanceWithExpectedGet('test-par-ici');
        $cache->cache(['test', 'par', 'ici'], function(){return false;});
    }

    public function testKeyWithString() {

        $cache = $this->getInstanceWithExpectedGet('test');
        $cache->cache('test', function(){return false;});
    }

    /** prise en compte des boolean */
    public function testKeyWithArrayWithBoolean() {

        $cache = $this->getInstanceWithExpectedGet('test-0-boolean');
        $cache->cache(['test',false,'boolean'], function(){return false;});
    }

    /** prise en compte d'un objet */
    public function testKeyWithArrayWithObject() {

        $fake = new FakeModel('model');
        $cache = $this->getInstanceWithExpectedGet('test-model-boolean');
        $cache->cache(['test',$fake,'boolean'], function(){return false;});
    }

// phpunit --coverage-html coverage
}