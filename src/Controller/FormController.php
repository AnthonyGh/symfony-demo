<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Entity\Category;

class FormController extends AbstractController
{
    /**
     * @Route("/article/create", name="create")
     * @Route("/articles/{id}/edit", name="edit")
     */
    public function createAndUpdateArticle(Article $article = null, Request $request, ObjectManager $manager)
    {
    	
    	if(!$article){
    		$article = new Article();
    	}

    	$form = $this->createFormBuilder($article)
    				 ->add('title')
    				 ->add('content')
                     ->add('category', EntityType::class, [
                        'class' => Category::class, 
                        'choice_label' => 'title'
                    ])
    				 ->add('image')
    				 ->getForm();

    	$form->handleRequest($request);

    	if($form->isSubmitted() && $form->isValid()) {

    		if(!$article->getId()){
    			$article->setCreatedAt(new \DateTime());
    		}
    		
    		$manager->persist($article);
    		$manager->flush();

    		return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
    	}

        return $this->render('form/create.html.twig', [
        	'formArticle' => $form->createView(),
        	'editMode' => $article->getId() !== null,
            'title' => 'Ajouter un article',
        ]);
    }
}
