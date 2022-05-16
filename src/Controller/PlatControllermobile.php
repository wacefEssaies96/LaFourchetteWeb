<?php

namespace App\Controller;

use App\Entity\Commande;

use App\Repository\PlatRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Plat;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PlatType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PlatControllermobile extends AbstractController
{
    /**
     * @Route("/plat", name="app_plat")
     */
    public function index(Request $request): Response
    {    $te=$request->request->get('te');
        $tt=$request->request->get('searchPlat');
        $plats = $this->getDoctrine()->getRepository(Plat::class)->findAll();
        return $this->render('plat/index.html.twig', [
            'plats' => $plats,
            'te' => $te,
            'searchPlat' => $tt,

        ]);
    }
    /**
     * @Route("/showplat", name="showplat")
     */
    public function showplat(): Response
    {
        $plats = $this->getDoctrine()->getRepository(Plat::class)->findAll();
        $r=$this->getDoctrine()->getRepository(Commande::class);
        $reservation=$r->findAll();
        return $this->render('Front/showplat.html.twig', [
            'plats' => $plats,
            'commande'=>$reservation,
        ]);
    }
     /**
     * @Route("/deletePlat/{id}", name="deletePlat")
     */
    public function deletePlat($id)
    {
        $plat = $this->getDoctrine()->getRepository(Plat::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($plat);
        $em->flush();
        $this->addFlash('info','Plat supprimé !');
        return $this->redirectToRoute("app_plat");
    }

     /**
     * @Route("/addPlat", name="addPlat")
     */
    public function addPlat(Request $request)
    {
        $plat = new Plat();
        $form = $this->createForm(PlatType::class, $plat);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('imagep')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('uploaddir'), $imageName);
            $plat->setImagep($imageName);
            $plat->setReference($form->get('nomprod')->getData());
            $em = $this->getDoctrine()->getManager();
            $em->persist($plat);
            $em->flush();
            $this->addFlash('info','                                            Plat ajouté !');

            return $this->redirectToRoute('app_plat');
        }
        return $this->render("plat/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updatePlat/{id}", name="updatePlat")
     */
    public function updatePlat(Request $request,$id)
    {
        $plat = $this->getDoctrine()->getRepository(Plat::class)->find($id);
        $form = $this->createForm(PlatType::class, $plat);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('imagep')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('brochures_directory'), $imageName);
            $plat->setImagep($imageName);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('info','Plat supprimé !');
            return $this->redirectToRoute('app_plat');
        }
        return $this->render("plat/update.html.twig",array('form'=>$form->createView()));
    }
    /**
     * @Route("/searchPlat", name="searchPlat")
     */
    public function searchReclam(Request $request, PlatRepository $PlatRepository): Response
    {
        $te=$request->request->get('te');
        $tt=$request->request->get('searchPlat');
        $plats= $PlatRepository->searchPlat($te,$tt);
        return $this->render('plat/index.html.twig', [
            'plats' => $plats,
            'te' => $te,
            'searchPlat' => $tt,

        ]);
    }
    /**
     * @Route("/triPLat/{type}", name="triPlat" )
     */
    public function triPlat(Request $request,PlatRepository $PlatRepository,$type): Response
    {
        $te=$request->request->get('te');
        $tt=$request->request->get('searchPlat');
        $plats = $PlatRepository->triPlat($type);

        return $this->render('plat/index.html.twig', [
            'plats' => $plats,
            'te' => $te,
            'searchPlat' => $tt,
        ]);

    }
    /**
     * @Route("/showplatjson", name="showplatjson")

     */
    public function tutoJson(): JsonResponse
    {
        $plat = $this->getDoctrine()->getManager()
            ->getRepository(Plat::class)->findAll();

        $serializer = new Serializer([new ObjectNormalizer()]);


        $formatted = $serializer->normalize($plat);

        return new JsonResponse($formatted);
    }
    /**
     * @Route("/json/{id}", name="TutoIdJson")
     * @throws ExceptionInterface
     */
    public function tutoIdJson($id): JsonResponse
    {
        $tutorial = $this->getDoctrine()->getManager()
            ->getRepository(Plat::class)->find($id);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tutorial);
        return new JsonResponse($formatted);
    }
    /**
     * @param Request $request
     * @Route("/showplatjson/upload",name="uploadJson",methods={"GET","POST"})
     * @return JsonResponse
     */
    public function uploadImage(Request $request)
    {

        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);

        if ((($_FILES["file"]["type"] == "image/*") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/png")) && ($_FILES["file"]["size"] < 5000000) && in_array($extension, $allowedExts)) {
            if ($_FILES["file"]["error"] > 0) {
                $named_array = array("Response" => array(array("Status" => "error")));
                return new JsonResponse($named_array);

            } else {
                move_uploaded_file($_FILES["file"]["tmp_name"], $this->getParameter('uploads_tutos').$_FILES["file"]["name"]);

                $Path = $_FILES["file"]["name"];
                $named_array = array("Response" => array(array("Status" => "ok")));
                return new JsonResponse($named_array);
            }
        } else {
            $named_array = array("Response" => array(array("Status" => "invalid")));
            return new JsonResponse($named_array);

        }
    }
    /**
     * @Route("/affichecommande/{id}", name="commandejson")
     *
     */
    public function afficherReservationP(Request $request,$id): JsonResponse
    {
        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findBy(array('idu'=>$id),array('idu'=>'ASC'));



        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($commandes);
        return new JsonResponse($formatted);


    }
    /**
     * @Route("/deletecommandes/{id}", name="deleteCommandeJson")
     */
    public function deleteCommandeJson($id)
    {
        $commandes = $this->getDoctrine()
            ->getRepository(Commande::class)->find($id);
        $this->getDoctrine()->getManager()->remove($commandes);
        $this->getDoctrine()->getManager()->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($commandes);
        return new JsonResponse($formatted);
    }
}
