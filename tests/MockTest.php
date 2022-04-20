<?php 

use PHPUnit\Framework\TestCase;
use Dada\Personne;
use Dada\Database;

class MockTest extends TestCase {
    
    public function test_bonjourID() {
        
        $dbmock = $this->getMockBuilder(Database::class)
            ->setMethods(['getPersonneInDatabase'])
            ->getMock();
        // création d'une fake personne
        
        $mockPerson= new stdClass();
        $array = json_decode(json_encode($mockPerson), true); //convertion en tableau
        $array[0]['name'] = 'Mike';
        // on remplace par le mock
        $dbmock->method('getPersonneInDatabase')->willReturn($array);
        $test = new Personne($dbmock);
        $this->assertEquals('Hello Mike',$test->bonjourID(0));

    } 
}