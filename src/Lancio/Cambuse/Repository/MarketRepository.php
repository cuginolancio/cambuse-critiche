<?php

namespace Lancio\Cambuse\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Lancio\Cambuse\Entity\Market;

class MarketRepository {
    
    private $conn;
    
    public function __construct( \Doctrine\DBAL\Connection $conn)
    {      
        $this->conn = $conn;
    }
    
    public function save( $data )
    {
        if(!empty($data['id'])){
            $this->update($data, $data['id']);
        }else{
            $this->insert($data);
        }
    }
    
    public function update($data, $id)
    {
        $result = $this->conn->update('markets', $data, ["id" => $id]);
        
        return $result;
    }
    
    public function insert($data)
    {
        $result = $this->conn->insert('markets', $data);
        
        return $result;
    }
    
    public function find( $id )
    {
        $sql = "SELECT m.* "
                . "FROM markets m "
                . "WHERE m.id = :market_id";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam("market_id",$id);
        $statement->execute();
        $market = $statement->fetch();
        
        return $market; 
    }
    
    public function findAll( )
    {
        $sql = "SELECT m.* "
                . "FROM markets m "
                . "ORDER BY m.name";
        
        $markets = $this->conn->fetchAll($sql);
        
        return $markets; 
    }
    
    public function findAllActive( )
    {
        $sql = "SELECT m.* "
                . "FROM markets m  "
                . "WHERE m.active = 1 "
                . "ORDER BY m.name";
        $markets = $this->conn->fetchAll($sql);
        
        return $this->hidrateArray($markets); 
    }
    
    protected function hidrate(array $market) 
    { 
        return Market::loadFromArray($market);
    }
}