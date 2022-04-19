<?php 

namespace Dada;

class FragmentCaching {

    /**
    *@var CacheAdapterInterface
    */
    private $cache;

    public function __construct(CacheAdapterInterface $cache) {

        $this->cache = $cache;
    }

    /** retourne une chaine hashée */
    private function hashkey($keys) {

        if(is_array($keys)) {

            $return = [];

            foreach($keys as $k) {
                array_push($return, $this->hashkeyTab($k));
            }

            return implode('-',$return);
        
        }else {

            return $keys;
        }
        
    }

    private function hashkeyTab($key) {

        if(is_bool($key)) {
            
            return $key ? "1" : "0";

        } elseif (is_object($key)){

            return $key->cache_key();

        }else {
            return $key;
        }
    }

    /**
     * 1 - clé et le callback et mise en cache
     * 2 - tester la clé hashée
     */
    public function cache($key, Callable $callback) {

        $key = $this->hashkey($key);
        $value = $this->cache->get($key);

/*         if($value) {
            echo $value;
        }else {
            // mise en cache
            ob_start();
            $callback();
            $content = ob_get_clean();
            $this->cache->set($key,$content);
            echo $content;
        } */

        // OU 

        if(!$value) {
            ob_start();
            $callback();
            $value = ob_get_clean();
            $this->cache->set($key,$value);
        }
        echo $value;
    }
}