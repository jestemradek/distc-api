<?php

namespace App\Controller;

use App\Utils\HaversineDistance;

use Treffynnon\Navigator\Coordinate;
use Treffynnon\Navigator\Distance;
use Treffynnon\Navigator\Distance\Calculator\Haversine;
use Treffynnon\Navigator\Distance\Calculator\Vincenty;
use Treffynnon\Navigator\Distance\Converter\MetreToKilometre;
use Treffynnon\Navigator\LatLong;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DistanceController extends AbstractController
{
    /**
     * @Route("/distance")
     *
     */
    public function distance(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(
                [
                    "status" => "error",
                    "errors" => "invalid json"
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $validator = Validation::createValidator();

        $constraint = new Assert\Collection([
            "latitudeFrom" => [
                new Assert\NotBlank([
                    "message" => "Missing value latituteFrom"
                ]),
                new Assert\Range([
                    "min" => -90,
                    "max" => 90,
                    "notInRangeMessage" => "Invalid latitudeFrom",
                ])
            ],
            "longitudeFrom" => [
                new Assert\NotBlank([
                    "message" => "Missing value longitudeFrom"
                ]),
                new Assert\Range([
                    "min" => -180,
                    "max" => 180,
                    "notInRangeMessage" => "Invalid longitudeFrom"
                ])
            ],
            "latitudeTo" => [
                new Assert\NotBlank([
                    "message" => "Missing value latituteTo"
                ]),
                new Assert\Range([
                    "min" => -90,
                    "max" => 90,
                    "notInRangeMessage" => "Invalid latitudeTo",
                ])
            ],
            "longitudeTo" => [
                new Assert\NotBlank([
                    "message" => "Missing value longitudeTo"
                ]),
                new Assert\Range([
                    "min" => -180,
                    "max" => 180,
                    "notInRangeMessage" => "Invalid longitudeTo"
                ])
                ],
            "calculatingMethod" => new Assert\Optional(
                new Assert\Choice([
                    "choices" => ["haversine", "vincenty"],
                    "message" => "Invalid calculating method"
                ])
            )
        ]);

        $errors = $validator->validate(
            $data,
            $constraint
        );
        
        if (0 !== $errors->count()) {
            $errorsOutput=[];
            foreach ($errors as $error) {
                $errorsOutput[]=$error->getMessage();
            }

            return new JsonResponse([
                "status" => "error",
                "errors" => $errorsOutput
            ]);
        }
        $calculatingMethod = $data["calculatingMethod"] ?? "vincenty";
        
        if ($calculatingMethod == "haversine") {
            $distanceKilometers = HaversineDistance::getDistance(
                (float)$data["latitudeFrom"],
                (float)$data["longitudeFrom"],
                (float)$data["latitudeTo"],
                (float)$data["longitudeTo"]
            );
        } else {
            $distance = new Distance(
                new LatLong(
                    new Coordinate((float)$data["latitudeFrom"]),
                    new Coordinate((float)$data["longitudeFrom"])
                ),
                new LatLong(
                    new Coordinate((float)$data["latitudeTo"]),
                    new Coordinate((float)$data["longitudeTo"])
                )
            );
            $distanceKilometers = round($distance->get(new Vincenty, new MetreToKilometre), 3);
        }

        $distanceMeters = $distanceKilometers * 1000;

        return new JsonResponse(
            [
                "distanceKilometers" => $distanceKilometers,
                "distanceMeters" => $distanceMeters,
                "calculatingMethod" => $calculatingMethod
            ]
        );
    }
}
