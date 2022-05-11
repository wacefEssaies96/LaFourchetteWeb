<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Entity\ProduitFournisseur;
use App\Entity\Fournisseur;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProduitType;
use Knp\Component\Pager\PaginatorInterface;
use Swift_Mailer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="app_produit")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        $pp = $paginator->paginate($produits, $request->query->getInt('page', 1), 3);
        return $this->render('produit/index.html.twig', [
            'produits' => $pp,
        ]);
    }
    /**
     * @Route("/deleteProduit/{id}", name="deleteProduit")
     */
    public function deleteProduit($id)
    {
        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute("app_produit");
    }

     /**
     * @Route("/addProduit", name="addProduit")
     */
    public function addProduit(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->add('Ajouter',SubmitType::class, [
            'attr' => ['class' => 'btn btn-success'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $image = $form->get('image')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
            $image->move($this->getParameter('brochures_directory'), $imageName);
            $produit->setImage($imageName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('app_produit');
        }
         return $this->render("produit/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateProduit/{id}", name="updateProduit")
     */
    public function updateProduit(Request $request,$id)
    {
        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->add('modifier',SubmitType::class, [
            'attr' => ['class' => 'btn btn-success'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
            $image->move($this->getParameter('brochures_directory'), $imageName);
            $produit->setImage($imageName);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_produit');
        }
        return $this->render("produit/update.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/search/produit", name="search_produit", requirements={"id":"\d+"})
     */
    public function searchProduit(Request $request, NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Produit::class);
        $requestString = $request->get('searchValue');
        $produit = $repository->findByNomProd($requestString);
        $jsonContent = $Normalizer->normalize($produit, 'json',[]);

        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/sendmail/{id}", name="sendmail")
     * @param Swift_Mailer $mailer
     */
    public function sendMail(Swift_Mailer $mailer, $id)
    {
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
            ->setTo('wacef.essaies@esprit.tn')
            ->setBody(
                $this->renderView(
                    'produit/mail.html.twig', ['produit' => $id]
                ),
                'text/html'
            )
        ;
        $mailer->send($message);
        $this->addFlash('message', 'Un lien d\'activation a été envoyer a votre email !');
        return $this->redirectToRoute('app_produit');
    }
}
