<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CgvController extends AbstractController
{
    /**
     * @Route("/cgv", name="cgv")
     */
    public function index()
    {
        return $this->render('cgv/cgv.html.twig', [
            'controller_name' => 'CgvController',
        ]);
    }
}
