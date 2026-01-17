<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\ArticleRepository;

class BlogController extends AbstractController
{
    #[Route('/main-page', name: 'main_page')]
    public function mainPage(): Response
    {
        $articles = $this->articleRepository->findAll();
        dump($articles);
        return new Response(content: "To będzie strona główna");
    }
    public function __construct(
        private ArticleRepository $articleRepository
    ) {
    }
}
