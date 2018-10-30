<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        // Créer 3 catégorie fakes avec la librairie Faker
        for($i = 1; $i <= 3; $i++){
            $category = new Category();
            $category->setTitle($faker->word())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);


            // Créé entre 4 et 6 articles

            for($j=1; $j <= mt_rand(4, 6); $j++){
                $article = new Article();

                // $faker->paragraphs renvoie un tableau, on le transform en string en joignant chaque paragre par </p><p>
                $content = '<p>'.join($faker->paragraphs(5), '</p><p>').'</p>';


                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);

                $manager->persist($article);


                for($k=1; $k <= mt_rand(4, 10); $k++){
                    $comment = new Comment();

                    // $faker->paragraphs renvoie un tableau, on le transform en string en joignant chaque paragre par </p><p>
                    $content = '<p>'.join($faker->paragraphs(5), '</p><p>').'</p>';

                    // Un commentaire ne peut avoir été mis qu'entre la date d'aujourd'hui, et la date de création de l'article
                    $now = new \DateTime();
                    $interval = $now->diff($article->getCreatedAt());
                    $jours = $interval->days;
                    $minimum = '-'. $jours.' days';

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
