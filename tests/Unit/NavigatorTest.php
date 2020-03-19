<?php

use PHPUnit\Framework\TestCase;
use Treffynnon\Navigator\Coordinate;
use Treffynnon\Navigator\Distance;
use Treffynnon\Navigator\Distance\Calculator\Haversine;
use Treffynnon\Navigator\Distance\Calculator\Vincenty;
use Treffynnon\Navigator\Distance\Converter\MetreToKilometre;
use Treffynnon\Navigator\LatLong;

class NavigatorTest extends TestCase
{
    public function testZeroCordinates()
    {
        $distance = new Distance(
            new LatLong(
                new Coordinate(0),
                new Coordinate(0)
            ),
            new LatLong(
                new Coordinate(0),
                new Coordinate(0)
            )            
        );
        $this->assertSame(
            0,
            $distance->get()
        );
    }

    public function testNewYorkToWarsawHaversineDistance()
    {
        $distance = new Distance(
            new LatLong(
                new Coordinate(40.6976637),
                new Coordinate(-74.1197621)
            ),
            new LatLong(
                new Coordinate(52.2330653),
                new Coordinate(20.9211139)
            )            
        );
        $this->assertSame(
            6856.55,
            round($distance->get(new Haversine, new MetreToKilometre), 3)
        );            
    }

    public function testNewYorkToWarsawVincentyDistance()
    {
        $distance = new Distance(
            new LatLong(
                new Coordinate(40.6976637),
                new Coordinate(-74.1197621)
            ),
            new LatLong(
                new Coordinate(52.2330653),
                new Coordinate(20.9211139)
            )            
        );
        $this->assertSame(
            6875.317,
            round($distance->get(new Vincenty, new MetreToKilometre), 3)
        );            
    }
}