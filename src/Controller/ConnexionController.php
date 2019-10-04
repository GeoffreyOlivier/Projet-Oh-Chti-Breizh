<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ConnexionController extends AbstractController
{
    /**
     * on nomme la route login car dans le fichier
     * security.yaml on a login_path: login
     * @Route("/login", name="login")
     */
    public function login()
    {

        return $this->render('login/login.html.twig');
    }


    /**
     * @Route("/register", name="register")
     * Cette fonction a pour but d'enregistrer un nouvel utilisateur en base de donnée
     * et d'encoder son mot de passe automatiquement
     */
    public function register(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder)
    {
        // on instancie un nouvel utilisateur et on lui donne le role (Role User). Il pourra accéder aux pages défini dans
        // security.yaml
        $user = new Utilisateur();
        $user->addRole("ROLE_USER");
        $inscrireForm = $this->createForm(InscriptionType::class, $user);

        //Récupération automatique des données du formulaire dans l'entité Utilisateur
        $inscrireForm->handleRequest($request);
        // on instancie le recaptcha en lui fournissant la clé secrète. La clé publique sera dans le twig
        $recaptcha = new ReCaptcha('6LetZbEUAAAAAIC0A8IfxY3WDCvuWySaeJHm_6aT');
        //Verification si la case est coché du recaptcha
        $response = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());
        //On vérifie que le formulaire est soumis, en plus qu'il est bien valide et que le recaptcha est ok.
        if ($inscrireForm->isSubmitted() && $inscrireForm->isValid() && $response->isSuccess()) {
            // on récupère le mot de passe inscris par l'utilisateur et on l'encode
            $passwordUnencode = $inscrireForm->get('password')->getData();
            $hash = $encoder->encodePassword($user, $passwordUnencode);
            // on fournit à l'objet utilisateur le mot de passe encodé
            $user->setPassword($hash);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Bienvenue parmis nous !');
            return $this->redirectToRoute("login");
        }
        return $this->render('login/register.html.twig',
            ["inscrireForm" => $inscrireForm->createView()]);

    }


    /**
     * Symfony gére entierement cette route il suffit de l'appeler logout.
     * Penser à parametre le fichier security.yaml pour rediriger la déconnexion.
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->render('login/login.html.twig');
    }

    /**
     * @Route("/motDePasseOublie", name="motDePasseOublie")
     * cette fonction va generer un token et permettre l'envoi d'un lien avec token par mail.
     */
    public function forgottenPassword(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer,
                                      TokenGeneratorInterface $tokenGenerator
    )
    {

        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(["email" => $email]);
            /* @var $user Utilisateur */

            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('login');
            }
            $token = $tokenGenerator->generateToken();

            try {
                $user->setToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('main');
            }

            $url = $this->generateUrl('resetPassword', array("token" => $token),
                UrlGeneratorInterface::ABSOLUTE_URL);
            $userEmail = $user->getEmail();
            $message = (new \Swift_Message('Mot de passe Oublie'))
                ->setFrom("OhCh'tiBreizh@gmail.com")
                ->setTo($userEmail)
                ->setBody(
                    "Bonjour, Vous avez oublié votre mot de passe ? voici le token pour reseter votre mot de passe : " . $url,
                    'text/html'
                );
            $mailer->send($message);;


            $this->addFlash('notice', 'Mail envoyé');

            return $this->redirectToRoute('login');
        }

        return $this->render('login/motDePasseOublie.html.twig');
    }

    /**
     * @Route("/resetPassword/{token}", name="resetPassword")
     * Cette fonction va permettre à l'utilisateur de changer son password après avoir cliquer sur le lien qu'il aura
     * reçu par mail
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {

        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(["token" => $token]);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('main');
            }

            $user->setToken(null);

            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour');

            return $this->redirectToRoute('main');
        } else {

            return $this->render('login/resetPassword.html.twig', ['token' => $token]);
        }

    }

}
