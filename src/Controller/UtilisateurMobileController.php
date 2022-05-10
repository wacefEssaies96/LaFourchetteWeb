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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UtilisateurMobileController extends AbstractController
{
   /**
    * @Route("/user/signup",name="app_registre")
    */
public function signupAction(Request $request ,UserPasswordEncoderInterface $passwordEncoder)
{
    $email= $request->query->get("email");
    $name= $request->query->get("nom_prenom");
    $password= $request->query->get("password");
    $role= '[
        "ROLE_USER"
    ] ';
    $verif= "non_bloc";
    $telephone= $request->query->get("telephone");
    $reset_token="reset_token";
    $addresse= $request->query->get("addresse");
    $picture= $request->query->get("picture");
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return new Response("email iiinvalid");
    }

    $user = new User();
    $user->setname($name);
    $user->setEmail($email);
    $user->setPassword(
        $passwordEncoder->encodePassword(
        $user,
        $password

        )
    
);
    $user->setVerif($verif);
    
   // $user->setPicture($picture);
    $user->setTelephone($telephone);
    $user->setRoles(array($role));
    $user->setAddresse($addresse);
    $user->setPicture($picture);
    //dd($user);
    $em=$this->getDoctrine()->getManager();
    $userEmail = $em->getRepository(User::class)->findOneBy(['email'=>$email]);
    //dd($userEmail);
    if($userEmail != null){

        return new Response("email exist déja");
    }

    else{
    try{
        
        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new JsonResponse("Account is created",200);

    }catch(\Exception $ex){
        return new Response("exception",$ex->getMessage());

    }}
}
    /**
    * @Route("user/signin",name="app_loginn")
    */
    public function signinAction(Request $request,SerializerInterface $serializer)
    {
        $email=$request->query->get("email");
        $password=$request->query->get("password");
        $em=$this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email'=>$email]);
        
        if($user){
            if(password_verify($password,$user->getPassword())){
                //$serialize = new serializer([new objectNormalize()]);
                $formatted = $serializer->normalize($user);
                //dd($formatted);
                return new JsonResponse($formatted);

            }
        
        else
        {
            return new Response("Passsword not found");
        }
    }
        else
        {
            return new Response("User not found");
        }
    

    }
    /**
    * @Route("user/editUser",name="app_gestion_profile")
    */
public function editUser(Request $request,UserPasswordEncoderInterface $passwordEncoder){
    //$form->handleRequest($request);
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $id =$request->get("id");
    $email= $request->query->get("email");
    $name= $request->query->get("nom_prenom");
    $password= $request->query->get("password");
    $em=$this->getDoctrine()->getManager();
    $user= $em->getRepository(User::class)->find($id);
   /*
        $image = $form->get('picture')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
            $image->move($this->getParameter('brochures_directory'), $imageName);
     */       
    
    //$user->setPicture($imageName);
    $user->setPassword(
        $passwordEncoder->encodePassword(
        $user,
        $password

        )
    
);
$user->setEmail($email);
   
   
    try{
        
        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new JsonResponse("Success",200);

    }catch(\Exception $ex){
        return new Response("failed",$ex->getMessage());

    }


}
/**
 * @param MailerInterface $mailer
 * @return Response
 * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
 * @Route("/user/sendmailjson/{email}", name="sendmailjson")
 */
public function sendmailjson(Request $request,MailerInterface $mailer,$email){

     $user = $this->getDoctrine()->getRepository(User::class)->findBy(['email'=>$email]);
    $message = (new Email())

    ->from('lafourchette.esprit@gmail.com')
    ->to($email)//    
    ->subject("Confirmation d'Inscription")
    ->text("Creation compte")
    ->html('<p>Votre compte a été créer avec succes</p>');
//dd($email);
    $mailer->send($message);
//dd($mailer);
return new JsonResponse("Success",200);


}

}






















