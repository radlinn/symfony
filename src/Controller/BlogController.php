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
        
        $latestArticle = $this->articleRepository->findLatestArticle();
        $articles = $this->articleRepository->findAll();
        
        return $this->render('main/index.html.twig', [
            'latestArticle' => $latestArticle,
            ]);
    }

    public function __construct(
        private ArticleRepository $articleRepository,
        private ArticleProvider $articleProvider
    ) {
    }

    #[Route('/articles', name: 'articles')]
public function articles(): Response
{
    return $this->render('articles/articles.html.twig', [
        'articles' => $this->articleRepository->findAll(),
    ]);
}
    #[Route('/articles/{id}', name: 'article_show')]
public function showArticle(int $id): Response
{
    $article = $this->articleRepository->find($id);

    if (!$article) {
        throw $this->createNotFoundException('Artykuł nie istnieje');
    }

    return $this->render('articles/show.html.twig', [
        'article' => $article,
    ]);
}

}
