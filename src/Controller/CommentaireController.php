<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Utilisateur;
use App\Repository\CommentaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Data\SearchData;
use App\Form\SearchForm;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;



class CommentaireController extends AbstractController
{
    /**
     * @Route("/commentaire", name="app_commentaire")
     */
    public function index(PaginatorInterface $paginator,Request $request): Response
    {   $te=$request->request->get('te');
        $tt=$request->request->get('searchComment');
        $commantaires = $this->getDoctrine()->getRepository(Commentaire::class)->findAll();
        //dd($commantaires);
        $commantaires = $paginator->paginate(
        // Doctrine Query, not results
            $commantaires,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );
        return $this->render('commentaire/index.html.twig', [
            'commantaires' => $commantaires,
            'te' => $te,
            'searchComment' => $tt,
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
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('app_commentaire');
        }
        return $this->render("commentaire/add.html.twig", array('form' => $form->createView()));
    }

    /**
     * @Route("/updateCommentaire/{id}", name="updateCommentaire")
     */
    public function updateCommentaire(Request $request, $id)
    {
        $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->add('modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_commentaire');
        }
        return $this->render("commentaire/update.html.twig", array('form' => $form->createView()));
    }

    /**
     * @param \App\Controller\CommentaireRepository $repositery
     * @return Response
     * @Route("/trilike/{id}",name="trilike")
     */

    function orderbylike(CommentaireRepository $repositery,$id)
    {
        $commentaires = $repositery->orderbyNbrLike($id);
        $evenements = $this->getDoctrine()->getRepository(Evenement::class)->find($id);

        $utilisateurs = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();
        return $this->render('evenement/detail.html.twig', [
            'e' => $evenements, 'comentaires' => $commentaires, 'utilisateurs' => $utilisateurs,
        ]);


    }

    /**
     * @param CommentaireRepository $repositery
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * @Route ("/tricommentaire", name="tricommentaire")
     */
    function ordercommentaire(CommentaireRepository $repositery,PaginatorInterface $paginator,Request $request)
    {
        $commantaires = $repositery->orderbycommentaire();
        $te=$request->request->get('te');
        $tt=$request->request->get('searchComment');
        $commantaires = $paginator->paginate(
        // Doctrine Query, not results
            $commantaires,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );
        return $this->render('commentaire/index.html.twig', [
            'commantaires' => $commantaires,
            'te' => $te,
            'searchComment' => $tt,

        ]);
    }

    /**
     * @param CommentaireRepository $repositery
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * @Route ("/likeback", name= "likeback")
     */
    function orderlikeback(CommentaireRepository $repositery,PaginatorInterface $paginator,Request $request)
    {
        $commantaires = $repositery->orderbylikeback();
        $te=$request->request->get('te');
        $tt=$request->request->get('searchComment');
        //$te=$request->request->get('te');
        //$tt=$request->request->get('searchEvent');
        $commantaires = $paginator->paginate(
        // Doctrine Query, not results
            $commantaires,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );
        return $this->render('commentaire/index.html.twig', [
            'commantaires' => $commantaires,
            'te' => $te,
            'searchComment' => $tt,

        ]);
    }

    /**
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param CommentaireRepository $EvenementRepository
     * @return Response
     * @Route ("/searchCommrnt",name="searchComment")
     */

    public function searchComment(PaginatorInterface $paginator,Request $request, CommentaireRepository $EvenementRepository): Response
    {
        $te=$request->request->get('te');
        $tt=$request->request->get('searchComment');
        $commantaires= $EvenementRepository->searchComment($te,$tt);
        $commantaires = $paginator->paginate(
        // Doctrine Query, not results
            $commantaires,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );

        return $this->render('commentaire/index.html.twig', [
            'commantaires' => $commantaires,
            'te' => $te,
            'searchComment' => $tt,

        ]);
    }

    // -----------------Mobile-------------//
/**
* @Route("/displayC/{ide}", name="displayC")
*/
    public function allCommanataire($ide)
    {
        $evenementM= $this->getDoctrine()->getManager()->getRepository(Commentaire::class)->findBy(['idevent'=>$ide]);
        $serializer= new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenementM);
        return new JsonResponse($formatted);


    }


    /**
     * @Route("/addComtr", name = "/addComtr")
     *
*/
   public  function addComtr(Request $request)
   {

       $textcommantaire = $request->query->get("commantaire");
       $idevent = $request->query->get("idevent");
       $idu = $request->query->get("idu");
       $idu = intval($idu);
       $idevent = intval($idevent);
      //dd($integer);
       //dd($idevent);
      // dd($commantaire);
       $evenementM= $this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($idevent);
       //dd($evenementM);
       $commantaire= new Commentaire();
       $commantaire->setIdevent($evenementM);

       $util= $this->getDoctrine()->getManager()->getRepository(Utilisateur::class)->find($idu);
       $commantaire->setIdu($util);
       $em = $this->getDoctrine()->getManager();
       $commantaire->setCommantaire($textcommantaire);
       $em->persist($commantaire);
       $em->flush();
       $serializer = new Serializer([new ObjectNormalizer()]);
       $formatted =  $serializer->normalize($commantaire);
       return new JsonResponse($formatted) ;
   }







}