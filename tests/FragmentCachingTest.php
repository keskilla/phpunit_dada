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
     * $cache->cache('test', function(){...}) test sur chaîne de caractère
     * $cache->cache(['','',''] function(){...}) test sur contenu du tableau
     * $cache->conditionif(condition, [] function({...})) test sur condtion et contenu du tableau
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

    /** condition faux */
    // public function testCacheIfWithFalseCondition() {
    //     //mocker le fragment de caching
    //     $cache = $this->getMockBuilder(\Dada\FragmentCaching::class)
    //         ->setConstructorArgs([new FakeCacheAdapter()])
    //         ->setMethods(['cache']) // mapper la méthode cache
    //         ->getMock();    // récupère le mock
    //     $cache->expects($this->never())->method('cache'); // je veux que la méthode cache ne soit jamais appelée
    //     $this->expectOutputString('salut');
    //     $cache->cacheIf(false, 'key', function(){echo 'salut';}); //si la condition est fausse, on ne veut pas activer le cache
    // }

    /** condition vrai */
    // public function testCacheIfWithTrueCondition() {

    //     $cache = $this->getMockBuilder(\Dada\FragmentCaching::class)
    //         ->setConstructorArgs([new FakeCacheAdapter()])
    //         ->setMethods(['cache']) // mapper la méthode cache
    //         ->getMock();    // récupère le mock
    //     $cache->expects($this->once())->method('cache'); // je veux que la méthode cache soit appelée
    //     $cache->cacheIf(true, 'key', function(){echo 'salut';}); //si la condition est vrai, on activer le cache
    // }

}