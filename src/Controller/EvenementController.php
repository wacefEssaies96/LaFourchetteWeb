<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use App\Repository\EvenementRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenement;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EvenementType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CommentaireType;
use Dompdf\Dompdf;
use Dompdf\Options;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use App\Data\SearchData;
use App\Form\SearchForm;
use Knp\Component\Pager\PaginatorInterface;
use App\Controller\CommentaireRepository;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;



class EvenementController extends AbstractController
{
    /**
     * @Route("/evenement", name="app_evenement")
     */
    public function index(PaginatorInterface $paginator,EvenementRepository $repository,Request $request): Response
    {
        $te=$request->request->get('te');
        $tt=$request->request->get('searchReclam');
        $event = $repository->findAll();
        // Paginate the results of the query
        $evenements = $paginator->paginate(
        // Doctrine Query, not results
            $event,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );
        //$evenements = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
            'te' => $te,
            'searchEvent' => $tt,

        ]);

    }

    /**
     * @Route("/ajoutercommentaire/{ide}" ,name="ajoutercommentaire")
     */
    public function ajoutercommentaire(Request $request,$ide,EvenementRepository $repository,UtilisateurRepository $repo)
    {
        $commentaire = new Commentaire();
        $evenement=$repository->find($ide);
        $commentaire->setIdevent($evenement);
        $utilisateur =$repo->find(5);
        $commentaire->setIdu($utilisateur);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->add('Ajoutercomentaire', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            /* $eve=$form->get('idevent')->getData();
             $commentaire->setIdevent($eve);*/
            /* dump($commentaire);
         die();*/
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            $this->addFlash('info','commentaire ajouté');
            return $this->redirectToRoute('app_evenementFront');
        }
        return $this->render("commentaire/ajoutercommentaire.html.twig", array('form' => $form->createView()));

    }


    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function indexshow($id): Response
    {
        $evenements = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('idevent' => $id));
        $utilisateurs = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();
        return $this->render('evenement/detail.html.twig', [
            'e' => $evenements, 'comentaires' => $commentaires, 'utilisateurs' => $utilisateurs,
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
        $this->addFlash('info','Delete successfuly');
        return $this->redirectToRoute("app_evenement");
    }

    /**
     * @Route("/addEvenement", name="addEvenement")
     */
    public function addEvenement(Request $request)
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('imagee')->getData();
            $imageName = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move($this->getParameter('brochures_directory'), $imageName);
            $evenement->setImagee($imageName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();
             $this->addFlash('info','added successfuly');
            return $this->redirectToRoute('app_evenement');
        }
        return $this->render("evenement/add.html.twig", array('form' => $form->createView()));
    }

    /**
     * @Route("/updateEvenement/{id}", name="updateEvenement")
     */
    public function updateEvenement(Request $request, $id)
    {
        $evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->add('modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('imagee')->getData();
            $imageName = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move($this->getParameter('brochures_directory'), $imageName);
            $evenement->setImagee($imageName);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('info','Update successfuly');
            return $this->redirectToRoute('app_evenement');
        }
        return $this->render("evenement/update.html.twig", array('form' => $form->createView()));
    }

    /**
     * @param EvenementRepository $repositery
     * @return Response
     * @Route ("/listedql", name ="listeDQL")
     */
    function orderbydesignation(EvenementRepository $repositery,PaginatorInterface $paginator,Request $request)
    {

        $event = $repositery->orderbydesignation();
        $te=$request->request->get('te');
        $tt=$request->request->get('searchEvent');
        $evenements = $paginator->paginate(
        // Doctrine Query, not results
            $event,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
            'te' => $te,
            'searchEvent' => $tt,
        ]);

    }
    /**
     * @param EvenementRepository $repositery
     * @return Response
     * @Route ("/listedqlDate", name ="listeDQLDate")
     */
    function orderbyDaate(EvenementRepository $repositery,PaginatorInterface $paginator,Request $request)
    {
        $event = $repositery->orderbyDate();
        $te=$request->request->get('te');
        $tt=$request->request->get('searchEvent');
        $evenements = $paginator->paginate(
        // Doctrine Query, not results
            $event,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
            'te' => $te,
            'searchEvent' => $tt,
        ]);
    }
    /**
     * @param EvenementRepository $repositery
     * @return Response
     * @Route ("/listeDQLParticipants", name ="listeDQLParticipants")
     */
    function orderbyPartcipants(EvenementRepository $repositery,PaginatorInterface $paginator,Request $request)
    {
        $event = $repositery->orderbyNbrPartcipants();
        $te=$request->request->get('te');
        $tt=$request->request->get('searchEvent');
        $evenements = $paginator->paginate(
        // Doctrine Query, not results
            $event,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
            'te' => $te,
            'searchEvent' => $tt,
        ]);
    }
    /**
     * @Route ("/searchEvent", name ="searchEvent")
     */
    public function searchEvent(PaginatorInterface $paginator,Request $request, EvenementRepository $EvenementRepository): Response
    {
        $te=$request->request->get('te');
        $tt=$request->request->get('searchEvent');
        $evenements= $EvenementRepository->searchEvent($te,$tt);
        $evenements = $paginator->paginate(
        // Doctrine Query, not results
            $evenements,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
            'te' => $te,
            'searchEvent' => $tt,

        ]);
    }

    /**
     * @param EvenementRepository $repositery
     * @return Response
     * @Route ("/listevenement", name="evenementliste", methods={"GET"})
     */
    public function listevenementPDF(EvenementRepository $repositery): Response
    {
        $pdfOptions = new Options();
        $evenements = $repositery->findAll();
        $pdfOptions->set('defaultFont', 'Arial');
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('evenement/listevenement.html.twig', [
            'evenements' => $evenements,
        ]);
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (force download)
        $dompdf->stream("EvenementsList.pdf", [
            "Attachment" => true
        ]);

    }


