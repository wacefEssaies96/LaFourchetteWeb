<?php

namespace App\Controller;

use App\Entity\Plat;

use App\Form\CommandeplatType;

use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commande;
use App\Form\CommandeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\CommandeRepository;
use Symfony\Component\Routing\Annotation\Route;




class CommandeController extends AbstractController
{
    /**
     * @Route("/commande", name="app_commande")
     */
    public function index(): Response
    {
        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,

        ]);
    }

    /**
     * @Route("/deleteCommande/{id}", name="deleteCommande")
     */
    public function deleteCommande($id)
    {
        $commande = $this->getDoctrine()->getRepository(Commande::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($commande); //indiquer object a supprimer
        $em->flush(); // envoyé tout ce qui est persisté a la base de donnne
        return $this->redirectToRoute("app_commande");
    }

     /**
     * @Route("/addCommande", name="addCommande")
     */
    public function addCommande(Request $request)
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush(); //envoyé tout ce qui est persisté a la base de donnne
            return $this->redirectToRoute('app_commande');
        }
        return $this->render("commande/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateCommande/{id}", name="updateCommande")
     */
    public function updateCommande(Request $request,$id)
    {
        $commande = $this->getDoctrine()->getRepository(Commande::class)->find($id);
        $form = $this->createForm(CommandeType::class, $commande);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_commande');
        }
        return $this->render("commande/update.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/addcommande{id}", name="addReservationPlat")
     */
    public function ajouterReservation(Request $request ,$id){


        $reservation = new Commande();
        $plat = $this->getDoctrine()->getRepository(Plat::class)->findOneBy(['reference'=>$id]);
         $currentuser=1;
        $reservation->setreferenceplat($plat);
        $q=$reservation->getquantity();

        $reservation->setPrixc($reservation->getquantity()*$plat->getprix());



        $form = $this->createForm(CommandeplatType::class,$reservation);


        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {


            $em =$this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            return $this->redirectToRoute('Mescommandes',array('id' => $currentuser));
        }
        return $this->render('Front/panier.html.twig', array('i' => $form->createView()));

    }
    /**
     * @Route("/Mescommandes/{id}", name="Mescommandes")
     */
    public function afficherReservationP(Request $request, PaginatorInterface $paginator,$id){
        $r=$this->getDoctrine()->getRepository(Commande::class);
        $reservation=$r->findfunction($id);

        $reservation= $paginator->paginate(
            $reservation,
            $request->query->getInt('page',1),
            2);

        return $this->render('Front/Mescommandes.html.twig', array('commande'=>$reservation));


    }
    /**
     * @Route("/pdf", name="PDF_Commande", methods={"GET"})
     */
    public function pdf(CommandeRepository $CommandeRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('commande/PDF_Commande.html.twig', [
            'commandes' => $CommandeRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('C6', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (inline view)
        $dompdf->set_base_path(realpath('Back/plugins/fontawesome-free/css/all.min.css'));
        $dompdf->stream("ListeDesCommandes.pdf", [
            "commandes" => true
        ]);
    }
}
