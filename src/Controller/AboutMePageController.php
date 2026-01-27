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
        private InformationAboutMeRepository $infoRepository;

    public function __construct(InformationAboutMeRepository $infoRepository)
    {
        $this->infoRepository = $infoRepository;
    }

    #[Route('/about-me', name: 'app_about_me_page')]
public function index(): JsonResponse
{
    $items = $this->infoRepository->findAll();

    $data = [];
    foreach ($items as $item) {
        $data[$item->getKey()] = $item->getValue();
    }

    return new JsonResponse([
        'data' => $data,
        'statusCode' => 200,
    ]);
}
}
