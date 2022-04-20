<?php 

use PHPUnit\Framework\TestCase;
use Dada\Personne;
use Dada\Database;

class PersonneTest extends TestCase
{
    public function test_bonjour()
    {
        $db = new Database();
        $test = new Personne($db);
        $this->assertEquals('Bonjour', $test->bonjour());
    }

    public function test_bonjourID() {
        $db = new Database();
        $test = new Personne($db);
        $this->assertEquals('Hello Mike', $test->bonjourID(0));
    }
    

}