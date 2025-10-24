<?php
class DatabaseHelper {
    static $connect; 

    function connectDB(){
      $host = "localhost";
      $user = "root";
      $pass = "StudentCard2018";
      $db = "myDB";

      $conn = new mysqli($host, $user, $pass, $db);

      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
      $conn->select_db("myDB");
      return ($conn);
    }

    function __construct(){
      if (self::$connect === null){
        self::$connect = $this->connectDB();
      }
    }

    // ================== LOGIN METHODS ==================
    function checkLogin($email, $password){
      $stmt = self::$connect->prepare("SELECT * FROM USERS WHERE email = ? AND password = ?");
      $stmt->bind_param("ss", $email, $password);
      $stmt->execute();
      $result = $stmt->get_result();

      return $result->num_rows > 0;
    }

    // ================== INVENTORY METHODS ==================
    public function searchProducts($query) {
        $stmt = self::$connect->prepare("SELECT * FROM ProductTable WHERE product_name LIKE ? ORDER BY product_id ASC");
        $searchTerm = "%$query%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    public function searchInventory($query) {
        $stmt = self::$connect->prepare("SELECT product_id, product_name, quantity, price, status, supplier_name FROM ProductTable
        INNER JOIN SupplierTable ON SupplierTable.supplier_id=ProductTable.supplier_id
        WHERE product_name LIKE ? ORDER BY product_id ASC
        ");
        $searchTerm = "%$query%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function searchSupplier($query) {
        $stmt = self::$connect->prepare("SELECT * FROM SupplierTable WHERE supplier_name LIKE ? ORDER BY supplier_id ASC");
        $searchTerm = "%$query%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getItem($query, $table) {
      $stmt = null;
      echo $table;
      if ($table === 'InventoryTable'){
        $stmt = self::$connect->prepare("SELECT * FROM InventoryTable WHERE inventory_id = ? ORDER BY product_id ASC");
      } else if ($table === 'ProductTable'){
        $stmt = self::$connect->prepare("SELECT * FROM ProductTable WHERE product_id = ? ORDER BY product_id ASC");
      } else if ($table === 'SupplierTable'){
        $stmt = self::$connect->prepare("SELECT * FROM SupplierTable WHERE supplier_id = ? ORDER BY supplier_Name ASC");
      }
      $stmt->bind_param("s", $query);
      $stmt->execute();
      return $stmt->get_result();
  }

    public function deleteItem($id, $table) {
      try {
      $stmt = null;
      echo $id;
        if ($table === 'ProductTable'){
          $stmt = self::$connect->prepare("DELETE FROM ProductTable WHERE product_id = ?");
        } else if ($table === 'SupplierTable'){
          $stmt = self::$connect->prepare("DELETE FROM SupplierTable WHERE supplier_id = ?");
        }
        $stmt->bind_param("i", $id);

        return $stmt->execute();
      } 
      catch(Exception $e) {
        return null;
      }
    }

    public function updateProduct($id, $product_name, $description, $price, $quantity, $status) {
        $stmt = self::$connect->prepare("UPDATE ProductTable 
        SET product_name = ? , description = ?, price = ?, quantity = ?, status = ?
        WHERE product_id = ?");
        $stmt->bind_param("ssssss", $product_name, $description, $price, $quantity, $status, $id);
        return $stmt->execute();
    }

    public function updateSupplier($id, $supplier_name, $address, $phone, $email) {
        $stmt = self::$connect->prepare("UPDATE SupplierTable 
        SET supplier_name = ? , address = ?, phone = ?, email = ?
        WHERE supplier_id = ?");
        $stmt->bind_param("sssss", $supplier_name, $address, $phone, $email, $id);
        return $stmt->execute();
    }
}
?>