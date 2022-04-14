<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\ProduitFournisseur;
use App\Form\ProduitFournisseurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class ProduitFournisseurController extends AbstractController
{
    /**
     * @Route("/produit/fournisseur", name="app_produit_fournisseur")
     */
    public function index(): Response
    {
        $pf = $this->getDoctrine()->getRepository(ProduitFournisseur::class)->join();
        $pf = $this->ordonner($pf);
        return $this->render('produit_fournisseur/index.html.twig', [
            'pf' => $pf
        ]);
    }
    public function ordonner($pf){
        $p = [];
        foreach($pf as $item){
            if($item instanceof ProduitFournisseur){
                if(!$this->verifFournisseur($item,$p)){
                    array_push($p,$item->getIdf());
                    $p[sizeof($p)-1]->{"nomProd"} = $item->getNomprod()->getNomProd();
                }
                else{
                    $p[sizeof($p)-1]->nomProd= $p[sizeof($p)-1]->nomProd.', '.$item->getNomprod()->getNomProd();
                }
            }
        }
        return $p;
    }
    public function verifFournisseur($f,$p){
        for($i=0 ; $i<count($p) ; $i++){
            if($p[$i] instanceof Fournisseur){
                if($f->getidf()->getIdF() == $p[$i]->getIdF()){
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @Route("/produit/fournisseur/addProduitFournisseur", name="addProduitFournisseur")
     */
    public function ajouterProduitFounisseur(Request $request): Response
    {
        $pf = new ProduitFournisseur();
        $form = $this->createForm(ProduitFournisseurType::class, $pf);
        $form->add('Ajouter',SubmitType::class, [
            'attr' => ['class' => 'btn btn-success'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pf);
            $em->flush();
            return $this->redirectToRoute('app_produit_fournisseur');
        }
        return $this->render("produit_fournisseur/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/produit/fournisseur/deleteProduitFournisseur/{id}", name="deleteProduitFournisseur")
     */
    public function deleteProduitFournisseur($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $pf = $this->getDoctrine()->getRepository(ProduitFournisseur::class)->findAll();
        if(sizeof($pf) == 1){
            $f = $this->getDoctrine()->getRepository(ProduitFournisseur::class)->findOneBy(['idf' => $id]);
            $em->remove($f);
            $em->flush();
        }
        else{
            foreach($pf as $item){
                $f = $this->getDoctrine()->getRepository(ProduitFournisseur::class)->findOneBy(['idf' => $id]);
                $em->remove($f);
                $em->flush();
            }
        }
       
        return $this->redirectToRoute('app_produit_fournisseur');
    }

}
