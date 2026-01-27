<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\InformationAboutMe;
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

        $info1 = new InformationAboutMe();
        $info1->setKey('name');
        $info1->setValue('Ania');
        $manager->persist($info1);

        $info2 = new InformationAboutMe();
        $info2->setKey('occupation');
        $info2->setValue('Frontend dev');
        $manager->persist($info2);

        $info3 = new InformationAboutMe();
        $info3->setKey('location');
        $info3->setValue('Poland');
        $manager->persist($info3);

        $info4 = new InformationAboutMe();
        $info4->setKey('hobby');
        $info4->setValue('Cooking and swimming');
        $manager->persist($info4);

        $manager->flush();

    }
}
