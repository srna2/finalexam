<?php

class ExamDao {

    private $conn;

    /**
     * constructor of dao class
     */
    public function __construct(){
        try {
          /** TODO
           * List parameters such as servername, username, password, schema. Make sure to use appropriate port
           */
          $servername = 'db1.ibu.edu.ba';
          $dbUsername = 'webfinal_24';
          $dbPassword = 'web24finPWD';
          $database = 'webfinal';
          $port = '3306';

          /** TODO
           * Create new connection
           */

          $this->conn = new PDO("mysql:host=$servername;port=$port;dbname=$database", $dbUsername, $dbPassword);

          echo "Connected successfully";
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
    }

    /** TODO
     * Implement DAO method used to get customer information
     */
    public function get_customers(){

      $stmt = $this->conn->prepare("SELECT * FROM customers");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    /** TODO
     * Implement DAO method used to get customer meals
     */
    public function get_customer_meals($customer_id) {

      $stmt = $this->conn->prepare("
      SELECT foods.name AS food_name, foods.brand AS food_brand, meals.created_at AS meal_date
      FROM meals
      JOIN foods ON meals.food_id = foods.id
      WHERE meals.customer_id = :customer_id
      ");
      $stmt->bindParam(':customer_id', $customer_id);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    /** TODO
     * Implement DAO method used to save customer data
     */
    public function add_customer($data){

      $stmt = $this->conn->prepare("INSERT INTO customers (first_name, last_name, birth_date) VALUES (:first_name, :last_name, :birth_date)");
      $stmt->bindParam(':first_name', $data['first_name']);
      $stmt->bindParam(':last_name', $data['last_name']);
      $stmt->bindParam(':birth_date', $data['birth_date']);
      $stmt->execute();
      return $this->conn->lastInsertId();

    }
  

    /** TODO
     * Implement DAO method used to get foods report
     */
    
    public function get_foods_report($limit, $offset) {
      $stmt = $this->conn->prepare("
          SELECT 
              foods.name,
              foods.brand,
              foods.image_url AS image,
              SUM(CASE WHEN nutrients.name = 'Energy' THEN food_nutrients.quantity ELSE 0 END) AS energy,
              SUM(CASE WHEN nutrients.name = 'Protein' THEN food_nutrients.quantity ELSE 0 END) AS protein,
              SUM(CASE WHEN nutrients.name = 'Fat' THEN food_nutrients.quantity ELSE 0 END) AS fat,
              SUM(CASE WHEN nutrients.name = 'Fiber' THEN food_nutrients.quantity ELSE 0 END) AS fiber,
              SUM(CASE WHEN nutrients.name = 'Carbs' THEN food_nutrients.quantity ELSE 0 END) AS carbs
          FROM foods
          LEFT JOIN food_nutrients ON foods.id = food_nutrients.food_id
          LEFT JOIN nutrients ON food_nutrients.nutrient_id = nutrients.id
          GROUP BY foods.id
          LIMIT :limit OFFSET :offset
      ");
      $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
      $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
?>
