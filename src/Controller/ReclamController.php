<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclam;
use App\Entity\TypeRec;
use App\Entity\Utilisateur;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ReclamType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\ReclamRepository;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReclamController extends AbstractController
{
   
     
  
    /**
     * @Route("/reclam", name="app_reclam")
     */
    public function index(Request $request,ReclamRepository $ReclamRepository): Response
    {
        $te=$request->request->get('te');
        $tt=$request->request->get('searchReclam');
        $reclams= $ReclamRepository->findAll();
        return $this->render('reclam/index.html.twig', [
                'reclams' => $reclams,
                'te' => $te,
                'searchReclam' => $tt,
     
            ]);
    }

    /**
     * @Route("/afficheReclamJSON", name="afficheReclamJSON")
     */
    public function afficheReclamJSON(){
        $reclam = $this->getDoctrine()->getManager()->getRepository(Reclam::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclam);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/afficheTypeReclamJSON", name="afficheTypeReclamJSON")
     */
    public function afficheTypeReclamJSON(){
        $type = $this->getDoctrine()->getManager()->getRepository(TypeRec::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($type);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/afficheReclamUserJSON/{idu}", name="afficheReclamUseJSONr")
     */
    public function afficheReclamUserJSON($idu){
        $reclam = $this->getDoctrine()->getManager()->getRepository(Reclam::class)->findBy(array('idu' => $idu),array('idu' => 'ASC'));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclam);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/pdfreclamation", name="PDF_Reclam", methods={"GET"})
     */
    public function pdfreclamation()
    {
        
        
        $pdfOptions = new Options();
        $reclamation = $this->getDoctrine()->getRepository(Reclam::class)->findAll();
        
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled',true);
        $dompdf = new Dompdf($pdfOptions);
        
        $png = file_get_contents("lafourchette.png");
        $pngbase64 = base64_encode($png);
        
        $html = $this->renderView('reclam/PDF_Reclam.html.twig', [
            'reclams' => $reclamation,
            'logo' => $pngbase64,
        ]);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('C6', 'portrait');
        $dompdf->render();
        $dompdf->stream("ListeDesReclamtions.pdf", [
            "Attachment" => true
        ]);
        
    }
    /**
     * @Route("/addReclamJSON", name="addReclamJSON")
     */

    public function addReclamJSON(Request $request)
    {
        $reclam = new Reclam();
        // $typrec = $request->query->get("typrec");
        $description = $request->query->get("description");
        // $etatrec = $request->query->get("etatrec");
        //$idu = $request->query->get("idu");
        $em = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getRepository(TypeRec::class)->find("Reclamation livraison");
        $reclam->setTyperec($reclamation);

        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find(1);
        $reclam->setIdu($utilisateur);
        $reclam->setDescription($description);
        $reclam->setEtatrec("En attente");
        $em->persist($reclam);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclam);
        return new JsonResponse("reclamation ajouté");

        /*  $reclam = new Reclam();
          $form = $this->createForm(ReclamType::class, $reclam);
          //$form->add('Ajouter',SubmitType::class);
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
              $em = $this->getDoctrine()->getManager();
              $em->persist($reclam);
              $em->flush();
              $this->addFlash('info','Réclamation envyée !');
             // return $this->redirectToRoute('app_reclam');
              return $this->render("Front/Front-base.html.twig");
          }
          return $this->render("reclam/ADD_Reclam.html.twig",array('form'=>$form->createView()));

          */
    }
     /**
     * @Route("/deleteReclam/{id}", name="deleteReclam")
     */
    public function deleteReclam($id)
    {
        $reclam = $this->getDoctrine()->getRepository(Reclam::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($reclam);
        $em->flush();
        return $this->redirectToRoute("app_reclam");
    }

     /**
     * @Route("/addReclam", name="addReclam")
     */
    public function addReclam(Request $request)
    {
        
        $reclam = new Reclam();
        $form = $this->createForm(ReclamType::class, $reclam);
        //$form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cu=$request->request->get('currentuser');
            $us = $this->getDoctrine()->getRepository(Utilisateur::class)->find((int)$cu);
        
            $reclam->setIdu($us);
            //dd($reclam);
            $em = $this->getDoctrine()->getManager();
            $em->persist($reclam);
            $em->flush();
           return $this->redirectToRoute('frontbase');
        }
        return $this->render("reclam/ADD_Reclam.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateReclam/{id}", name="updateReclam")
     */
    public function updateReclam(Request $request,$id)
    {
        $reclam = $this->getDoctrine()->getRepository(Reclam::class)->find($id);
        $form = $this->createForm(ReclamType::class, $reclam);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_reclam');
        }
        return $this->render("reclam/update.html.twig",array('form'=>$form->createView()));
    }
    
    /**
     * @Route("/searchReclam", name="searchReclam")
     */
    public function searchReclam(Request $request, ReclamRepository $ReclamRepository): Response
    {
        $te=$request->request->get('te');
        $tt=$request->request->get('searchReclam');
        $reclams= $ReclamRepository->searchReclam($te,$tt);
        return $this->render('reclam/index.html.twig', [
                'reclams' => $reclams,
                'te' => $te,
                'searchReclam' => $tt,
     
            ]);
    }
    /**
     * @Route("/triReclam/{type}", name="triReclam" )
     */
    public function triReclam(Request $request,ReclamRepository $ReclamRepository,$type): Response
    {
        $te=$request->request->get('te');
        $tt=$request->request->get('searchReclam');
        $reclams = $ReclamRepository->triReclam($type);

        return $this->render('reclam/index.html.twig', [
            'reclams' => $reclams,
            'te' => $te,
            'searchReclam' => $tt,
        ]);

    }
    /**
     * @Route("/statReclam", name="statReclam")
     */
    public function statReclam(ReclamRepository $ReclamRepository){
        $reclams = $ReclamRepository->findAll();
        
        $Pie=[];
        $EtatEnCours=0;
        $EtatEnAttente=0;
        $EtatTraite=0;
        foreach ($reclams as $reclam){
            $v = $reclam->getEtatrec();         
            if ($v == "En cours")
            {
                $EtatEnCours++ ;
            }
            if ($v == "En attente")
            {
                $EtatEnAttente++ ;
            }
            if ($v == "Traitée")
            {
                $EtatTraite++ ;
            }
        }
        $Pie=[$EtatEnAttente,$EtatEnCours,$EtatTraite];

         return $this->render('reclam/StatReclam.html.twig' , [
             'Pie' => json_encode($Pie),
            
         ]);
    }

}
