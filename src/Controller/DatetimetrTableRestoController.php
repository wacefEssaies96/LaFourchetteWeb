<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DatetimetrTableRestoController extends AbstractController
{
    /**
     * @Route("/datetimetr/table/resto", name="app_datetimetr_table_resto")
     */
    public function index(): Response
    {
        return $this->render('datetimetr_table_resto/index.html.twig', [
            'controller_name' => 'DatetimetrTableRestoController',
        ]);
    }
}
