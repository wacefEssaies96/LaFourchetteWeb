<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\TableResto;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TableRestoType;

class TableRestoController extends AbstractController
{
    /**
     * @Route("/table/resto", name="app_table_resto")
     */
    public function index(): Response
    {
        $tableRestos = $this->getDoctrine()->getRepository(TableResto::class)->findAll();
        return $this->render('table_resto/index.html.twig', [
            'tableRestos' => $tableRestos,
        ]);
    }
     /**
     * @Route("/deleteTableResto/{id}", name="deleteTableResto")
     */
    public function deleteTableResto($id)
    {
        $tableResto = $this->getDoctrine()->getRepository(TableResto::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($tableResto);
        $em->flush();
        return $this->redirectToRoute("app_table_resto");
    }

     /**
     * @Route("/addTableResto", name="addTableResto")
     */
    public function addTableResto(Request $request)
    {
        $tableResto = new TableResto();
        $form = $this->createForm(TableRestoType::class, $tableResto);
        $form->handleRequest($request);

        $tableResto->setImagetable("imagenotfound.png");
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('imagetable')->getData()!= null){
                $image = $form->get('imagetable')->getData();
                $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
                $image->move($this->getParameter('brochures_directory'), $imageName);
                $tableResto->setImagetable($imageName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($tableResto);
            $em->flush();
            return $this->redirectToRoute('app_table_resto');
            
        }
        return $this->render("table_resto/add.html.twig",array('form'=>$form->createView()));
        
    }

    /**
     * @Route("/updateTableResto/{id}", name="updateTableResto")
     */
    public function updateTableResto(Request $request,$id)
    {
        $tableResto = $this->getDoctrine()->getRepository(TableResto::class)->find($id);
        
        $form = $this->createForm(TableRestoType::class, $tableResto);
        
        $form->handleRequest($request);
        
        
        
        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('imagetable')->getData()){
                $image = $form->get('imagetable')->getData();
                $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
                $image->move($this->getParameter('brochures_directory'), $imageName);
                $tableResto->setImagetable($imageName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_table_resto');
        }
        return $this->render("table_resto/update.html.twig",array('form'=>$form->createView()));
    }
     
    
    
    /**
     * @Route ("/searchtableresto", name="searchtableresto")
     */
    function searchtableresto(Request $request): Response
    {
        $nbrp=$request->request->get('searchtableresto');

        $tableResto = $this->getDoctrine()->getRepository(TableResto::class)->Searchnbrplace($nbrp);

        return $this->render('table_resto/index.html.twig', [
            'tableRestos' => $tableResto,
        ]);
    }

    /**
     * @Route ("/tritableresto/{type}", name="tritableresto")
     */
    function tritableresto(Request $request,$type)
    {
        
        $tableResto = $this->getDoctrine()->getRepository(TableResto::class)->tritableresto($type);
        /*dump($tableResto);die();*/
        return $this->render('table_resto/index.html.twig', [
            'tableRestos' => $tableResto,
        ]);
    }
}
