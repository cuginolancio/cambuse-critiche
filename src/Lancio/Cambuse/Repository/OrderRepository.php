<?php

namespace Lancio\Cambuse\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Lancio\Cambuse\Entity\Product;
use Lancio\Cambuse\Entity\Order;
use Lancio\Cambuse\Entity\OrderProduct;

class OrderRepository {
    
    private $conn;
    
    public function __construct( \Doctrine\DBAL\Connection $conn)
    {      
        $this->conn = $conn;
    }
    
    public function save( Order $order )
    {
        $data = [
            'shop' => $order->getShop(),
            'user_id' => $order->getUser()->getId(),
            'name' => $order->getUser()->getName(),
            'surname' => $order->getUser()->getSurname(),
            'email' => $order->getUser()->getEmail(),
            'phone' => $order->getUser()->getPhone(),
            'scout_group' => $order->getUser()->getScoutGroup(),
            'scout_unit' => $order->getUser()->getScoutUnit(),
            'note' => $order->getNote(),
            'accept' => $order->getAccept(),
            'total' => $order->getTotal(),
        ];
        if ($order->getDatetime()) {
            $data['datetime'] = $order->getDatetime()->format("Y-m-d H:i:s");;
        }
        
        if($order->getId()){
            
            $data['id'] = $order->getId();
            $this->update($data);
            
        }else{
            
            $id = $this->insert($data);
            $order->setId($id);
        }
        
        $this->saveProducts($order);
    }
    
    private function saveProduct($product) {
        if(isset($product['order_product_id'])){
            
            $this->update($product, "order_products");
        }else{
            
            $this->insert($product, "order_products");
        }
    }
    
    public function saveProducts($order)
    {
        $orderId = $order->getId();
        $userId = $order->getUser()->getId();
        
        foreach ($order->getProducts() as $product) {
            $data = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'code' => $product->getCode(),
                'bio' => $product->getBio(),
                'category_id' => $product->getCategoryId(),
//                'category' => $product->getCategory(),
                'pieces_in_package' => $product->getPiecesInPackage(),
                'price' => $product->getPrice(),
                'price_regular' => $product->getPriceRegular(),
                'quantity' => $product->getQuantity(),
                'total' => $product->getTotal(),
                'order_id' => $orderId,
                'user_id' => $userId,
            ];
            if ($product instanceof \Lancio\Cambuse\Entity\OrderProduct && $product->getOrderProductId()) {
                $data['order_product_id'] = $product->getOrderProductId();
            }
            $this->saveProduct($data);
            
        }
    }
    
    public function update($data, $table = 'orders')
    {
        $result = $this->conn->update($table, $data,array("id" => $data['id']));
        
        return $result;
    }
    
    public function insert($data, $table = 'orders')
    {
        $result = $this->conn->insert($table, $data);
        $id = $this->conn->lastInsertId();
        
        return $id;
    }
    
    public function find( $id )
    {
        $sql = "SELECT o.* "
                . "FROM orders o "
                . "WHERE o.id = :order_id";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam("order_id",$id);
        $statement->execute();
        $order = $statement->fetch();
        
        if (!$order) {
            return null;
        }
        
        $products = $this->findProductsByOrderId($order['id']);

        return $this->hidrate($order, $products);  
    }
    
    public function findAllByUserId( $userId )
    {
        $sql = "SELECT o.* "
                . "FROM orders o "
                . "WHERE o.user_id = :userId "
                . "ORDER BY o.id desc";
        $statement = $this->conn->prepare($sql);
        $statement->bindParam("userId",$userId);
        $statement->execute();
        $orders = $statement->fetchAll();
        
        return $this->hidrateArray($orders);  
    }
    
    public function findAll( )
    {
        $sql = "SELECT o.* "
                . "FROM orders o "
                . "ORDER BY o.id desc";
        
        $orders = $this->conn->fetchAll($sql);
        
        return $this->hidrateArray($orders);  
    }
    
    public function findProductsByOrderId( $orderId )
    {
        $sql = "SELECT p.*, c.name as category "
                . "FROM order_products p "
                . "LEFT JOIN categories c on (p.category_id = c.id)  "
                . "WHERE p.order_id = ? "
                . "ORDER BY c.name";
        
        $products  = $this->conn->fetchAll($sql, [$orderId], [\PDO::PARAM_INT]);

        return $this->hidrateProductArray($products); 
    }
    
    protected function hidrateArray(array $ords = array()) 
    {
        $orders = new ArrayCollection;
        foreach ($ords as $ord) {
            $products = $this->findProductsByOrderId($ord['id']);
            
            $orders->add($this->hidrate($ord, $products));
        }
        return $orders; 
    }
    
    protected function hidrate(array $order, \Doctrine\Common\Collections\ArrayCollection $products = null) 
    { 
        return Order::loadFromArray($order, $products);
    }
    protected function hidrateProductArray(array $products = array()) 
    {
        $prods = new ArrayCollection;
        foreach ($products as $product) {
            $prods->add($this->hidrateProduct($product));
        }
        return $prods; 
    }
    
    protected function hidrateProduct(array $product) 
    { 
        return OrderProduct::loadFromArray($product);
    }
}