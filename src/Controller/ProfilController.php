<?php

namespace App\Controller;

use App\Form\ProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     * Cette fonction a pour but de récupérer l'utilisateur en cours afin qu'il puisse visualiser ses informations sur la page
     */
    public function index()
    {
        $user = $this->getUser();
        $profilForm = $this->createForm(ProfilType::class, $user);
        return $this->render('profil/profil.html.twig', [
            'controller_name' => 'ProfilController', "profilForm" => $profilForm->createView()
        ]);
    }
}
