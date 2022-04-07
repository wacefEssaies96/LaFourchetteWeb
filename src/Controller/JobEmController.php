<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Jobem;
use Symfony\Component\HttpFoundation\Request;
use App\Form\JobEmType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class JobEmController extends AbstractController
{
    /**
     * @Route("/job/em", name="app_job_em")
     */
    public function index(): Response
    {
        $jobems = $this->getDoctrine()->getRepository(Jobem::class)->findAll();
        return $this->render('job_em/index.html.twig', [
            'jobems' => $jobems,
        ]);
    }

     /**
     * @Route("/deleteJobem/{id}", name="deleteJobem")
     */
    public function deleteJobem($id)
    {
        $jobem = $this->getDoctrine()->getRepository(Jobem::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($jobem);
        $em->flush();
        return $this->redirectToRoute("app_job_em");
    }

     /**
     * @Route("/addJobem", name="addJobem")
     */
    public function addJobem(Request $request)
    {
        $jobem = new Jobem();
        $form = $this->createForm(JobemType::class, $jobem);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($jobem);
            $em->flush();
            return $this->redirectToRoute('app_job_em');
        }
        return $this->render("job_em/add.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/updateJobem/{id}", name="updateJobem")
     */
    public function updateJobem(Request $request,$id)
    {
        $jobem = $this->getDoctrine()->getRepository(Jobem::class)->find($id);
        $form = $this->createForm(JobemType::class, $jobem);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_job_em');
        }
        return $this->render("job_em/update.html.twig",array('form'=>$form->createView()));
    }
    
}
