<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Entity\Utilisateur;
use App\Entity\Decoration;
use App\Entity\DecorationReservation;
use App\Entity\TableResto;
use App\Entity\ReservationTableResto;
use App\Entity\Datetimetr;
use App\Entity\DatetimetrTableResto;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ReservationType;
use App\Form\TableRestoType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Data\SearchData;
use App\Form\SearchForm;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ReservationController extends AbstractController
{
    /**
     * @Route("/refreshimg", name="refreshimg")
     */
    public function refreshimg(Request $request)
    {
        $tableResto = new TableResto();
        $form = $this->createForm(TableRestoType::class, $tableResto);
        $form->handleRequest($request);

        $tableResto->setImagetable("imagenotfound.png");
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('imagetable')->getData()!= null){
                $image = $form->get('imagetable')->getData();
                $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
                $image->move($this->getParameter('brochures_directory'), $imageName);
                $tableResto->setImagetable($imageName);
            }

            /*$em = $this->getDoctrine()->getManager();
            $em->persist($tableResto);
            $em->flush();*/
            return $this->redirectToRoute('app_table_resto');
            
        }
        return $this->render("client/index.html.twig",array('form'=>$form->createView()));
    
    }

    /**
     * @Route("/reservation", name="app_reservation")
     */
    public function index(PaginatorInterface $paginator,Request $request): Response
    {
        $TRR=$request->request->get('TRR');
        $VRR=$request->request->get('searchreservation');
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findAll();
        $reservations = $paginator->paginate(
            $reservations,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
            'TRR' => $TRR,
            'searchreservation' => $VRR,
        ]);
    }

    
    /**
     * @Route("/afficherReservation", name="afficherReservation")
     */
    public function afficherReservation(){
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reservations);
        //dd($reservations);
        return new JsonResponse($formatted);
    }
     /**
     * @Route("/deleteMesReservation/{id}/{idu}", name="deleteMesReservation")
     */
    public function deleteMesReservation($id,$idu)
    {
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($reservation);
        $em->flush();
        $this->addFlash('info','Reservation supprimé');
        return $this->redirectToRoute("Mes_Reservations",['idu' => $idu]);
    }
    
     /**
     * @Route("/deleteReservation/{id}", name="deleteReservation")
     */
    public function deleteReservation($id)
    {
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($reservation);
        $em->flush();
        $this->addFlash('info','Reservation supprimé');
        return $this->redirectToRoute("app_reservation");
    }

     /**
     * @Route("/addReservation", name="addReservation")
     */
    public function addReservation(Request $request)
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            $this->addFlash('info','Reservation ajouté');
            return $this->redirectToRoute('app_reservation');
        }
        return $this->render("reservation/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateReservation/{id}", name="updateReservation")
     */
    public function updateReservation(Request $request,$id)
    {
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('info','Reservation modifié');
            return $this->redirectToRoute('app_reservation');
        }
        return $this->render("reservation/update.html.twig",array('form'=>$form->createView()));
    }
    /**
     * @Route("/showReservation/{id}", name="showReservation")
     */
    public function showReservation(Request $request,$id)
    {
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        
        /*$tableRestos = $this->getDoctrine()->getRepository(TableResto::class)->findAll();
        $decorations = $this->getDoctrine()->getRepository(Decoration::class)->findAll();*/
        
        
        $decorations = $this->getDoctrine()->getRepository(DecorationReservation::class)->JRD($id);
        
        $tableRestos = $this->getDoctrine()->getRepository(ReservationTableResto::class)->JRT($id);
        /*$tr=[];
        $i=0;
        foreach($tableRestos as $t)
        {
            if($t instanceof TableResto)
            $tr[$i] = $t;
            $i+=1;
        }
        $dc=[];
        $j=0;
        foreach($decorations as $d)
        {
            if($d instanceof Decoration)
            $dc[$j] = $d;
            $j+=1;
        }*/
        /*
        dump($tableRestos);
        die();
        */
        $tr=$tableRestos;
        $dc=$decorations;

        /*
        if ($reservation.getIdu().getRole() == 'Admin'){
        $pageherite='base.html.twig';
        }else{
        $pageherite='basesansmenu.html.twig';
        }*/
        
        $pageherite='base.html.twig';

        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservations,
            'tableRestos' => $tr,
            'decorations' => $dc,
            'pageherite' => $pageherite,
        ]);

    }
    
    /**
     * @Route("/showMesReservation/{id}", name="showMesReservation")
     */
    public function showMesReservation(Request $request,$id)
    {
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $decorations = $this->getDoctrine()->getRepository(DecorationReservation::class)->JRD($id);
        $tableRestos = $this->getDoctrine()->getRepository(ReservationTableResto::class)->JRT($id);
        
        return $this->render('reservation/showMesRes.html.twig', [
            'reservation' => $reservations,
            'tableRestos' => $tableRestos,
            'decorations' => $decorations,
        ]);

    }
    
    /**
     * @Route("/showMesReservationjson/{id}", name="showMesReservationjson")
     */
    public function showMesReservationjson(Request $request,$id)
    {
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $decorations = $this->getDoctrine()->getRepository(DecorationReservation::class)->JRD($id);
        $tableRestos = $this->getDoctrine()->getRepository(ReservationTableResto::class)->JRT($id);
        
        
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formattedres = $serializer->normalize($reservations);
        $formatteddec= $serializer->normalize($decorations);
        $formattedtres = $serializer->normalize($tableRestos);
        $all=[$formattedres,$formatteddec,$formattedtres];
        
        $formattedall = $serializer->normalize($all);
        return new JsonResponse($formattedall);

    }

    
    /**
     * @Route("/reserver", name="reserver")
     */
    public function reserver(): Response
    {
        $tableRestos = $this->getDoctrine()->getRepository(TableResto::class)->TD();
        
        /*
        dump($tableRestos);
        die();
        */
        return $this->render('client/reservation.html.twig', [
            'tableRestos' => $tableRestos,
        ]);
    }
    /**
     * @param MailerInterface $mailer
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @Route("/ConfirmerReservation/{id}", name="ConfirmerReservation")
     */
    public function ConfirmerReservation(PaginatorInterface $paginator,Request $request,MailerInterface $mailer,$id)
    {
        $TRMR=$request->request->get('TRMR');
        $VRR=$request->request->get('searchMesreservation');

        $maxidres = $this->getDoctrine()->getRepository(Reservation::class)->MaxId();
        $tableResto = $this->getDoctrine()->getRepository(TableResto::class)->find($id);
        $datetimetrs =$this->getDoctrine()->getRepository(DatetimetrTableResto::class)->DTD($id);
        $decorations = $this->getDoctrine()->getRepository(Decoration::class)->findALL();
        
        $reservation = new Reservation();

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        $datereservation=$request->request->get('datereservation');
        $decoration=$request->request->get('decoration');
        if ($form->isSubmitted() && $form->isValid()) {
            $idu=$form->get('idu')->getData();
            $idu=1;
            $datetimetr =$this->getDoctrine()->getRepository(Datetimetr::class)->find($datereservation);

            $maxidres=$maxidres[0][1]+1;
            $reservation->setIdr($maxidres);
            /*$date = new \DateTime('@'.strtotime('now'));*/
            
            $reservation->setDatecreation($datetimetr->getDate());
            $reservation->setDatemodification($datetimetr->getDate());
            
            

            $rtr = new ReservationTableResto();
            $rtr->setIdr($reservation);
            $rtr->setIdt($tableResto);
            $tableResto->setEtat('Reserver');

            $datetimetr->setEtat('Reserver');

            
            $decoration = $this->getDoctrine()->getRepository(Decoration::class)->find($decoration);
            $dr = new DecorationReservation();
            $dr->setIdr($reservation);
            $dr->setIdd($decoration);

            
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            $emrtr= $this->getDoctrine()->getManager();
            $emrtr->persist($rtr);
            $emrtr->flush();
            $emtr= $this->getDoctrine()->getManager();
            $emtr->persist($tableResto);
            $em->flush();
            $emdt= $this->getDoctrine()->getManager();
            $emdt->persist($datetimetr);
            $em->flush();
            $emdr= $this->getDoctrine()->getManager();
            $emdr->persist($dr);
            $em->flush();
            
            $email = (new Email())
            ->from('lafourchette.esprit@gmail.com')
            ->to('iheb.benhelel@esprit.tn')
            ->subject('Reservation effectue')
            ->text('Date de reservation : ')
            ->html('<p>Plus de detaille Consulter votre reservation </p>');
            $mailer->send($email);
            
        
            $reservations = $this->getDoctrine()->getRepository(Reservation::class)->MR($idu);
            $reservations = $paginator->paginate(
                $reservations,
                $request->query->getInt('page', 1),
                4
            );
            return $this->render("reservation/Mes_Reservations.html.twig",
            array(
                'reservations' => $reservations,
                'tableRestos' => $tableResto,
                'iduser' => $reservation->getIdu()->getIdu(),
                'TRMR' => $TRMR,
                'searchMesreservation' => $VRR,
                )
            );
            
        }
        
        return $this->render("reservation/add.html.twig",
        array(
            'form'=>$form->createView(),
            't' => $tableResto,
            'datetimetrs' => $datetimetrs,
            'decorations' => $decorations,
            )
        );
    }
    
    /**
     * @param MailerInterface $mailer
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @Route("/ConfirmerReservationjson/{idt}/{idu}", name="ConfirmerReservationjson")
     * 
     */
    public function ConfirmerReservationjson(Request $request,MailerInterface $mailer,$idt,$idu)
    {
        /*
        $TRMR=$request->request->get('TRMR');
        $VRR=$request->request->get('searchMesreservation');
*/
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($idu);
        $maxidres = $this->getDoctrine()->getRepository(Reservation::class)->MaxId();
        $tableResto = $this->getDoctrine()->getRepository(TableResto::class)->find($idt);
        $datetimetrs =$this->getDoctrine()->getRepository(DatetimetrTableResto::class)->DTD($idt);
        $decorations = $this->getDoctrine()->getRepository(Decoration::class)->findALL();
        
        $reservation = new Reservation();

        $datereservation=$request->query->get('datereservation');
        $decoration=$request->query->get('decoration');
        

            $datetimetr =$this->getDoctrine()->getRepository(Datetimetr::class)->find($datereservation);

            $maxidres=$maxidres[0][1]+1;
            $reservation->setIdr($maxidres);

            $reservation->setDatecreation($datetimetr->getDate());
            $reservation->setDatemodification($datetimetr->getDate());
            
            

            $rtr = new ReservationTableResto();
            $rtr->setIdr($reservation);
            $rtr->setIdt($tableResto);
            $tableResto->setEtat('Reserver');

            $datetimetr->setEtat('Reserver');

            
            $decoration = $this->getDoctrine()->getRepository(Decoration::class)->find($decoration);
            $dr = new DecorationReservation();
            $dr->setIdr($reservation);
            $dr->setIdd($decoration);

            
            /*
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            $emrtr= $this->getDoctrine()->getManager();
            $emrtr->persist($rtr);
            $emrtr->flush();
            $emtr= $this->getDoctrine()->getManager();
            $emtr->persist($tableResto);
            $em->flush();
            $emdt= $this->getDoctrine()->getManager();
            $emdt->persist($datetimetr);
            $em->flush();
            $emdr= $this->getDoctrine()->getManager();
            $emdr->persist($dr);
            $em->flush();
            */
            $email = (new Email())
            ->from('lafourchette.esprit@gmail.com')
            ->to($utilisateur->getEmail())
            ->subject('Reservation effectue')
            ->text('Date de reservation : ')
            ->html('<p>Plus de detaille Consulter votre reservation </p>');
            $mailer->send($email);
            /*
            return $this->render("reservation/Mes_Reservations.html.twig",
            array(
                'reservations' => $reservations,
                'tableRestos' => $tableResto,
                'iduser' => $reservation->getIdu()->getIdu(),
                'TRMR' => $TRMR,
                'searchMesreservation' => $VRR,
                )
            );
            */
            
            
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($idu);
            //dd($reservations);
            return new JsonResponse($formatted);
        
        
    }
    /**
     * @Route("/Mes_Reservations/{idu}", name="Mes_Reservations")
     */
    public function Mes_Reservations(PaginatorInterface $paginator,Request $request,$idu): Response
    {
        $TRMR=$request->request->get('TRMR');
        $VRR=$request->request->get('searchMesreservation');

        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->MR($idu);
        $reservations = $paginator->paginate(
            $reservations,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('reservation/Mes_Reservations.html.twig', [
            'reservations' => $reservations,
            'iduser' => $idu,
            'TRMR' => $TRMR,
            'searchMesreservation' => $VRR,
        ]);
    }
    /**
     * @Route("/Mes_Reservationsjson/{idu}", name="Mes_Reservationsjson")
     */
    public function Mes_Reservationsjson(Request $request,$idu): Response
    {
        /*
        $TRMR=$request->request->get('TRMR');
        $VRR=$request->request->get('searchMesreservation');
        */
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->MR($idu);
       
        /*
        return $this->render('reservation/Mes_Reservations.html.twig', [
            'reservations' => $reservations,
            'iduser' => $idu,
            'TRMR' => $TRMR,
            'searchMesreservation' => $VRR,
        ]);
        */
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reservations);
        //dd($reservations);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/frontiheb", name="front")
     */
    public function front(): Response
    {
        /*$tableRestos = $this->getDoctrine()->getRepository(TableResto::class)->TD();*/
        $tableRestos = $this->getDoctrine()->getRepository(DatetimetrTableResto::class)->TD();
        $evenements = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
        return $this->render('front.html.twig', [
            'tableRestos' => $tableRestos,
            'evenements' => $evenements,
        ]);
    }
    
    /**
     * @Route("/tabledisponiblejson", name="tabledisponible")
     */
    public function tabledisponiblejson()
    {
        
        $tableRestos = $this->getDoctrine()->getRepository(DatetimetrTableResto::class)->TD();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tableRestos);
        //dd($tableRestos);
        return new JsonResponse($formatted);
        
    }
    
    /**
     * @Route ("/pdfreservation/{idr}", name="pdfreservation", methods={"GET"})
     */
    public function pdfreservation($idr): Response
    {
        $pdfOptions = new Options();
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($idr);
        $decorations = $this->getDoctrine()->getRepository(DecorationReservation::class)->JRD($idr);
        $tableRestos = $this->getDoctrine()->getRepository(ReservationTableResto::class)->JRT($idr);

        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        
        $listimg=[];
        $ii=0;
        /*
        foreach($tableRestos as $t){
            //l'image est située au niveau du dossier public
            $png = file_get_contents('asset("uploads/images/" ~ $t.getImageTable())');
            $pngbase64 = base64_encode($png);
            $listimg[$ii]=$pngbase64;
            $ii+=1;
        }
        dd($listimg);*/
        $html = $this->renderView('reservation/pdfreservation.html.twig', [
            'reservation' => $reservation,
            'decorations' => $decorations,
            'tableRestos' => $tableRestos,
            'listimg' => $listimg,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("VotreReservation.pdf", [
            "Attachment" => true
        ]);

    }

     
    /**
     * @Route ("/showpdfreservation/{idr}", name="showpdfreservation", methods={"GET"})
     */
    public function showpdfreservation($idr): Response
    {
        
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($idr);
        $decorations = $this->getDoctrine()->getRepository(DecorationReservation::class)->JRD($idr);
        $tableRestos = $this->getDoctrine()->getRepository(ReservationTableResto::class)->JRT($idr);

        
        return $this->render('reservation/pdfreservation.html.twig', [
            'reservation' => $reservation,
            'decorations' => $decorations,
            'tableRestos' => $tableRestos,
        ]);
        

    }

    
    /**
     * @Route ("/searchreservation", name="searchreservation")
     */
    function searchreservation(PaginatorInterface $paginator,Request $request): Response
    {
        $TRR=$request->request->get('TRR');
        $VRR=$request->request->get('searchreservation');

        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->Search($TRR,$VRR);
        
        
        if($reservations == []){
            if($TRR == 'datecreation'){
                $this->addFlash('info','Il n\'y aucune reservation dont le date de creation  = " '.$VRR.' "');
            }else if($TRR == 'datemodification'){
                $this->addFlash('info','Il n\'y aucune reservation dont le date de modification  = " '.$VRR.' "');
            }else{
                $this->addFlash('info','Il n\'y aucune reservation dont le nom d\'utilisateur  = " '.$VRR.' "');
            }

            $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findAll();
           
        }

        $reservations = $paginator->paginate(
            $reservations,
            $request->query->getInt('page', 1),
            4
        );


        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
            'TRR' => $TRR,
            'searchreservation' => $VRR,
        ]);
    }

    /**
     * @Route ("/trireservation/{type}", name="trireservation")
     */
    function trireservation(PaginatorInterface $paginator,Request $request,$type)
    {
        
        $TRR=$request->request->get('TRR');
        $VRR=$request->request->get('searchreservation');
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->trireservation($type);

        /*dump($reservation);die();*/
        $reservations = $paginator->paginate(
            $reservations,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
            'TRR' => $TRR,
            'searchreservation' => $VRR,
        ]);
    }
    
    /**
     * @Route ("/searchMesreservation/{idu}", name="searchMesreservation")
     */
    function searchMesreservation(PaginatorInterface $paginator,Request $request,$idu): Response
    {
        $TRMR=$request->request->get('TRMR');
        $VRR=$request->request->get('searchMesreservation');
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->SearchMesRes($idu,$TRMR,$VRR);
        
        if($reservations == []){
            if($TRMR == 'datecreation'){
                $this->addFlash('info','Il n\'y aucune reservation dont le date de creation  = " '.$VRR.' "');
            }else{
                $this->addFlash('info','Il n\'y aucune reservation dont le date de modification  = " '.$VRR.' "');
            }

            $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findAll();
           
        }
        $reservations = $paginator->paginate(
            $reservations,
            $request->query->getInt('page', 1),
            4
        );
        //dd($reservation);

        return $this->render('reservation/Mes_Reservations.html.twig', [
            'reservations' => $reservations,
            'iduser' => $idu,
            'TRMR' => $TRMR,
            'searchMesreservation' => $VRR,
        ]);
    }

    /**
     * @Route ("/triMesreservation/{type}/{idu}", name="triMesreservation")
     */
    function triMesreservation(PaginatorInterface $paginator,Request $request,$type,$idu)
    {
        
        $TRMR=$request->request->get('TRMR');
        $VRR=$request->request->get('searchMesreservation');
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->triMesreservation($type,$idu);
        /*dump($reservation);die();*/
        $reservations = $paginator->paginate(
            $reservations,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('reservation/Mes_Reservations.html.twig', [
            'reservations' => $reservations,
            'iduser' => $idu,
            'TRMR' => $TRMR,
            'searchMesreservation' => $VRR,
        ]);
    }
    
}
