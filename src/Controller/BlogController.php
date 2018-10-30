<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;

class BlogController extends AbstractController
{

	/**
     * @Route("/", name="accueil")
     */
    public function accueil()
    {
        return $this->render('blog/accueil.html.twig', [
            'title' => 'RedécouvrirDieu.fr',
        ]);
    }

    /**
     * @Route("/articles", name="articles")
     */
    public function tousLesarticles(ArticleRepository $repo)
    {
    	$articles = $repo->findAll();
        return $this->render('blog/articles.html.twig', [
            'title' => 'Tous les articles',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/articles/{id}", name="article_show")
     */
    public function showArticle(Article $article)
    {
        return $this->render('blog/article.html.twig', [
        	'article' => $article
        ]);
    }


    /**
     * @Route("/exemple", name="exemple")
     */
    public function exemple()
    {
        return $this->render('blog/exemple.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
}
