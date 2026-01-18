<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ArticleProvider;
use App\Repository\ArticleRepository;

class BlogController extends AbstractController
{
    #[Route('/main-page', name: 'main_page')]
    public function mainPage(): Response
    {
        $articles = $this->articleRepository->findAll();
        $parameters = [];
        if ($articles){
            $parameters = $this->articleProvider->transformData($articles);
        }
        return $this->render('articles/articles.html.twig', $parameters);
    }
    public function __construct(
        private ArticleRepository $articleRepository,
        private ArticleProvider $articleProvider
    ) {
    }
}
