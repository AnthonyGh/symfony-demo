<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Form\RegistrationType;


class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
    	$user = new User();
    	$form = $this->createForm(RegistrationType::class, $user);

    	$form->handleRequest($request);

    	if($form->isSubmitted() && $form->isValid()){

    		$hash = $encoder->encodePassword($user, $user->getPassword());

    		$user->setPassword($hash);
            $role = $user->getRoles();
            
            $user->setRoles($role);

    		$manager->persist($user);
    		$manager->flush();

    		return $this->redirectToRoute('security_login');
    	}

        return $this->render('security/registration.html.twig', [
            'title' => 'Enregistrement sur le site',
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/login", name="security_login")
     */
    public function login(){
    	return $this->render('security/login.html.twig', [
            'title' => 'Login'
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){
    	// défini dans security.yaml

    }
}
