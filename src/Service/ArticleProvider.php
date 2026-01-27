<?php

declare(strict_types=1);
namespace App\Service;

class ArticleProvider{
    public function transformData(array $articles): array{
        $data = [];

    foreach ($articles as $article) {
        $data[] = [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'author' => $article->getAuthor(),
        ];
    }

    return $data;
    }
}