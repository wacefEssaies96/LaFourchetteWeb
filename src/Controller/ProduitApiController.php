<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Entity\Produit;
use Swift_Mailer;
use App\Entity\ProduitFournisseur;
use App\Entity\Fournisseur;

class ProduitApiController extends AbstractController
{
    /**
     * @Route("/json/produit")
     */
    public function afficheProduits(){
        $decorations = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($decorations);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/produit/add")
     */
    public function AddProduit(Request $request)
    {
        $p=new Produit() ;
        $em=$this->getDoctrine()->getManager();
        $p->setNomProd($request->query->get("nomProd"));
        $p->setQuantite($request->query->get("quantite"));
        $p->setPrix($request->query->get("prix"));
        $p->setImage('');
        $em->persist($p);
        $em->flush();
        
        $serializer = new Serializer([new ObjectNormalizer()]);
        $f = $serializer->normalize($p);
        return new JsonResponse ($f);


    }

    /**
     * @Route("/produit/edit")
     */
    public function updatejson(Request $request,NormalizerInterface $Normalizer): Response
    {
        $em=$this->getDoctrine()->getManager();
        $p=$em->getRepository(Produit::class)->find($request->query->get("nomProd"));
        //$p->setNomProd($request->query->get("nomProd"));
        $p->setQuantite($request->query->get("quantite"));
        $p->setPrix($request->query->get("prix"));
        $p->setImage('');
        $em->flush();

        $jsonContent=$Normalizer->normalize($p,'json',['groups'=>'post:read']);
        return new Response ("publication modifiée".json_encode($jsonContent));


    }
    /**
     * @Route("/produit/delete")
     */
    public function deletejson(Request $request,NormalizerInterface $Normalizer): Response
    {
        $em=$this->getDoctrine()->getManager();
        $p=$em->getRepository(Produit::class)->find($request->query->get("nomProd"));
        $em->remove($p);
        $em->flush();

        $jsonContent=$Normalizer->normalize($p,'json',['groups'=>'post:read']);
        return new Response ("publication modifiée".json_encode($jsonContent));
    }

    /**
     * @Route("/produit/commander")
     * @param Swift_Mailer $mailer
     */
    public function sendMail(Swift_Mailer $mailer,Request $request,NormalizerInterface $Normalizer)
    {
        $id = $request->query->get("nomProd");
        $pf = $this->getDoctrine()->getRepository(ProduitFournisseur::class)->join();
        $p = [];
        foreach($pf as $item){
            if($item instanceof ProduitFournisseur && $item->getNomprod()->getNomProd() == $id){
                array_push($p,$item->getIdf()->getIdF());
            }
        }
        $lvlMax = new Fournisseur();
        $lvlMax->setLvl(0);
        foreach($p as $item){
            $f = $this->getDoctrine()->getRepository(Fournisseur::class)->find($item);
            if($f->getLvl() > $lvlMax->getLvl()){
                $lvlMax = $f;
            }
        }
        $message = (new \Swift_Message('Commande'))
            ->setFrom('lafourchette.esprit@gmail.com')
            ->setTo($lvlMax->getEmailF())
            ->setBody(
                $this->renderView(
                    'produit/mail.html.twig', ['produit' => $id]
                ),
                'text/html'
            )
        ;
        $mailer->send($message);
        $jsonContent=$Normalizer->normalize($p,'json',['groups'=>'post:read']);
        return new Response ("Email envoyé".json_encode($jsonContent));
    }
}
