<?php 

namespace Dada;

use Dada\Database;

class Personne
{
    public $db = null;

    public function __construct($db) {

        $this->db = $db;
    }

    public function Bonjour() {
        return 'Bonjour';
    }

    public function bonjourID($id)
    {
        $friend = $this->db->getPersonneInDatabase($id);
        $friendName = $friend[0]['name'];
        return "Hello $friendName";
    }
    
}

$db = new Database();
$personn = new Personne($db);
echo $personn->bonjourID(0);