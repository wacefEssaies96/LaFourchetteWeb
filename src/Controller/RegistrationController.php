<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request,\Swift_Mailer $mailer)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_USER']);
            $user->setVerif("non_bloc");
            $image = $form->get('picture')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
            $image->move($this->getParameter('brochures_directory'), $imageName);
            $user->setPicture($imageName);
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $message = (new \Swift_Message('TROOD'))
            ->setFrom('wedev122@gmail.com')
            ->setTo($form->get('email')->getData())//
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'registration.html.twig',
                    compact('user')
                ),
                'text/html'
            )
        ;
        $mailer->send($message);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}