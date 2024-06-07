<?php
// vendor/bin/phpunit tests

use PHPUnit\Framework\TestCase;

class FunctionalityTest extends TestCase
{
    protected $baseUrl = 'http://localhost/api/';

    public function testCreateItem(): void
    {
        $data = array(
            'name' => 'Test Item',
            'phone' => '1234567890',
            'key' => 'test_key'
        );

        $response = $this->postRequest('items', $data);

        $responseData = json_decode($response, true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Item created successfully', $responseData['message']);
        // Add more assertions as needed
    }

    public function testGetItem(): void
    {
        // Assume item with ID 1 exists in the database
        $response = $this->getRequest('items/1');

        $responseData = json_decode($response, true);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(1, $responseData['id']);
        // Add more assertions as needed
    }

    public function testUpdateItem(): void
    {
        $data = array(
            'name' => 'Updated Item',
            'phone' => '9876543210',
            'key' => 'updated_key'
        );

        $response = $this->putRequest('items/1', $data);

        $responseData = json_decode($response, true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Item updated successfully', $responseData['message']);
        // Add more assertions as needed
    }

    public function testDeleteItem(): void
    {
        // Assume item with ID 1 exists in the database
        $response = $this->deleteRequest('items/1');

        $responseData = json_decode($response, true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Item deleted successfully', $responseData['message']);
        // Add more assertions as needed
    }

    protected function postRequest($endpoint, $data): string
    {
        $url = $this->baseUrl . $endpoint;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    protected function getRequest($endpoint): string
    {
        $url = $this->baseUrl . $endpoint;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    protected function putRequest($endpoint, $data): string
    {
        $url = $this->baseUrl . $endpoint;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    protected function deleteRequest($endpoint): string
    {
        $url = $this->baseUrl . $endpoint;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
