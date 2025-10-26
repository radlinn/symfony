<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class testController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function index() : Response {
        return new Response(content:'Hello World');
    }
}