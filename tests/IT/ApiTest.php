<?php

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    private $http;

    public function setUp(): void
    {
        $this->http = new Client(['base_uri' => 'http://localhost:8000/']);
    }

    public function tearDown(): void
    {
        $this->http = null;
    }

    public function testApiWelcomeMessage()
    {
        $response = $this->http->request('GET', '/');
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

        $this->assertSame(
            "Welcome to DistC API - GPS Distance Calculator",
            json_decode($response->getBody())->{"message"}
        );
    }

    public function testUserAgent()
    {
        $response = $this->http->request('GET', 'user-agent');

        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

        $userAgent = json_decode($response->getBody())->{"user-agent"};
        $this->assertRegexp('/Guzzle/', $userAgent);
    }

    public function testZeroCoordinates()
    {
        $response = $this->http->request(
            'GET',
            'distance',
            [
                "json" => [
                    "latitudeFrom" => "0",
                    "longitudeFrom" => "0",
                    "latitudeTo" => "0",
                    "longitudeTo" => "0"
                ]
            ]
        );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

        $data = json_decode($response->getBody());

        $this->assertEquals("0", $data->{"distanceKilometers"});
        $this->assertEquals("0", $data->{"distanceMeters"});
        $this->assertEquals("vincenty", $data->{"calculatingMethod"});
    }

    public function testNewYorkToWarsawHaversineDistance()
    {
        $response = $this->http->request(
            'GET',
            'distance',
            [
                "json" => [
                    "latitudeFrom" => "40.6976637",
                    "longitudeFrom" => "-74.1197621",
                    "latitudeTo" => "52.2330653",
                    "longitudeTo" => "20.9211139",
                    "calculatingMethod" => "haversine"
                ]
            ]
        );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

        $data = json_decode($response->getBody());

        $this->assertEquals("6856.55", $data->{"distanceKilometers"});
        $this->assertEquals("6856550", $data->{"distanceMeters"});
        $this->assertEquals("haversine", $data->{"calculatingMethod"});
    }

    public function testNewYorkToWarsawVincentyDistance()
    {
        $response = $this->http->request(
            'GET',
            'distance',
            [
                "json" => [
                    "latitudeFrom" => "40.6976637",
                    "longitudeFrom" => "-74.1197621",
                    "latitudeTo" => "52.2330653",
                    "longitudeTo" => "20.9211139"
                ]
            ]
        );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

        $data = json_decode($response->getBody());

        $this->assertEquals("6875.317", $data->{"distanceKilometers"});
        $this->assertEquals("6875317", $data->{"distanceMeters"});
        $this->assertEquals("vincenty", $data->{"calculatingMethod"});
    }
}