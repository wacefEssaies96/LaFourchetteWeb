<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * @Route("/utilisateur", name="app_utilisateur")
     */
    public function index(): Response
    {
        $utilisateurs = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
     /**
     * @Route("/deleteUtilisateur/{id}", name="deleteUtilisateur")
     */
    public function deleteUtilisateur($id)
    {
        $utilisateur = $this->getDoctrine()->getRepository(User::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($utilisateur);
        $em->flush();
        return $this->redirectToRoute("app_utilisateur");
    }

     

    /**
     * @Route("/updateUtilisateur/{id}", name="updateUtilisateur")
     */
    public function updateUtilisateur(Request $request,$id)
    {
        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $form = $this->createForm(UserType::class, $user);
        $form->add('modifier',SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            // Set their role
 
            // Save
            $em = $this->getDoctrine()->getManager();
             $em->flush();
            

            return $this->redirectToRoute('app_utilisateur');
        }
        return $this->render("utilisateur/update.html.twig",array('form'=>$form->createView()));

    }
      /**
     * @Route("/bloc/{id}", name="blocuser")
     */
    public function bloc($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->bloc($id);

        return $this->redirectToRoute("app_utilisateur");
    }
      /**
     * @Route("/debloc/{id}", name="deblocuser")
     */
    public function debbloc($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->debloc($id);

        return $this->redirectToRoute("app_utilisateur");
    }
    }
