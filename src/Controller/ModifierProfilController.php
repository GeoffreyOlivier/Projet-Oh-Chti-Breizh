<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ModifierProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ModifierProfilController extends AbstractController
{
    /**
     * @Route("/profil/modifierProfil", name="modifierProfil")
     * Cette fonction a pour but de modifier les données de l'utilisateur. Il ne pourra accéder à ce controller que si
     * il est connecté, sinon il sera renvoyé vers la page login
     */
    public function modifierProfil(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder)
    {
        //récupération de l'utilisateur en cours
        $user = $this->getUser();

        $modifierProfilForm = $this->createForm(ModifierProfilType::class, $user);
        $modifierProfilForm->handleRequest($request);

        if ($modifierProfilForm->isSubmitted() && $modifierProfilForm->isValid()) {

            //récupération du mot de mot inscris par l'utilisateur, on l'encode et on vérifie qu'il correspond avec
            // celui en base de données
            $passwordUnencode = $modifierProfilForm->get('password')->getData();
            $hash = $encoder->isPasswordValid($user, $passwordUnencode);


            if ($hash !== true) {
                $this->addFlash('alert', 'Le Mot de Passe ne correspond pas avec celui que nous avons en BDD !');
                return $this->render('profil/modifierProfil.html.twig', [
                    'controller_name' => 'ModifierProfilController', "modifierProfilForm" => $modifierProfilForm->createView()]);
            } else {

                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'La modification a bien été enregistrée !');
                return $this->redirectToRoute('profil');
            }
        }


        return $this->render('profil/modifierProfil.html.twig', [
            'controller_name' => 'ModifierProfilController', "modifierProfilForm" => $modifierProfilForm->createView()

        ]);
    }
}
