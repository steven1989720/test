<?php
// vendor/bin/phpunit tests

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    protected $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
        // Set up database tables if needed
        $this->setUpDatabase();
    }

    protected function tearDown(): void
    {
        // Clean up database after each test
        $this->pdo->exec("TRUNCATE TABLE items");
    }

    protected function setUpDatabase(): void
    {
        // Create tables if they don't exist
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                phone VARCHAR(15),
                `key` CHAR(25) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
    }

    public function testCreateItemInDatabase(): void
    {
        // Ensure the database is empty before the test
        $this->assertEquals(0, $this->getRowCount());

        // Create item using the API endpoint or directly insert into the database
        $stmt = $this->pdo->prepare("INSERT INTO items (name, phone, `key`) VALUES (?, ?, ?)");
        $stmt->execute(['Test Item', '1234567890', 'test_key']);

        // Retrieve the item from the database
        $stmt = $this->pdo->prepare("SELECT * FROM items WHERE name = ?");
        $stmt->execute(['Test Item']);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        // Assert that the item exists in the database with the correct data
        $this->assertEquals('Test Item', $item['name']);
        $this->assertEquals('1234567890', $item['phone']);
        $this->assertEquals('test_key', $item['key']);
    }

    protected function getRowCount(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM items");
        return $stmt->fetchColumn();
    }
}
