<?php
$host = "localhost";
$user = "root";
$pass = "StudentCard2018";

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS myDB";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
} 
    

// select DB
$conn->select_db("myDB");

// remove old tables if exist
$conn->query("DROP TABLE IF EXISTS InventoryTable");
$conn->query("DROP TABLE IF EXISTS Users");
$conn->query("DROP TABLE IF EXISTS ProductTable");
$conn->query("DROP TABLE IF EXISTS SupplierTable");

// Supplier Table
$conn->query("
CREATE TABLE SupplierTable (
    supplier_id INT PRIMARY KEY,
    supplier_name VARCHAR(100),
    address VARCHAR(255),
    phone VARCHAR(20),
    email VARCHAR(100)
)") or die($conn->error);

// product table
$conn->query("
CREATE TABLE Users (
    email VARCHAR(255),
    password VARCHAR(255)
)") or die($conn->error);

// product table
$conn->query("
CREATE TABLE ProductTable (
    product_id INT PRIMARY KEY,
    product_name VARCHAR(100),
    description VARCHAR(255),
    price DECIMAL(10,2),
    quantity INT,
    status CHAR(1),
    supplier_id INT,
    FOREIGN KEY (supplier_id) REFERENCES SupplierTable(supplier_id)
)") or die($conn->error);

// inventory table
$conn->query("
CREATE TABLE InventoryTable (
    inventory_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    product_name VARCHAR(100),
    quantity INT,
    price DECIMAL(10,2),
    status CHAR(1),
    supplier_name VARCHAR(100)
)") or die($conn->error);

// supplier table data
$conn->query("
INSERT INTO Users (email, password) VALUES
('a@a.ca', 'a123')
") or die($conn->error);

// supplier table data
$conn->query("
INSERT INTO SupplierTable (supplier_id, supplier_name, address, phone, email) VALUES
(9512, 'Acme Corporation', '123 Main St', '205-288-8591', 'info@acme-corp.com'),
(8642, 'Xerox Inc.', '456 High St', '505-398-8414', 'info@xrx.com'),
(3579, 'RedPark Ltd.', '789 Park Ave', '604-683-2555', 'info@redpark.ca'),
(7890, 'Samsung', '456 Seoul St', '909-763-4442', 'support@samsung.com'),
(7671, 'LG Electronics', '789 Busan St', '668-286-5378', 'support@lge.kr'),
(9876, 'Toshiba', '246 Osaka St', '90-6378-0835', 'support@toshiba.co.jp'),
(3456, 'Panasonic', '246 Osaka St', '443-887-9967', 'support@panasonic.co.jp'),
(8765, 'Philips', '789 Amsterdam St', '61-483-898-670', 'support@philips.au'),
(1357, 'Sharp', '123 Tokyo St', '80-4745-3107', 'support@sharp.co.jp'),
(9144, 'Fujitsu', '456 Tokyo St', '03-3556-7890', 'support@fujitsu.co.jp'),
(8655, 'Dell', '246 Austin St', '505-351-3181', 'support@dell.com'),
(3592, 'IBM', '456 New York St', '201-335-9423', 'support@ibm.com'),
(7084, 'Acer', '135 Taipei St', '905-926-031', 'support@acer.tw'),
(2345, 'MSI', '789 Mofan St', '943-299-465', 'support@msi.tw'),
(6954, 'Apple', '246 Cupertino St', '202-918-2132', 'support@apple.com'),
(9794, 'Amazon', '246 Seattle St', '555-343-8950', 'support@amazon.com'),
(8692, 'Microsoft', '123 Redmond St', '505-549-0420', 'support@microsoft.com'),
(7807, 'Intel', '2200 Mission College Blvd', '408-646-7611', 'support@intel.com'),
(8672, 'AMD', '246 Santa Clara St', '312-866-2043', 'support@amd.com'),
(4567, 'Qualcomm', '456 San Diego St', '44-7700-087231', 'info@qualcomm.co.uk')
") or die($conn->error);

// product table data 
$conn->query("
INSERT INTO ProductTable (product_id, product_name, description, price, quantity, status, supplier_id) VALUES
(1, 'Camera', 'Camera', 799.9, 50, 'B', 7890),
(2, 'Laptop', 'MacBook Pro', 1799.9, 30, 'A', 9876),
(3, 'Telephone', 'Cordless Phone', 299.99, 40, 'A', 3456),
(4, 'Telephone', 'Home telephone', 99.9, 25, 'A', 8765),
(5, 'TV', 'Plate TV', 799.9, 20, 'C', 9144),
(6, 'TV', 'Plate TV', 1499.99, 5, 'A', 7671),
(7, 'Camera', 'Instant Camera', 179.5, 30, 'C', 8642),
(8, 'Mouse', 'Wireless Mouse', 99.5, 30, 'A', 3579),
(9, 'Telephone', 'Home Telephone', 169.99, 15, 'A', 8692)
") or die($conn->error);

// join inventory table 
$conn->query("
INSERT INTO InventoryTable (product_id, product_name, quantity, price, status, supplier_name)
SELECT
    p.product_id,
    p.product_name,
    p.quantity,
    p.price,
    p.status,
    s.supplier_name
FROM ProductTable p
JOIN SupplierTable s ON p.supplier_id = s.supplier_id
") or die($conn->error);


// display table on site
echo "<h2>Inventory Contents:</h2>";

$result = $conn->query("SELECT * FROM InventoryTable ORDER BY product_id ASC");

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='8'>";
    echo "<tr>
            <th>Inventory ID</th>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Status</th>
            <th>Supplier Name</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['inventory_id']}</td>
                <td>{$row['product_id']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['quantity']}</td>
                <td>\${$row['price']}</td>
                <td>{$row['status']}</td>
                <td>{$row['supplier_name']}</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "No data in InventoryTable.";
}

$conn->close();

?>