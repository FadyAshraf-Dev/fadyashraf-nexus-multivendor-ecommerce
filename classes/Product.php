<?php
// classes/Product.php
require_once __DIR__ . '/Database.php';

class Product {
    private $db;

    // Properties matching your exact Wizard Steps
    public $id;
    public $vendor_id;
    public $name;
    public $short_description;
    public $full_description;
    public $status;
    public $category_id;
    public $brand;
    
    public $cost_price;
    public $selling_price;
    public $discount_type;
    public $discount_value;
    
    public $stock_quantity;
    public $low_stock_threshold;
    
    public $main_image;
    public $gallery_images = [];

    public function __construct() {
        // Automatically fetch the active database connection instance when a product is created
        $this->db = Database::connect();
    }

    /**
     * Creates a raw new product record in the database using the processed wizard array data.
     * @param array $data The sanitized $_POST data array from the wizard form
     * @param int $vendorId The securely validated vendor ID from the active PHP session
     */
    public function create(array $data, int $vendorId) {
        $sql = "INSERT INTO products (
                    vendor_id, product_name, short_desc, full_desc, status, 
                    category_id, brand, cost_price, selling_price, 
                    discount_type, discount_value, stock_quantity, low_stock_alerts
                ) VALUES (
                    :vendor_id, :name, :short_desc, :full_desc, :status, 
                    :category_id, :brand, :cost_price, :selling_price, 
                    :discount_type, :discount_value, :stock_quantity, :low_stock_alerts
                )";

        $stmt = $this->db->prepare($sql);

        // Execute using secure named array bindings to protect against structural exploits
        $stmt->execute([
            ':vendor_id'        => $vendorId,
            ':name'             => $data['product_name'],
            ':short_desc'       => $data['short_des'],
            ':full_desc'        => $data['full_des'],
            ':status'           => $data['status'],
            ':category_id'      => $data['category'],
            ':brand'            => $data['brand'], // varchar string input from your rule change!
            ':cost_price'       => $data['cost_price'],
            ':selling_price'    => $data['selling_price'],
            ':discount_type'    => $data['discount_type'],
            ':discount_value'   => $data['discount_value'] ?? 0,
            ':stock_quantity'   => $data['stock_quantity'],
            ':low_stock_alerts' => $data['low_stock_alerts']
        ]);

        // Capture the newly generated product ID in case we need to save images next
        return $this->db->lastInsertId();
    }

    /**
     * Reads and queries products securely by vendor profile
     */
    public function getAllByVendor(int $vendorId) {
        $sql = "SELECT * FROM products WHERE vendor_id = :vendor_id ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':vendor_id' => $vendorId]);
        return $stmt->fetchAll();
    }
}