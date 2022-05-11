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

class FournisseurApiController extends AbstractController
{
     /**
     * @Route("/json/fournisseur")
     */
    public function afficheFournisseur(){
        $decorations = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($decorations);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/fournisseur/add")
     */
    public function AddFournisseur(Request $request)
    {
        $p=new Fournisseur() ;
        $em=$this->getDoctrine()->getManager();
        $p->setNomF($request->query->get("nomF"));
        $p->setEmailF($request->query->get("emailF"));
        $p->setTelephoneF($request->query->get("telephoneF"));
        $p->setLvl($request->query->get("lvl"));
        $em->persist($p);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $f = $serializer->normalize($p);
        return new JsonResponse ($f);
    }

    /**
     * @Route("/fournisseur/edit")
     */
    public function updatejson(Request $request,NormalizerInterface $Normalizer): Response
    {
        $em=$this->getDoctrine()->getManager();
        $p=$em->getRepository(Fournisseur::class)->find($request->query->get("idF"));
        $p->setNomF($request->query->get("nomF"));
        $p->setEmailF($request->query->get("emailF"));
        $p->setTelephoneF($request->query->get("telephoneF"));
        $p->setLvl($request->query->get("lvl"));
        $em->flush();

        $jsonContent=$Normalizer->normalize($p,'json',['groups'=>'post:read']);
        return new Response ("publication modifiée".json_encode($jsonContent));
    }
    /**
     * @Route("/fournisseur/delete")
     */
    public function deletejson(Request $request,NormalizerInterface $Normalizer): Response
    {
        $em=$this->getDoctrine()->getManager();
        $p=$em->getRepository(Fournisseur::class)->find($request->query->get("idF"));
        $em->remove($p);
        $em->flush();
        $jsonContent=$Normalizer->normalize($p,'json',['groups'=>'post:read']);
        return new Response ("publication modifiée".json_encode($jsonContent));
    }
}
