<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProduitType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

//@Unique

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="app_produit")
     */
    public function index(): Response
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }
    /**
     * @Route("/deleteProduit/{id}", name="deleteProduit")
     */
    public function deleteProduit($id)
    {
        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute("app_produit");
    }

     /**
     * @Route("/addProduit", name="addProduit")
     */
    public function addProduit(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $image = $form->get('image')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
            $image->move($this->getParameter('brochures_directory'), $imageName);
            $produit->setImage($imageName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('app_produit');
        }
         return $this->render("produit/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateProduit/{id}", name="updateProduit")
     */
    public function updateProduit(Request $request,$id)
    {
        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
            $image->move($this->getParameter('brochures_directory'), $imageName);
            $produit->setImage($imageName);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_produit');
        }
        return $this->render("produit/update.html.twig",array('form'=>$form->createView()));
    }
}
