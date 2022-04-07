<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UtilisateurType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_utilisateur');
        }
        return $this->render("utilisateur/update.html.twig",array('form'=>$form->createView()));
    }
}
