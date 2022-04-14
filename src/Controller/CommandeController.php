<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commande;
use App\Form\CommandeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommandeController extends AbstractController
{
    /**
     * @Route("/commande", name="app_commande")
     */
    public function index(): Response
    {
        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    /**
     * @Route("/deleteCommande/{id}", name="deleteCommande")
     */
    public function deleteCommande($id)
    {
        $commande = $this->getDoctrine()->getRepository(Commande::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($commande); //indiquer object a supprimer
        $em->flush(); // envoyé tout ce qui est persisté a la base de donnne
        return $this->redirectToRoute("app_commande");
    }

     /**
     * @Route("/addCommande", name="addCommande")
     */
    public function addCommande(Request $request)
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush(); //envoyé tout ce qui est persisté a la base de donnne
            return $this->redirectToRoute('app_commande');  
        }
        return $this->render("commande/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateCommande/{id}", name="updateCommande")
     */
    public function updateCommande(Request $request,$id)
    {
        $commande = $this->getDoctrine()->getRepository(Commande::class)->find($id);
        $form = $this->createForm(CommandeType::class, $commande);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_commande');
        }
        return $this->render("commande/update.html.twig",array('form'=>$form->createView()));
    }
}
