<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Employer;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EmployerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Repository\EmployerRepository;
use App\Data\SearchData;
use App\Form\SearchForm;
use Knp\Component\Pager\PaginatorInterface;

class EmployerController extends AbstractController
{
    /**
     * @Route("/employer", name="app_employer")
     */
    public function index(PaginatorInterface $paginator,EmployerRepository $repository,Request $request): Response
    {
        $event = $repository->findAll();
        $employes= $repository->findAll();
     //Paginate the results of the query
    $employes = $paginator->paginate(
     //Doctrine Query, not results
        $event,
        // Define the page parameter
        $request->query->getInt('page', 1),
        // Items per page
        4
    );
    
   
       // $employes = $this->getDoctrine()->getRepository(Employer::class)->findAll();
       $ud=$request->request->get('ud');
       $uv=$request->request->get('searchEmployer');
        return $this->render('employer/index.html.twig', [
            'employes' => $employes,
            'ud' => $ud,
                'searchEmployer' => $uv,
        ]);
       
        //
       // return $this->render('reclam/index.html.twig', [
          //      'employes' => $employes,
                
    }

    /**
     * @Route("/deleteEmployer/{id}", name="deleteEmployer")
     */
    public function deleteEmployer($id)
    {
        $employer = $this->getDoctrine()->getRepository(Employer::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($employer);
        $em->flush();
        return $this->redirectToRoute("app_employer");
    }

     /**
     * @Route("/addEmployer", name="addEmployer")
     */
    public function addEmployer(Request $request)
    {
        $employer = new Employer();
        $form = $this->createForm(EmployerType::class, $employer);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $image = $form->get('picture')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
            $image->move($this->getParameter('brochures_directory'), $imageName);
            $employer->setPicture($imageName);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($employer);
            $em->flush();
            return $this->redirectToRoute('app_employer');
        }
        return $this->render("employer/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateEmployer/{id}", name="updateEmployer")
     */
    public function updateEmployer(Request $request,$id)
    {
        $employer = $this->getDoctrine()->getRepository(Employer::class)->find($id);
        $form = $this->createForm(EmployerType::class, $employer);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $image = $form->get('picture')->getData();
            $imageName = md5(uniqid()).'.'.$image->guessExtension(); 
            $image->move($this->getParameter('brochures_directory'), $imageName);
            $employer->setPicture($imageName);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_employer');
        }
        return $this->render("employer/update.html.twig",array('form'=>$form->createView()));
    }
    /**
 * @param EmployerRepository $Repository
 * @return Response
 * @Route ("/listEmployer", name="Employer", methods={"GET"})
 */
public function Employer(EmployerRepository $Repository): Response
{
    $pdfOptions = new Options();
    $employes = $Repository->findAll();


    $pdfOptions->set('defaultFont', 'Arial');
    $pdfOptions->set('isRemoteEnabled',true);
    // Instantiate Dompdf with our options
    $dompdf = new Dompdf($pdfOptions);
    
    $logo = file_get_contents('lafourchette.png');
    $logo64 = base64_encode($logo);
    
    $listimge=[];
    $ii=0;
    
    foreach($employes as $e){
        $file = $this->getParameter('brochures_directory') . '/' . $e->getPicture();
        $png = file_get_contents($file);
        $pngbase64 = base64_encode($png);
        $listimge[$ii]=$pngbase64;
        $ii+=1;
    } 
    // Retrieve the HTML generated in our twig file
    $html = $this->renderView('employer/listEmployer.html.twig', [
        'employes' => $employes,"img64"=>$pngbase64,"listimge"=>$listimge,"logo"=>$logo64,
    ]);
    // Load HTML to Dompdf
    $dompdf->loadHtml($html);
    // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
    $dompdf->setPaper('A4', 'portrait');
    // Render the HTML as PDF
    $dompdf->render();
    // Output the generated PDF to Browser (force download)
    $dompdf->stream("Employer.pdf", [
        "Attachment" => true
    ]);

}
/**
     * @Route("/searchEmployer", name="searchEmployer")
     */
    public function searchEmployer(PaginatorInterface $paginator,Request $request, EmployerRepository $EmployerRepository): Response
    {
        $ud=$request->request->get('ud');
        $uv=$request->request->get('searchEmployer');
        $employes= $EmployerRepository->searchEmployer($ud,$uv);
        $employes = $paginator->paginate(
            // Doctrine Query, not results
                $employes,
                // Define the page parameter
                $request->query->getInt('page', 1),
                // Items per page
                4
            );
        return $this->render('employer/index.html.twig', [
                'employes' => $employes,
                'ud' => $ud,
                'searchEmployer' => $uv,
     
            ]);
    }
    /**
     * @Route("/triEmployer/{type}", name="triEmployer" )
     */
    public function triEmployer(PaginatorInterface $paginator,Request $request,EmployerRepository $EmployerRepository,$type): Response
    {
        $ud=$request->request->get('ud');
        $uv=$request->request->get('searchEmployer');
        $employes = $EmployerRepository->triEmployer($type);
        $employes = $paginator->paginate(
            // Doctrine Query, not results
                $employes,
                // Define the page parameter
                $request->query->getInt('page', 1),
                // Items per page
                4
            );
        return $this->render('employer/index.html.twig', [
            'employes' => $employes,
            'ud' => $ud,
            'searchEmployer' => $uv,
        ]);

    }
}
