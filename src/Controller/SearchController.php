<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\VarDumper\VarDumper;

class SearchController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepository
    ) {}

    #[Route('/search', name: 'app_search')]
public function search(Request $request, ArticleRepository $articleRepository): Response
{
    $query = $request->query->get('q');

    $articles = [];
    if ($query) {
        $articles = $articleRepository->searchByText($query);
    }

    return $this->render('search/index.html.twig', [
        'articles' => $articles,
        'query' => $query,
    ]);
}

}

