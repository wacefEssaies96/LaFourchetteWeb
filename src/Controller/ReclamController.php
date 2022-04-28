<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclam;
use App\Notifications\Mailing_Reclam;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Form\ReclamType;
use App\Form\SearchReclamType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
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
     * @Route("/deleteReclam/{id}", name="deleteReclam")
     */
    public function deleteReclam($id)
    {
        $reclam = $this->getDoctrine()->getRepository(Reclam::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($reclam);
        $em->flush();
        $this->addFlash('info','Réclamation supprimée !');
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($reclam);
            $em->flush();
            $this->addFlash('info','Réclamation envyée !');
           // return $this->redirectToRoute('app_reclam');
            return $this->render("Front/Front-base.html.twig");
        }
        return $this->render("reclam/ADD_Reclam.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateReclam/{id}/{u}/{nom}/{typerec}", name="updateReclam")
     */
    public function updateReclam(Request $request,$id,$u,$nom,$typerec , \Swift_Mailer $mailer)
    {
        $reclam = $this->getDoctrine()->getRepository(Reclam::class)->find($id);
        $form = $this->createForm(ReclamType::class, $reclam);
        $form->add('modifier',SubmitType::class, [
            'attr' => ['class' => 'btn btn-success float-right'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()  && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $etat = $reclam->getEtatrec();
            if( $etat=="Traitée"){
              $message = (new \Swift_Message('Réclamation'))
              ->setFrom('lafourchette.esprit@gmail.com')
              ->setTo($u)
              ->setBody(
                  $this->renderView(
                      'emails/Reclam_traitée.html.twig', [
                        'nom' => json_encode($nom),
                        'typerec' => json_encode($typerec),
                       
                    ]
                  ),
                  'text/html'
              )
              ;
              $mailer->send($message);
              $this->addFlash('info','Email envyée !');
            }
            $this->addFlash('info','Réclamation modifiée !');
              
            return $this->redirectToRoute('app_reclam');
        }
        return $this->render("reclam/update.html.twig",array('form'=>$form->createView()));
    }
    /**
     * @Route("/pdf", name="PDF_Reclam", methods={"GET"})
     */
    public function pdf(ReclamRepository $ReclamRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled',true);
       // $pdfOptions->set('enable_html5_parser',true);
       //$pdfOptions->set('tempDir','C:\wamp64\www'); 

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $png = file_get_contents("lafourchette.png");
        $pngbase64 = base64_encode($png);
       // $imgpath='<img src="data:image/png;base64, '.$dataBase64.'">';
        //$HTML='<body><div>'.$imgpath.'</div></body>';
        // Retrieve the HTML generated in our twig file
        
        $html = $this->renderView('reclam/PDF_Reclam.html.twig', [
            'reclams' => $ReclamRepository->findAll(),"img64"=>$pngbase64
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('C6', 'portrait');
        
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (inline view)
        // $dompdf->set_base_path(realpath('Back/plugins/fontawesome-free/css/all.min.css'));
        $dompdf->stream("ListeDesReclamtions.pdf", [
            "reclams" => true
        ]);
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
