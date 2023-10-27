<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use DateTime;
use DateTimeImmutable;
use App\Entity\Category;
use App\Entity\Comment;





class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = \Faker\Factory::create('fr_FR');

        for ($i=1; $i <=3 ; $i++) { 
            $category = new Category();

        $category->setTitle("The title of Many")
                ->setDescription("lfsdqlfhqsudhfqlsudfhqlsudhfqsjdhfqdf'yvrluqyvh rfreqgfrq qerg ");

        $manager->persist($category);

            
            $content = '<p>' . "dqslfgfgqsdhfgfjdshqflkjqshdfjhqdsfjqhsdfjqhsldfjhqslfjdhlqkjdfhqljdfhq sjqtjiequf qrtgkqhqubgfqhrdfgsrtyh".'</p>';


        for ($j = 1; $j <= mt_rand(4,6); $j++) {
            $article = new Article();
            $article->setTitle("Article Title")
                ->setContent($content)
                ->setImage("https://picsum.photos/600/400")
                ->setCreatedAt(new \DateTimeImmutable())
                ->setCategory($category);
            $manager->persist($article);

            for ($k=1; $k < mt_rand(4,10); $k++) { 
            $comment = new Comment();

            $content = '<p>' . "lfsdqlfhqsudhfqlsudfhqlsudhfqsjdhfqdf'yvrluqyvh rfreqgfrq qerg ".'</p>';
            
            $now = new DateTimeimmutable();
            $interval = $now->diff($article->getCreatedAt());
            $days = $interval->days;
            $minimum = '-' . $days . 'days';

            $comment->setAuthor($faker->name)
                    ->setContent($content)
                    ->setCreatedAt( new \DateTimeimmutable())
                    ->setArticle($article);

            $manager->persist($comment);

            }
        }
        }


        $manager->flush();
    }
}
