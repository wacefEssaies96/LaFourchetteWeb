<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\ProduitFournisseur;
use App\Form\RecuType;
use App\Form\ProduitFournisseurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

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
     * @Route("/produit/fournisseur/addpdf", name="addPdf")
     */
    public function addPdf(Request $request){
        $pf = new ProduitFournisseur();
        $form = $this->createForm(RecuType::class,$pf);
        $form->add('Ajouter',SubmitType::class, [
            'attr' => ['class' => 'btn btn-success'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->generatePdf($form->getData()->getnomprod(),$form->getData()->getidf(),$form->getData()->getquantite());
        }
        return $this->render('produit_fournisseur/addpdf.html.twig',[
            'form' => $form->createView()
        ]);
    }

    public function generatePdf($produit, $fournisseur, $quantite){
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $logo = file_get_contents('lafourchette.png');
        $logo64 = base64_encode($logo);
        $html = $this->renderView('produit_fournisseur/recu.html.twig', [
            'f' => $fournisseur,
            'p' => $produit,
            'q' => $quantite,
            'logo' => $logo64
         ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Reçu.pdf", [
            "Attachment" => true
        ]);
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
        if ($form->isSubmitted()) {
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
        $pf = $this->getDoctrine()->getRepository(ProduitFournisseur::class)->findByIdF($id);
        for($i=0 ; $i<sizeof($pf) ; $i++){
            $f = $this->getDoctrine()->getRepository(ProduitFournisseur::class)->findOneBy(['idf' => $id]);
            $em->remove($f);
            $em->flush();
        }
        return $this->redirectToRoute('app_produit_fournisseur');
    }

}
