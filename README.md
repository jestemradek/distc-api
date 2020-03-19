# DistC API - GPS Distance Calculator

[![Build Status](https://travis-ci.org/jestemradek/distc-api.svg?branch=master)](https://travis-ci.org/github/jestemradek/distc-api)

The "DistC API" is a demonstration application of a distance calculator between two geographical points into Symfony framework.

To calculate the distance very precisely I used the [Vincenty's formulae][2] implemented in the [treffynnon/Navigator][3] library.
I also wanted to show that I can implement the distance calculation method myself. I used the [Haversine formula][4] in HaversineDistance class.

This project is the backend part. I also created the frontend in Vue.js using this API:
link to project in future...

## Requirements

- PHP 7.3 or higher;
- Symfony
- Curl
- and the [usual Symfony application requirements][1].

## Installation

Download project to your PC and install depedencies:

```bash
$ git git@github.com:jestemradek/distc-api.git
$ cd distc-api
$ composer install
```

## Usage

To run API enter this command:

```bash
$ symfony serve
```

If you don't have the Symfony binary installed, run this command:

```bash
$ php -S 127.0.0.1:8000 -t public/
```

If you want to calculate the distance without using the frontend,
you can talk to API using json.

Sample commands:

```bash
$ curl -H "Content-type: application/json" -d '{
    "latitudeFrom": "0",
    "longitudeFrom": "0",
    "latitudeTo": "90",
    "longitudeTo": "180",
    "calculatingMethod": "haversine"
}' 'http://localhost:8000/distance'

$ curl -H "Content-type: application/json" -d '{
    "latitudeFrom": "52.5069704",
    "longitudeFrom": "13.2846516",
    "latitudeTo": "55.5820947",
    "longitudeTo": "37.105356",
    "calculatingMethod": "vincenty"
}' 'http://localhost:8000/distance'
```

[1]: https://symfony.com/doc/current/reference/requirements.html
[2]: https://en.wikipedia.org/wiki/Vincenty%27s_formulae
[3]: https://github.com/treffynnon/Navigator
[4]: https://en.wikipedia.org/wiki/Haversine_formula
