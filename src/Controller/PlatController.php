<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Plat;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PlatType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlatController extends AbstractController
{
    /**
     * @Route("/plat", name="app_plat")
     */
    public function index(): Response
    {
        $plats = $this->getDoctrine()->getRepository(Plat::class)->findAll();
        return $this->render('plat/index.html.twig', [
            'plats' => $plats,
        ]);
    }
     /**
     * @Route("/deletePlat/{id}", name="deletePlat")
     */
    public function deletePlat($id)
    {
        $plat = $this->getDoctrine()->getRepository(Plat::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($plat);
        $em->flush();
        return $this->redirectToRoute("app_plat");
    }

     /**
     * @Route("/addPlat", name="addPlat")
     */
    public function addPlat(Request $request)
    {
        $plat = new Plat();
        $form = $this->createForm(PlatType::class, $plat);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($plat);
            $em->flush();
            return $this->redirectToRoute('app_plat');
        }
        return $this->render("plat/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updatePlat/{id}", name="updatePlat")
     */
    public function updatePlat(Request $request,$id)
    {
        $plat = $this->getDoctrine()->getRepository(Plat::class)->find($id);
        $form = $this->createForm(PlatType::class, $plat);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_plat');
        }
        return $this->render("plat/update.html.twig",array('form'=>$form->createView()));
    }
}
