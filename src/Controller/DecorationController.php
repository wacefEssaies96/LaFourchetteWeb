<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Decoration;
use App\Form\DecorationType;
use Symfony\Component\HttpFoundation\Request;

class DecorationController extends AbstractController
{
    /**
     * @Route("/decoration", name="app_decoration")
     */
    public function index(): Response
    {
        $decorations = $this->getDoctrine()->getRepository(Decoration::class)->findAll();
        return $this->render('decoration/index.html.twig', [
            'decorations' => $decorations,
        ]);
    }

            /**
     * @Route("/deleteDecoration/{id}", name="deleteDecoration")
     */
    public function deleteDecoration($id)
    {
        $decoration = $this->getDoctrine()->getRepository(Decoration::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($decoration);
        $em->flush();
        return $this->redirectToRoute("app_decoration");
    }

     /**
     * @Route("/addDecoration", name="addDecoration")
     */
    public function addDecoration(Request $request)
    {
        $decoration = new Decoration();
        $form = $this->createForm(DecorationType::class, $decoration);
        $form->handleRequest($request);

        $decoration->setImaged("imagenotfound.png");
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('imaged')->getData()!= null){
                $image = $form->get('imaged')->getData();
                $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
                $image->move($this->getParameter('brochures_directory'), $imageName);
                $decoration->setImaged($imageName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($decoration);
            $em->flush();
            return $this->redirectToRoute('app_decoration');
        }
        return $this->render("decoration/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateDecoration/{id}", name="updateDecoration")
     */
    public function updateDecoration(Request $request,$id)
    {
        $decoration = $this->getDoctrine()->getRepository(Decoration::class)->find($id);
        $form = $this->createForm(DecorationType::class, $decoration);
        $form->handleRequest($request);
        
        $decoration->setImaged("imagenotfound.png");
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('imaged')->getData()){
                $image = $form->get('imaged')->getData();
                $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
                $image->move($this->getParameter('brochures_directory'), $imageName);
                $decoration->setImaged($imageName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_decoration');
        }
        return $this->render("decoration/update.html.twig",array('form'=>$form->createView()));
    }
    
    /**
     * @Route ("/searchdecoration", name="searchdecoration")
     */
    function searchdecoration(Request $request): Response
    {
        $nom=$request->request->get('searchdecoration');

        $decoration = $this->getDoctrine()->getRepository(Decoration::class)->SearchNomD($nom);

        return $this->render('decoration/index.html.twig', [
            'decorations' => $decoration,
        ]);
    }

    /**
     * @Route ("/tridecoration/{type}", name="tridecoration")
     */
    function tridecoration(Request $request,$type)
    {
        
        $decoration = $this->getDoctrine()->getRepository(Decoration::class)->tridecoration($type);
        /*dump($decoration);die();*/
        return $this->render('decoration/index.html.twig', [
            'decorations' => $decoration,
        ]);
    }
}
