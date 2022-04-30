<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\TableResto;
use App\Entity\Datetimetr;
use App\Entity\DatetimetrTableResto;
use Symfony\Component\HttpFoundation\Request;
use App\Form\DatetimetrType;
use App\Form\TableRestoType;
use Knp\Component\Pager\PaginatorInterface;


class TableRestoController extends AbstractController
{
    /**
     * @Route("/table/resto", name="app_table_resto")
     */
    public function index(PaginatorInterface $paginator,Request $request): Response
    {
        $TRT=$request->request->get('TRT');
        $VRT=$request->request->get('searchtableresto');
        $tableRestos = $this->getDoctrine()->getRepository(TableResto::class)->findAll();
        $tableRestos = $paginator->paginate(
            $tableRestos,
            $request->query->getInt('page', 1),
            5
        );
        
        return $this->render('table_resto/index.html.twig', [
            'tableRestos' => $tableRestos,
            'TRT' => $TRT,
            'searchtableresto' => $VRT,
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
        $this->addFlash('info','Table Resto supprimé');
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
            $this->addFlash('info','Table Resto ajouté');
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
            $this->addFlash('info','Table Resto modifié');
            return $this->redirectToRoute('app_table_resto');
        }
        return $this->render("table_resto/update.html.twig",array('form'=>$form->createView()));
    }
     
    
    
    /**
     * @Route ("/searchtableresto", name="searchtableresto")
     */
    function searchtableresto(PaginatorInterface $paginator,Request $request): Response
    {
        $TRT=$request->request->get('TRT');
        $VRT=$request->request->get('searchtableresto');

        $tableRestos = $this->getDoctrine()->getRepository(TableResto::class)->Search($TRT,$VRT);

        if($tableRestos == []){
            if($TRT == 'prix'){
                $this->addFlash('info','Il n\'y aucune TableResto de prix = " '.$VRT.' "');
            }else if($TRT == 'etat'){
                $this->addFlash('info','Il n\'y aucune TableResto d\'etat = " '.$VRT.' "');
            }else{
                $this->addFlash('info','Il n\'y aucune TableResto dont le nombre de place = " '.$VRT.' "');
            }
            $tableRestos = $this->getDoctrine()->getRepository(TableResto::class)->findAll();
           
        }
        $tableRestos = $paginator->paginate(
            $tableRestos,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('table_resto/index.html.twig', [
            'tableRestos' => $tableRestos,
            'TRT' => $TRT,
            'searchtableresto' => $VRT,
        ]);
    }

    /**
     * @Route ("/tritableresto/{type}", name="tritableresto")
     */
    function tritableresto(PaginatorInterface $paginator,Request $request,$type)
    {
        
        $TRT=$request->request->get('TRT');
        $VRT=$request->request->get('searchtableresto');
        $tableRestos = $this->getDoctrine()->getRepository(TableResto::class)->tritableresto($type);
        $tableRestos = $paginator->paginate(
            $tableRestos,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('table_resto/index.html.twig', [
            'tableRestos' => $tableRestos,
            'TRT' => $TRT,
            'searchtableresto' => $VRT,
        ]);
    }
    
    /**
     * @Route("/gererdisponibiliteTableResto/{id}", name="gererdisponibiliteTableResto")
     */
    public function gererdisponibiliteTableResto(Request $request,$id)
    {
        $tableResto = $this->getDoctrine()->getRepository(TableResto::class)->find($id);
        $TRDTR=$request->request->get('TRDTR');
        $VRDTR=$request->request->get('searchdateunetable');
        
        $datetimetrs = $this->getDoctrine()->getRepository(DatetimetrTableResto::class)->findTRDate($id);

        /*dd($datetimetrs);*/
        

        return $this->render('table_resto/disponibilite.html.twig', [
            'tableResto' => $tableResto,
            'datetimetrs' => $datetimetrs,
            'TRDTR' => $TRDTR,
            'searchdateunetable' => $VRDTR,
        ]);
    }
    
    
     /** 
     * @Route("/deleteDateunetable/{id}/{idt}", name="deleteDateunetable")
     */
    public function deleteDateunetable($id,$idt)
    {
        $datetimetr = $this->getDoctrine()->getRepository(Datetimetr::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($datetimetr);
        $em->flush();
        $this->addFlash('info','La date a ete supprimé ');
        return $this->redirectToRoute('gererdisponibiliteTableResto',array('id'=>$idt));
    }

     /** 
     * @Route("/addDateunetable/{id}", name="addDateunetable")
     */
    public function addDateunetable(Request $request,$id): Response
    {

        $TRDTR=$request->request->get('TRDTR');
        $VRDTR=$request->request->get('searchdateunetable');
        $tableResto = $this->getDoctrine()->getRepository(TableResto::class)->find($id);

        $datetimetr = new Datetimetr();
        $form = $this->createForm(DatetimetrType::class, $datetimetr);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $maxiddt = $this->getDoctrine()->getRepository(Datetimetr::class)->MaxId();
            $maxiddt=$maxiddt[0][1]+1;
            $datetimetr->setIddt($maxiddt);
            
            $dtr = new DatetimetrTableResto();
            $dtr->setIddt($datetimetr);
            $dtr->setIdT($tableResto);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($datetimetr);
            $em->flush();
            $emdtr= $this->getDoctrine()->getManager();
            $emdtr->persist($dtr);
            $emdtr->flush();

            $this->addFlash('info','Date de reservation ajouté ');
            
            return $this->redirectToRoute('gererdisponibiliteTableResto',array('id'=>$id));
        }
        return $this->render("datetimetr/add.html.twig",array('form'=>$form->createView()));
    }

    /** 
     * @Route("/updateDateunetable/{id}/{idt}", name="updateDateunetable")
     */
    public function updateDateunetable(Request $request,$id,$idt)
    {
        $datetimetr = $this->getDoctrine()->getRepository(Datetimetr::class)->find($id);
        
        $form = $this->createForm(DatetimetrType::class, $datetimetr);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('info','Date de reservation modifieé');
            return $this->redirectToRoute('gererdisponibiliteTableResto',array('id'=>$idt));
        }
        return $this->render("datetimetr/update.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route ("/searchdate", name="searchdate")
     */
    function searchdate(PaginatorInterface $paginator,Request $request): Response
    {
        $TRDTR=$request->request->get('TRDTR');
        $VRDTR=$request->request->get('searchdate');

        $datetimetrs = $this->getDoctrine()->getRepository(Datetimetr::class)->Search($TRDTR,$VRDTR);
        if($datetimetrs == []){
            if($TRDTR == 'date'){
                $this->addFlash('info','Il n\'y aucune Date de reservation = " '.$VRDTR.' "');
            }else {
                $this->addFlash('info','Il n\'y aucune Date de reservation dont l\'etat = " '.$VRDTR.' "');
            }
            $datetimetrs = $this->getDoctrine()->getRepository(Datetimetr::class)->findAll();
           
        }
        $datetimetrs = $paginator->paginate(
            $datetimetrs,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('datetimetr/index.html.twig', [
            'datetimetrs' => $datetimetrs,
            'TRDTR' => $TRDTR,
            'searchdate' => $VRDTR,
        ]);
    }
 

    /**
     * @Route ("/searchdateunetable", name="searchdateunetable")
     */
    function searchdateunetable(Request $request): Response
    {
        $id=$request->request->get('idtr');
        $tableResto = $this->getDoctrine()->getRepository(TableResto::class)->find($id);
        
        $TRDTR=$request->request->get('TRDTR');
        $VRDTR=$request->request->get('searchdateunetable');

        $datetimetrs = $this->getDoctrine()->getRepository(DatetimetrTableResto::class)->SearchTR($TRDTR,$VRDTR,$id);
        if($datetimetrs == []){
            if($TRDTR == 'date'){
                $this->addFlash('info','Il n\'y aucune Date de reservation = " '.$VRDTR.' "');
            }else {
                $this->addFlash('info','Il n\'y aucune Date de reservation dont l\'etat = " '.$VRDTR.' "');
            }
            $datetimetrs = $this->getDoctrine()->getRepository(Datetimetr::class)->findAll();
           
        }
        
        return $this->render('table_resto/disponibilite.html.twig', [
            'tableResto' => $tableResto,
            'datetimetrs' => $datetimetrs,
            'TRDTR' => $TRDTR,
            'searchdateunetable' => $VRDTR,
        ]);
    }
    
    /**
     * @Route ("/tridatetr/{type}/{id}", name="tridatetr")
     */
    function tridatetr(Request $request,$type,$id)
    {
        
        $tableResto = $this->getDoctrine()->getRepository(TableResto::class)->find($id);

        $TRDTR=$request->request->get('TRDTR');
        $VRDTR=$request->request->get('searchdateunetable');

        $datetimetrs = $this->getDoctrine()->getRepository(DatetimetrTableResto::class)->tridateTR($type,$id);
        
        
        return $this->render('table_resto/disponibilite.html.twig', [
            'tableResto' => $tableResto,
            'datetimetrs' => $datetimetrs,
            'TRDTR' => $TRDTR,
            'searchdateunetable' => $VRDTR,
        ]);
    }

}
