<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Article;
use App\Service\ArticleProvider;
use PHPUnit\Framework\TestCase;

class ArticleProviderTest extends TestCase
{
    public function testTransformData()
    {
        $article1 = $this->createMock(Article::class);
        $article1->method('getId')->willReturn(1);
        $article1->method('getTitle')->willReturn('Pierwszy artykuł');
        $article1->method('getContent')->willReturn('To jest pierwszy artykuł');
        $article1->method('getAuthor')->willReturn('Ania');

        $article2 = $this->createMock(Article::class);
        $article2->method('getId')->willReturn(2);
        $article2->method('getTitle')->willReturn('Drugi artykuł');
        $article2->method('getContent')->willReturn('To jest drugi artykuł');
        $article2->method('getAuthor')->willReturn('Admin');

        $articles = [$article1, $article2];

        $expected = [
            [
                'id' => 1,
                'title' => 'Pierwszy artykuł',
                'content' => 'To jest pierwszy artykuł',
                'author' => 'Ania',
            ],
            [
                'id' => 2,
                'title' => 'Drugi artykuł',
                'content' => 'To jest drugi artykuł',
                'author' => 'Admin',
            ]
        ];

        $articleProvider = new ArticleProvider();
        $result = $articleProvider->transformData($articles);

        $this->assertEquals($expected, $result);
    }

    public function testTransformDataWithEmptyArray()
    {
    
        $articles = [];
        $expected = [];

        $articleProvider = new ArticleProvider();
        $result = $articleProvider->transformData($articles);

        $this->assertEquals($expected, $result);
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testTransformDataWithSingleArticle()
    {

        $article = $this->createMock(Article::class);
        $article->method('getId')->willReturn(5);
        $article->method('getTitle')->willReturn('Single Article');
        $article->method('getContent')->willReturn('Content here');
        $article->method('getAuthor')->willReturn('Author Name');

        $articles = [$article];

        $expected = [
            [
                'id' => 5,
                'title' => 'Single Article',
                'content' => 'Content here',
                'author' => 'Author Name',
            ]
        ];

        $articleProvider = new ArticleProvider();
        $result = $articleProvider->transformData($articles);

        $this->assertEquals($expected, $result);
        $this->assertCount(1, $result);
    }
}
