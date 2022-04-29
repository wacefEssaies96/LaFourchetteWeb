<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Decoration;
use App\Form\DecorationType;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class DecorationController extends AbstractController
{
    /**
     * @Route("/decoration", name="app_decoration")
     */
    public function index(PaginatorInterface $paginator,Request $request): Response
    {
        $TRD=$request->request->get('TRD');
        $VRD=$request->request->get('searchdecoration');
        $decorations = $this->getDoctrine()->getRepository(Decoration::class)->findAll();
        $decorations = $paginator->paginate(
            $decorations,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('decoration/index.html.twig', [
            'decorations' => $decorations,
            'TRD' => $TRD,
            'searchdecoration' => $VRD,
        ]);
    }

            /**
     * @Route("/deleteDecoration/{id}", name="deleteDecoration")
     */
    public function deleteDecoration($id)
    {
        $decoration = $this->getDoctrine()->getRepository(Decoration::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($decoration);
        $em->flush();
        $this->addFlash('info','Decoration supprimer');
        return $this->redirectToRoute("app_decoration");
    }

     /**
     * @Route("/addDecoration", name="addDecoration")
     */
    public function addDecoration(Request $request)
    {
        $decoration = new Decoration();
        $form = $this->createForm(DecorationType::class, $decoration);
        $form->handleRequest($request);

        $decoration->setImaged("imagenotfound.png");
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('imaged')->getData()!= null){
                $image = $form->get('imaged')->getData();
                $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
                $image->move($this->getParameter('brochures_directory'), $imageName);
                $decoration->setImaged($imageName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($decoration);
            $em->flush();
            $this->addFlash('info','Decoration ajoutée');
            return $this->redirectToRoute('app_decoration');
        }
        return $this->render("decoration/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateDecoration/{id}", name="updateDecoration")
     */
    public function updateDecoration(Request $request,$id)
    {
        $decoration = $this->getDoctrine()->getRepository(Decoration::class)->find($id);
        $form = $this->createForm(DecorationType::class, $decoration);
        $form->handleRequest($request);
        
        $decoration->setImaged("imagenotfound.png");
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('imaged')->getData()){
                $image = $form->get('imaged')->getData();
                $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
                $image->move($this->getParameter('brochures_directory'), $imageName);
                $decoration->setImaged($imageName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('info','Decoration modifié');
            return $this->redirectToRoute('app_decoration');
        }
        return $this->render("decoration/update.html.twig",array('form'=>$form->createView()));
    }
    
    /**
     * @Route ("/searchdecoration", name="searchdecoration")
     */
    function searchdecoration(PaginatorInterface $paginator,Request $request): Response
    {
        
        $TRD=$request->request->get('TRD');
        $VRD=$request->request->get('searchdecoration');
        
        $decorations = $this->getDoctrine()->getRepository(Decoration::class)->Search($TRD,$VRD);

        
        if($decorations == []){
            if($TRD == 'prix'){
                $this->addFlash('info','Il n\'y aucune decoration de prix = " '.$VRD.' "');
            }else{
                $this->addFlash('info','Il n\'y aucune decoration de nom = " '.$VRD.' "');
            }
            $decorations = $this->getDoctrine()->getRepository(Decoration::class)->findAll();
           
        }
        $decorations = $paginator->paginate(
            $decorations,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('decoration/index.html.twig', [
            'decorations' => $decorations,
            'TRD' => $TRD,
            'searchdecoration' => $VRD,
        ]);
    }

    /**
     * @Route ("/tridecoration/{type}", name="tridecoration")
     */
    function tridecoration(PaginatorInterface $paginator,Request $request,$type)
    {
        $TRD=$request->request->get('TRD');
        $VRD=$request->request->get('searchdecoration');
        $decorations = $this->getDoctrine()->getRepository(Decoration::class)->tridecoration($type);
        /*dd($decoration);*/
        $decorations = $paginator->paginate(
            $decorations,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('decoration/index.html.twig', [
            'decorations' => $decorations,
            'TRD' => $TRD,
            'searchdecoration' => $VRD,
        ]);
    }
}
