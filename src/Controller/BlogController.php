<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Article;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Symfony\Component\Security\Core\User\UserInterface;


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
    public function showArticle(Article $article, UserInterface $user, Request $request, ObjectManager $manager)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article)
                    //si l'utilisateur est logger, mettre getUsername() de l'objet $user de UserInterface
                    ->setAuthor($user->getUsername());

            $manager->persist($comment);
            $manager->flush($comment);
            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/article.html.twig', [
            
        	'article' => $article,
            'commentForm' => $form->createView()
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
