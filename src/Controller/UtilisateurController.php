<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UtilisateurType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/utilisateur", name="app_utilisateur")
     */
    public function index(): Response
    {
        $utilisateurs = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
     /**
     * @Route("/deleteUtilisateur/{id}", name="deleteUtilisateur")
     */
    public function deleteUtilisateur($id)
    {
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($utilisateur);
        $em->flush();
        return $this->redirectToRoute("app_utilisateur");
    }

     /**
     * @Route("/addUtilisateur", name="addUtilisateur")
     */
    public function addUtilisateur(Request $request)
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur);
            $em->flush();
            return $this->redirectToRoute('app_utilisateur');
        }
        return $this->render("utilisateur/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateUtilisateur/{id}", name="updateUtilisateur")
     */
    public function updateUtilisateur(Request $request,$id)
    {
        $utilisateur = new Utilisateur(null,null);

        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->add('modifier',SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $uploaddir= $this->getParameter('images_directory');

            $image=$request->files->get("utilisateur")["picture"];
               $m='\\';
              $fichier_name = md5(uniqid()) . '.' . $image->guessExtension();
              $image->move($uploaddir,$fichier_name);
               $utilisateur->setPicture("/".$fichier_name);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_utilisateur');
        }
        return $this->render("utilisateur/update.html.twig",array('form'=>$form->createView()));
    }

/**
     * @Route("/adduser", name="adduser")
     */
    public function add_user(Request $request)
    {
        $utilisateur = new Utilisateur('User','Non_bloc');
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
       

        $form->add('Ajouter',SubmitType::class,['attr'=>['class'=>"btn btn-block btn-primary"]]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $uploaddir= $this->getParameter('images_directory');

            $image=$request->files->get("utilisateur")["picture"];
               $m='\\';
              $fichier_name = md5(uniqid()) . '.' . $image->guessExtension();
              $image->move($uploaddir,$fichier_name);
               $utilisateur->setPicture("/".$fichier_name);
             
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur);

            $em->flush();
            return $this->redirectToRoute('app_utilisateur');
        }
        return $this->render("Registre.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/login", name="connexion")
     */
    public function login(Request $request,SessionInterface $session)
    {
        $utilisateur = new Utilisateur(null,null);
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->add('Connexion',SubmitType::class,['attr'=>['class'=>"btn btn-block btn-primary"]]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
       
            $email=$form->get('email')->getData();
            $mdp=$form->get('password')->getData();


            $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->findBy(array('email' => $email,'password'=>$mdp));
            foreach ($utilisateur as $event)
{
   
     
if( $event->getRole()=='Admin')
{
    $this->addFlash(
        'nom',
        $event->getNomPrenom()
    ); 
   
    return $this->redirectToRoute('app_utilisateur');

}
else 
if( $event->getRole()=='User')
{
    $this->addFlash(
        'nom',
        $event->getNomPrenom()
    ); 
   
    return new Response("test");

}
else {
    return $this->render("login.html.twig",array('form'=>$form->createView()));

}
}


         }
        return $this->render("login.html.twig",array('form'=>$form->createView()));
    }


}