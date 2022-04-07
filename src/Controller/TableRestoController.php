<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\TableResto;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TableRestoType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
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
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_table_resto');
        }
        return $this->render("table_resto/update.html.twig",array('form'=>$form->createView()));
    }
}
