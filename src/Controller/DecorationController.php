<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Decoration;
use App\Form\DecorationType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
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
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_decoration');
        }
        return $this->render("decoration/update.html.twig",array('form'=>$form->createView()));
    }
}
