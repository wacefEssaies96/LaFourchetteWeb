<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Datetimetr;
use App\Form\DatetimetrType;

class DatetimetrController extends AbstractController
{
    /**
     * @Route("/datetimetr", name="app_datetimetr")
     */
    public function index(Request $request): Response
    {
        
        $TRDTR=$request->request->get('TRDTR');
        $VRDTR=$request->request->get('searchdate');
        $datetimetrs = $this->getDoctrine()->getRepository(Datetimetr::class)->findAll();
        /*dd($datetimetrs);*/
        return $this->render('datetimetr/index.html.twig', [
            'datetimetrs' => $datetimetrs,
            'TRDTR' => $TRDTR,
            'searchdate' => $VRDTR,
        ]);
    }

    
     /**
     * @Route("/deleteDate/{id}", name="deleteDate")
     */
    public function deleteDate($id)
    {
        $datetimetr = $this->getDoctrine()->getRepository(Datetimetr::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($datetimetr);
        $em->flush();
        return $this->redirectToRoute("app_datetimetr");
    }

     /**
     * @Route("/addDate", name="addDate")
     */
    public function addDate(Request $request)
    {
        $datetimetr = new Datetimetr();
        $form = $this->createForm(DatetimetrType::class, $datetimetr);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($datetimetr);
            $em->flush();
            return $this->redirectToRoute('app_datetimetr');
        }
        return $this->render("datetimetr/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateDate/{id}", name="updateDate")
     */
    public function updateDate(Request $request,$id)
    {
        $datetimetr = $this->getDoctrine()->getRepository(Datetimetr::class)->find($id);
        $form = $this->createForm(DatetimetrType::class, $datetimetr);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_datetimetr');
        }
        return $this->render("datetimetr/update.html.twig",array('form'=>$form->createView()));
    }


    /**
     * @Route ("/searchdate", name="searchdate")
     */
    function searchdate(Request $request): Response
    {
        $TRDTR=$request->request->get('TRDTR');
        $VRDTR=$request->request->get('searchdate');

        $datetimetrs = $this->getDoctrine()->getRepository(Datetimetr::class)->Search($TRDTR,$VRDTR);

        return $this->render('datetimetr/index.html.twig', [
            'datetimetrs' => $datetimetrs,
            'TRDTR' => $TRDTR,
            'searchdate' => $VRDTR,
        ]);
    }
    
    /**
     * @Route ("/tridate/{type}", name="tridate")
     */
    function tridate(Request $request,$type)
    {
        
        $TRDTR=$request->request->get('TRDTR');
        $VRDTR=$request->request->get('searchdate');

        $datetimetrs = $this->getDoctrine()->getRepository(Datetimetr::class)->tridate($type);
        
        return $this->render('datetimetr/index.html.twig', [
            'datetimetrs' => $datetimetrs,
            'TRDTR' => $TRDTR,
            'searchdate' => $VRDTR,
        ]);
    }
}
