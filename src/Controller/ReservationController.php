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

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="app_reservation")
     */
    public function index(): Response
    {
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findAll();
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
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
        $tr=[];
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
        }
        /*
        dump($tableRestos);
        die();
        */
        
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
    
}
