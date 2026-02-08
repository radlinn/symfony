<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
//use App\Service\AboutMeProvider;
use App\Repository\InformationAboutMeRepository;
use App\Formatter\ApiResponseFormatter;


final class AboutMePageController extends AbstractController
{
    public function __construct(
        private InformationAboutMeRepository $infoRepository,
        private ApiResponseFormatter $formatter
    ) {}

    #[Route('/api/about-me', name: 'api_about_me')]
    public function api(): JsonResponse
    {
        $items = $this->infoRepository->findAll();

        $data = [];
        foreach ($items as $item) {
            $data[$item->getKey()] = $item->getValue();
        }

        return $this->formatter->success($data);
    }

#[Route('/about-me', name: 'app_about_me_page')]
    public function index(): Response
    {
        $data = $this->infoRepository->findAll();

        return $this->render('about_me/index.html.twig', [
            'data' => $data,
        ]);
    }
}