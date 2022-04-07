<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\TypeRec;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TypeRecType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TypeRecController extends AbstractController
{
    /**
     * @Route("/type/rec", name="app_type_rec")
     */
    public function index(): Response
    {
        $typeRecs = $this->getDoctrine()->getRepository(TypeRec::class)->findAll();
        return $this->render('type_rec/index.html.twig', [
            'typeRecs' => $typeRecs,
        ]);
    }
     /**
     * @Route("/deleteTypeRec/{id}", name="deleteTypeRec")
     */
    public function deleteTypeRec($id)
    {
        $typeRec = $this->getDoctrine()->getRepository(TypeRec::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeRec);
        $em->flush();
        return $this->redirectToRoute("app_type_rec");
    }

     /**
     * @Route("/addTypeRec", name="addTypeRec")
     */
    public function addTypeRec(Request $request)
    {
        $typeRec = new TypeRec();
        $form = $this->createForm(TypeRecType::class, $typeRec);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // $nom = $form['typerec']->getData(); 
            // var_dump($nom);
            // die ; 
            $typeRec->setTyperec($form['typerec']->getData());  
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeRec);
            $em->flush();
            return $this->redirectToRoute('app_type_rec');
        }
        return $this->render("type_rec/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateTypeRec/{id}", name="updateTypeRec")
     */
    public function updateTypeRec(Request $request,$id)
    {
        $typeRec = $this->getDoctrine()->getRepository(TypeRec::class)->find($id);
        $form = $this->createForm(TypeRecType::class, $typeRec);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_type_rec');
        }
        return $this->render("type_rec/update.html.twig",array('form'=>$form->createView()));
    }
}
