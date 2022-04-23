<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reservation;
use App\Entity\Utilisateur;
use App\Entity\Decoration;
use App\Entity\DecorationReservation;
use App\Entity\TableResto;
use App\Entity\ReservationTableResto;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ReservationType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Dompdf\Dompdf;
use Dompdf\Options;


class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="app_reservation")
     */
    public function index(Request $request): Response
    {
        $TRR=$request->request->get('TRR');
        $VRR=$request->request->get('searchreservation');
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findAll();

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
            'TRR' => $TRR,
            'searchreservation' => $VRR,
        ]);
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
        
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservations,
            'tableRestos' => $tr,
            'decorations' => $dc,
        ]);

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
     * @Route("/ConfirmerReservation/{id}", name="ConfirmerReservation")
     */
    public function ConfirmerReservation(Request $request,$id)
    {
        $TRMR=$request->request->get('TRMR');
        $VRR=$request->request->get('searchMesreservation');

        $maxidres = $this->getDoctrine()->getRepository(Reservation::class)->MaxId();
        $tableResto = $this->getDoctrine()->getRepository(TableResto::class)->find($id);
        
        
        $reservation = new Reservation();

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $maxidres=$maxidres[0][1]+1;
            $reservation->setIdr($maxidres);
            
            $rtr = new ReservationTableResto();
            $rtr->setIdr($reservation);
            $rtr->setIdt($tableResto);
            $tableResto->setEtat('Reserver');

            
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            $emrtr= $this->getDoctrine()->getManager();
            $emrtr->persist($rtr);
            $emrtr->flush();
            $emtr= $this->getDoctrine()->getManager();
            $emtr->persist($tableResto);
            $em->flush();
            
        
        
            $reservations = $this->getDoctrine()->getRepository(Reservation::class)->MR($form->get('idu')->getData());
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
        return $this->render("reservation/add.html.twig",array('form'=>$form->createView(),'t' => $tableResto));
    }
    /**
     * @Route("/Mes_Reservations/{idu}", name="Mes_Reservations")
     */
    public function Mes_Reservations(Request $request,$idu): Response
    {
        $TRMR=$request->request->get('TRMR');
        $VRR=$request->request->get('searchMesreservation');

        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->MR($idu);

        return $this->render('reservation/Mes_Reservations.html.twig', [
            'reservations' => $reservations,
            'iduser' => $idu,
            'TRMR' => $TRMR,
            'searchMesreservation' => $VRR,
        ]);
    }
    /**
     * @Route("/front", name="front")
     */
    public function front(): Response
    {
        $tableRestos = $this->getDoctrine()->getRepository(TableResto::class)->TD();
        return $this->render('front.html.twig', [
            'tableRestos' => $tableRestos,
        ]);
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
        $html = $this->renderView('reservation/pdfreservation.html.twig', [
            'reservation' => $reservation,
            'decorations' => $decorations,
            'tableRestos' => $tableRestos,
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
    function searchreservation(Request $request): Response
    {
        $TRR=$request->request->get('TRR');
        $VRR=$request->request->get('searchreservation');

        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->Search($TRR,$VRR);

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservation,
            'TRR' => $TRR,
            'searchreservation' => $VRR,
        ]);
    }

    /**
     * @Route ("/trireservation/{type}", name="trireservation")
     */
    function trireservation(Request $request,$type)
    {
        
        $TRR=$request->request->get('TRR');
        $VRR=$request->request->get('searchreservation');
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->trireservation($type);
        /*dump($reservation);die();*/
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservation,
            'TRR' => $TRR,
            'searchreservation' => $VRR,
        ]);
    }
    
    /**
     * @Route ("/searchMesreservation/{idu}", name="searchMesreservation")
     */
    function searchMesreservation(Request $request,$idu): Response
    {
        $TRMR=$request->request->get('TRMR');
        $VRR=$request->request->get('searchMesreservation');
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->SearchMesRes($idu,$TRMR,$VRR);
        //dd($reservation);

        return $this->render('reservation/Mes_Reservations.html.twig', [
            'reservations' => $reservation,
            'iduser' => $idu,
            'TRMR' => $TRMR,
            'searchMesreservation' => $VRR,
        ]);
    }

    /**
     * @Route ("/triMesreservation/{type}/{idu}", name="triMesreservation")
     */
    function triMesreservation(Request $request,$type,$idu)
    {
        
        $TRMR=$request->request->get('TRMR');
        $VRR=$request->request->get('searchMesreservation');
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->triMesreservation($type,$idu);
        /*dump($reservation);die();*/
        return $this->render('reservation/Mes_Reservations.html.twig', [
            'reservations' => $reservation,
            'iduser' => $idu,
            'TRMR' => $TRMR,
            'searchMesreservation' => $VRR,
        ]);
    }
    
}
