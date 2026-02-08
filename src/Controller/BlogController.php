<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ArticleProvider;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;


class BlogController extends AbstractController
{
    private ArticleRepository $articleRepository;
    private ArticleProvider $articleProvider;

    public function __construct(
        ArticleRepository $articleRepository,
        ArticleProvider $articleProvider
    ) {
        $this->articleRepository = $articleRepository;
        $this->articleProvider = $articleProvider;
    }

    #[Route('/main-page', name: 'main_page')]
    public function mainPage(Request $request): Response
    {
        $query = $request->query->get('q');

        if ($query) {
            $articles = $this->articleRepository->searchByText($query);

            return $this->render('main/index.html.twig', [
                'articles' => $articles,
                'query' => $query,
            ]);
        }

        $latestArticle = $this->articleRepository->findLatestArticle();

        return $this->render('main/index.html.twig', [
            'latestArticle' => $latestArticle,
        ]);
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

    #[Route('/api/articles', name: 'api_articles')]
    public function api(): JsonResponse
    {
        $articles = $this->articleRepository->findAll();

        return $this->json(
            $this->articleProvider->transformData($articles)
        );
    }


    }
