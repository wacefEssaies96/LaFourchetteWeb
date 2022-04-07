<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Employer;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EmployerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmployerController extends AbstractController
{
    /**
     * @Route("/employer", name="app_employer")
     */
    public function index(): Response
    {
        $employes = $this->getDoctrine()->getRepository(Employer::class)->findAll();

        return $this->render('employer/index.html.twig', [
            'employes' => $employes,
        ]);
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
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_employer');
        }
        return $this->render("employer/update.html.twig",array('form'=>$form->createView()));
    }
}
