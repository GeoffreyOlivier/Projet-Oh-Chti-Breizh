<?php

namespace App\Controller;

use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    /**
     * @Route("/profil/macommande", name="commande")
     * Cette fonction a pour but de récupérer la commande de l'utilisateur en cours
     */
    public function index()
    {
        $user = $this->getUser();
        $userID = $user->getId();
        $repo = $this->getDoctrine()->getRepository(Commande::class);
        $commande = $repo->findBy(array('utilisateur' => $userID ));


        return $this->render('commande/commande.html.twig', [
            'controller_name' => 'CommandeController',
            'commande' => $commande
        ]);
    }
}
