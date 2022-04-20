<?php 

namespace Dada;

class Database
{
    public function getPersonInArrawByID($id)
    {
        $personne = array(
            0 => array("id"=>"1", "name"=>"Mike",    "num"=>"9876543210"),
            1 => array("id"=>"2", "name"=>"Carissa", "num"=>"08548596258"),
            2 => array("id"=>"3", "name"=>"Mathew",  "num"=>"784581254"),
        );

        $pos = array_search($id,$personne);
        echo $personne['name'];
        return $pos;
        
        //return sql("select * from person where id = $id limit 1;")[0];
    }
    
    public function getPersonneInDatabase($id) {

        $personne = array(
            0 => array("id"=>"1", "name"=>"Mike", "num"=>"9876543210"),
        );
        
        return $personne;
    }
  
}


