<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class CommentaireController extends AbstractController
{
    /**
     * @Route("/commentaire", name="app_commentaire")
     */
    public function index(): Response
    {
        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->findAll();
        return $this->render('commentaire/index.html.twig', [
            'commantaires' => $commentaires,
        ]);
    }

        /**
     * @Route("/deleteCommentaire/{id}", name="deleteCommentaire")
     */
    public function deleteCommentaire($id)
    {
        $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($commentaire);
        $em->flush();
        return $this->redirectToRoute("app_commentaire");
    }

     /**
     * @Route("/addCommentaire", name="addCommentaire")
     */
    public function addCommentaire(Request $request)
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('app_commentaire');
        }
        return $this->render("commentaire/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateCommentaire/{id}", name="updateCommentaire")
     */
    public function updateCommentaire(Request $request,$id)
    {
        $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_commentaire');
        }
        return $this->render("commentaire/update.html.twig",array('form'=>$form->createView()));
    }
}
