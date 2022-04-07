<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FournisseurController extends AbstractController
{
    /**
     * @Route("/fournisseur", name="app_fournisseur")
     */
    public function index(): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        return $this->render('fournisseur/index.html.twig', [
            'fournisseurs' => $fournisseurs,
        ]);
    }
    /**
     * @Route("/deleteFournisseur/{id}", name="deleteFournisseur")
     */
    public function deleteFournisseur($id)
    {
        $fournisseur = $this->getDoctrine()->getRepository(Fournisseur::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($fournisseur);
        $em->flush();
        return $this->redirectToRoute("app_fournisseur");
    }

     /**
     * @Route("/addFournisseur", name="addFournisseur")
     */
    public function addFournisseur(Request $request)
    {
        $fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fournisseur);
            $em->flush();
            return $this->redirectToRoute('app_fournisseur');
        }
        return $this->render("fournisseur/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateFournisseur/{id}", name="updateFournisseur")
     */
    public function updateFournisseur(Request $request,$id)
    {
        $commande = $this->getDoctrine()->getRepository(Fournisseur::class)->find($id);
        $form = $this->createForm(FournisseurType::class, $commande);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_fournisseur');
        }
        return $this->render("fournisseur/update.html.twig",array('form'=>$form->createView()));
    }

}
