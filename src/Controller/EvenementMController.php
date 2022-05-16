<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Evenement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Entity\Utilisateur;


class EvenementMController extends AbstractController
{
    /**
     * @Route("/evenement/m", name="app_evenement_m")
     */
    public function index(): Response
    {
        return $this->render('evenement_m/index.html.twig', [
            'controller_name' => 'EvenementMController',
        ]);
    }
  /**
   * @Route("/display", name="display")
   */
  public function allEvenement()
  {
      $evenementM= $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();
      $serializer= new Serializer([new ObjectNormalizer()]);
      $formatted = $serializer->normalize($evenementM);
      return new JsonResponse($formatted);


  }
    /**
     * @Route("/deleteEveneM", name="addpubjson")
     */
    public function deleteevenement(Request $request,NormalizerInterface $Normalizer): Response
    {
        $ide= $request->get("ide");
        $em= $this->getDoctrine()->getManager();
        $evenementM= $em->getRepository(Evenement::class)->find($ide);
        if ($evenementM!=null)
        {
            $em->remove($evenementM);
            $em->flush();
            $serializer= new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize("evenement supprimé ");
            return new JsonResponse($formatted);


        }
        return new JsonResponse("id invaid");



    }
    /**
     * @Route("/detail"  ,name = "details")
     */
     public function details(Request $request){
         $ide= $request->get("ide");
         $em= $this->getDoctrine()->getManager();
         $evenementM= $em->getRepository(Evenement::class)->find($ide);
         $encoder=new JsonEncoder();
         $normalizer= new ObjectNormalizer();
         $normalizer->setCircularReferenceHandler(function($object){
             return $object->getDescription();
         });
         $serializer = new Serializer([$normalizer],[$encoder]);
         $formatted =  $serializer->normalize($evenementM);
         return new JsonResponse($formatted) ;
     }

    /**
     * @Route("/evenement/mail")
     * @param Swift_Mailer $mailer
     */
    public function sendMail(MailerInterface $mailer,Request $request,NormalizerInterface $Normalizer)
    {
        $id = $request->query->get("idu");
        $pf = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);


        $email = (new Email())
            ->from('lafourchette.esprit@gmail.com')
            ->to($pf->getEmail())
            ->subject('Participation evenement!')
            ->text('Bienvenu à l evenement on vous confirme votre participation !')
            ->html('<p>Bienvenu à l evenement on vous confirme votre partcipation</p>');
        $mailer->send($email);
        $jsonContent=$Normalizer->normalize($pf,'json',['groups'=>'post:read']);
        return new Response ("Email envoyé".json_encode($jsonContent));
    }






}
