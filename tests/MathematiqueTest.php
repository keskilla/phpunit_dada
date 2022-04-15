<?php

class MathematiqueTest extends PHPUnit_Framework_TestCase {

    public function testDouble() {

        $this->assertEquals(4, \Dada\Mathematique::double(2));
   }
   
}