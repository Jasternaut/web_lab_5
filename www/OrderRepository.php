<?php

class OrderRepository {
    private $pdo;

    public function __construct() {
        $host = 'db';
        $db   = 'food_service';
        $user = 'root';
        $pass = 'root_password';
        
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public function save(array $data) {
        $sql = "INSERT INTO orders (customer_name, quantity, restaurant, packaging, online_pay) 
                VALUES (:name, :qty, :rest, :pack, :pay)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'qty'  => $data['qty'],
            'rest' => $data['rest'],
            'pack' => $data['pack'],
            'pay'  => $data['pay']
        ]);
    }

    // 1. сортировка по дате (новые сверху)
    public function getAllSorted() {
        return $this->pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
    }

    // 2. фильтр через SQL (например, заказы, где больше 2 блюд)
    public function getLargeOrders($minQuantity = 3) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE quantity >= ? ORDER BY created_at DESC");
        $stmt->execute([$minQuantity]);
        return $stmt->fetchAll();
    }

    // 3. подсчет количества записей (COUNT)
    public function getCount() {
        return $this->pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    }

    // доп. статистика: сумма всех заказанных блюд
    public function getTotalQuantity() {
        return $this->pdo->query("SELECT SUM(quantity) FROM orders")->fetchColumn() ?: 0;
    }
}