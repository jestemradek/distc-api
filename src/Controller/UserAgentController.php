<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserAgentController extends AbstractController
{
    /**
     * @Route("/user-agent")
     */
    public function UserAgent(Request $request)
    {
        return new JsonResponse(
            [
                'user-agent' => $request->headers->get('User-Agent'),
            ]
        );
    }
}
