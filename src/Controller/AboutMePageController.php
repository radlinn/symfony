<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
//use App\Service\AboutMeProvider;
use App\Repository\InformationAboutMeRepository;



final class AboutMePageController extends AbstractController
{
    #[Route('/about-me', name: 'app_about_me_page')]
    public function index(): JsonResponse
    {
        $user = $this->getUser();

    return new JsonResponse();
        #'data' => [
        #    'email' => $user?->getEmail(),
        #    'username' => $user?->getUsername(),
        #    'roles' => $user?->getRoles(),
        #],
        #'statusCode' => 200,
    }
    public function __construct(
        private InformationAboutMeRepository $informationAboutMeRepository
    ) {
    }   
}
