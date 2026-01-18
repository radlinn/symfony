<?php

declare(strict_types=1);
namespace App\Service;

class ArticleProvider{
    public function transformData(array $articles): array{
        $transformedData = [];
        foreach ($articles as $article){
            $transformedData['articles'][] =[
                'title' => $article-> getTitle(),
                'content' => substr($article->getContent(), 0, 30) . '...',
                'link' => 'article/' . $article->getId(),
            ];
        }
        return $transformedData;
    }
}