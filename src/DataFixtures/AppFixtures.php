<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $article = new Article();
        $article->setContent(
            'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est expedita adipisci, voluptatibus nulla reiciendis, totam sunt nam blanditiis consectetur corporis enim fugit eligendi ex eos accusamus veniam esse quae perspiciatis?'
        );
        $article->setTitle('To jest artykuł #1');
        $article->setAuthor('Anna');

        $manager->persist($article);

        $article2 = new Article();
        $article2->setContent(
            'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est expedita adipisci, voluptatibus nulla reiciendis, totam sunt nam blanditiis consectetur corporis enim fugit eligendi ex eos accusamus veniam esse quae perspiciatis?'
        );
        $article2->setTitle('To jest artykuł #2');
        $article2->setAuthor('Anna');
        $manager->persist($article2);

        $manager->flush();
    }
}
