<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenement;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EvenementType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;



class EvenementController extends AbstractController
{
    /**
     * @Route("/evenement", name="app_evenement")
     */
    public function index(): Response
    {
        $evenements = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);

    }
    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function indexshow($id): Response
    {
        $evenements = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('idevent' => $id ));
        $utilisateurs = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();
        return $this->render('evenement/detail.html.twig', [
            'e' => $evenements, 'comentaires'=> $commentaires,'utilisateurs'=>$utilisateurs,
        ]);

    }
    /**
     * @Route("/evenementFront", name="app_evenementFront")
     */
    public function indexFront(): Response
    {
        $evenements = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
        return $this->render('front.html.twig', [
            'evenements' => $evenements,
        ]);

    }

    
    /**
     * @Route("/deleteEvenement/{id}", name="deleteEvenement")
     */
    public function deleteEvenement($id)
    {
        $evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($evenement);
        $em->flush();
        return $this->redirectToRoute("app_evenement");
    }

     /**
     * @Route("/addEvenement", name="addEvenement")
     */
    public function addEvenement(Request $request)
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid() ) {
            $image = $form->get('imagee')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('brochures_directory'), $imageName);
            $evenement->setImagee($imageName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();
            return $this->redirectToRoute('app_evenement');
        }
        return $this->render("evenement/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateEvenement/{id}", name="updateEvenement")
     */
    public function updateEvenement(Request $request,$id)
    {
        $evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid() ) {
            $image = $form->get('imagee')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('brochures_directory'), $imageName);
            $evenement->setImagee($imageName);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_evenement');
        }
        return $this->render("evenement/update.html.twig",array('form'=>$form->createView()));
    }




}