/*
    /**
     * @param MailerInterface $mailer
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @Route ("/email", name= "email")



    public function sendEmail(MailerInterface $mailer): Response
    {   $evenements = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
        $email = (new Email())
            ->from('lafourchette.esprit@gmail.com')
            ->to('farahchahrazed.selmi@esprit.tn')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');
        $mailer->send($email);

        return $this->render('front.html.twig', [
            'evenements' => $evenements,
        ]);
    }*/
    /**
     * @Route("/{id}/email",name="email")
     */
    public function sendMail ( MailerInterface $mailer,Evenement $e ,Request $request, EntityManagerInterface $m){
        $evenements = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
        $email = (new Email())
            ->from('lafourchette.esprit@gmail.com')
            ->to('farahchahrazed.selmi@esprit.tn')
            ->subject('Participation evenement!')
            ->text('Bienvenu à l evenement fama barcha jaw !')
            ->html('<p>Bienvenu à l evenement fama barcha jaw</p>');
        $mailer->send($email);

        $snbr=$e->getNbrparticipants();

        if ($snbr>0 ){
            $e->setNbrparticipants( $snbr-1);
            $m -> persist($e);
            $m->flush();
            //$this->addFlash('info','Update successfuly');
        }
        return $this->redirectToRoute('app_evenementFront');
    }

    /**
     *
     * @Route ("/like/{idC}", name="like")
     */
   public  function  like(EntityManagerInterface $m,$idC):Response
   {
       $commantaire = $this->getDoctrine()->getRepository(Commentaire::class)->find($idC);

       $nbr=$commantaire->getNbrlike();
       $evenements = $this->getDoctrine()->getRepository(Evenement::class)->find($commantaire->getIdevent()->getIde());
      /* dump($evenements);
       die();*/
           $commantaire->setNbrlike( $nbr+1);
           $m -> persist($commantaire);
           $m->flush();
           //$this->addFlash('info','Update successfuly');
       /*return $this->render('evenement/detail.html.twig', [
           'e' => $evenements, 'comentaires' => $commantaire,
       ]);*/
       return  $this->redirectToRoute('detail',['id'=>$commantaire->getIdevent()->getIde()]);

   }



}
