<?php

namespace App\Controller;

use App\Repository\PlatRepository;
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
    public function index(Request $request): Response
    {    $te=$request->request->get('te');
        $tt=$request->request->get('searchPlat');
        $plats = $this->getDoctrine()->getRepository(Plat::class)->findAll();
        return $this->render('plat/index.html.twig', [
            'plats' => $plats,
            'te' => $te,
            'searchPlat' => $tt,

        ]);
    }
    /**
     * @Route("/showplat", name="showplat")
     */
    public function showplat(): Response
    {
        $plats = $this->getDoctrine()->getRepository(Plat::class)->findAll();
        return $this->render('Front/showplat.html.twig', [
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
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('imagep')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('brochures_directory'), $imageName);
            $plat->setImagep($imageName);
            $plat->setReference($form->get('nomprod')->getData());
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
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('imagep')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('brochures_directory'), $imageName);
            $plat->setImagep($imageName);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_plat');
        }
        return $this->render("plat/update.html.twig",array('form'=>$form->createView()));
    }
    /**
     * @Route("/searchPlat", name="searchPlat")
     */
    public function searchReclam(Request $request, PlatRepository $PlatRepository): Response
    {
        $te=$request->request->get('te');
        $tt=$request->request->get('searchPlat');
        $plats= $PlatRepository->searchPlat($te,$tt);
        return $this->render('plat/index.html.twig', [
            'plats' => $plats,
            'te' => $te,
            'searchPlat' => $tt,

        ]);
    }
    /**
     * @Route("/triPLat/{type}", name="triPlat" )
     */
    public function triPlat(Request $request,PlatRepository $PlatRepository,$type): Response
    {
        $te=$request->request->get('te');
        $tt=$request->request->get('searchPlat');
        $plats = $PlatRepository->triPlat($type);

        return $this->render('plat/index.html.twig', [
            'plats' => $plats,
            'te' => $te,
            'searchPlat' => $tt,
        ]);

    }
}
