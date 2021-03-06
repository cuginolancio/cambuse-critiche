<?php

namespace Lancio\Cambuse\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Lancio\Cambuse\Entity\Product;

class ProductRepository {
    
    private $conn;
    private $categories;
    
    public function __construct( \Doctrine\DBAL\Connection $conn)
    {      
        $this->conn = $conn;
    }
    
    public function save( $data )
    {
        unset($data['category']);
            
        if(!empty($data['id'])){
            $this->update($data, $data['id']);
        }else{
            $this->insert($data);
        }
    }
    
    public function update($data, $id)
    {
        $result = $this->conn->update('products', $data,array("id" => $id));
        
        return $result;
    }
    
    public function insert($data)
    {
        $result = $this->conn->insert('products', $data);
        
        return $result;
    }
    
    public function find( $id )
    {
        $sql = "SELECT p.*, c.name as category "
                . "FROM products p "
                . "INNER JOIN categories c on (p.category_id = c.id)  "
                . "WHERE p.id = :product_id";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam("product_id",$id);
        $statement->execute();
        $product = $statement->fetch();
        
        return $product; 
    }
    
    public function findByCode( $code )
    {
        $sql = "SELECT p.*, c.name as category "
                . "FROM products p "
                . "INNER JOIN categories c on (p.category_id = c.id)  "
                . "WHERE p.code = :code";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam("code",$code);
        $statement->execute();
        $product = $statement->fetch();
        
        return $product; 
    }
    
    public function findAll( )
    {
        $sql = "SELECT p.*, c.name as category "
                . "FROM products p "
                . "INNER JOIN categories c on (p.category_id = c.id)  "
                . "ORDER BY c.name, p.name";
        
        $products = $this->conn->fetchAll($sql);
        
        return $products; 
    }
    
    public function findCategories( )
    {
        if(empty($this->categories)){
            $this->categories = array();
            $sql = "SELECT * "
                    . "FROM categories "
                    . "ORDER BY name";

            $categories = $this->conn->fetchAll($sql);
            foreach($categories as $category)
                $this->categories[$category['id']] = $category['name'];
        }
        return $this->categories ; 
    }
    
    public function findAllActive( )
    {
        $sql = "SELECT p.*, c.name as category "
                . "FROM products p "
                . "INNER JOIN categories c on (p.category_id = c.id)  "
                . "WHERE p.active = 1 AND c.active = 1 "
                . "ORDER BY c.name, p.name";
        $products = $this->conn->fetchAll($sql);
        
        return $this->hidrateArray($products); 
    }
    
    public function findActiveByCodes( array $codes )
    {
        $sql = "SELECT p.*, c.name as category "
                . "FROM products p "
                . "INNER JOIN categories c on (p.category_id = c.id)  "
                . "WHERE p.active = 1 AND c.active = 1 "
                . "AND p.code IN (?) "
                . "ORDER BY c.name, p.name";
        
        $statement = $this->conn->executeQuery($sql, [$codes], [\Doctrine\DBAL\Connection::PARAM_STR_ARRAY]);
        $products = $statement->fetchAll();
            
        return $this->hidrateArray($products); 
    }
    
    protected function hidrateArray(array $products = array()) 
    {
        $prods = new ArrayCollection;
        foreach ($products as $product) {
            $prods->add($this->hidrate($product));
        }
        return $prods; 
    }
    
    protected function hidrate(array $product) 
    { 
        return Product::loadFromArray($product);
    }
}