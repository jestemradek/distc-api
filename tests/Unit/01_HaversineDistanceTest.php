<?php

use App\Utils\HaversineDistance;
use PHPUnit\Framework\TestCase;

class HaversineDistanceTest extends TestCase
{
    public function testZeroCordinates()
    {
        $this->assertSame(
            (float) 0,
            HaversineDistance::getDistance(0, 0, 0, 0)
        );
    }

    public function testNewYorkToWarsawDistance()
    {
        $this->assertSame(
            6856.55,
            HaversineDistance::getDistance(40.6976637, -74.1197621, 52.2330653, 20.9211139)
        );
    }
}
