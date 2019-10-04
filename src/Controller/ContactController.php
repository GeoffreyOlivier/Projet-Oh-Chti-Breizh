<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Utilisateur;
use App\Form\ContactType;
use App\Form\InscriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use ReCaptcha\ReCaptcha; // Include the recaptcha lib

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     * Cette fonction a pour but de récupérer les éléments inscris par l'utilisateur et d'envoyer un mail à Oh Ch'ti Breizh
     * une fois que le formulaire est soumis, qu'il est valide et que le recaptcha est coché.
     * Un recaptcha est présent et suit la même logique que dans le controller connexion/register
     *
     *
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $recaptcha = new ReCaptcha('6LetZbEUAAAAAIC0A8IfxY3WDCvuWySaeJHm_6aT');
        $contact = new Contact();
        $contactForm = $this->createForm(ContactType::class, $contact);
        $contactForm -> handleRequest($request);
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

        // On vérifie que le formulaire est soumis, qu'il est bien valide et que le recaptcha est coché
        if ($contactForm->isSubmitted() && $contactForm->isValid() && $resp->isSuccess())
        {
        // on récupère les éléments un à un pour pouvoir travailler sur le contenu du message
            $evenement = $request->request->get("evenement");
            $nom = $contactForm->get('nom')->getData();
            $prenom = $contactForm->get('prenom')->getData();
            $message = $contactForm->get('message')->getData();
            $mail = $contactForm->get('email')->getData();
            $telephone = $contactForm->get('telephone')->getData();
        // on prépare le message et sa mise en forme
            $messageEnvoi = "Nom : "."<br>".
                "Prenom: ".$prenom."<br>".
                "Email: ".$mail."<br>".
                "Telephone: ".$telephone."<br>".
                "Evenement: ".$evenement."<br><br>".
                $message;
        // grâce à swiftmailer on prepare le mail qui sera envoyé: envoyeur, destinateur etc...
            $envoi = (new \Swift_Message('Message'))
                ->setFrom($mail)
                ->setTo("remy_savin@hotmail.fr")
                ->setBody(
                    $messageEnvoi,
                    'text/html');
        // on envoi le mail avec message pour informer l'utilisateur
            $mailer->send($envoi);
            $this->addFlash('notice', 'Message envoyé');

            return $this->redirectToRoute('main');
        }

        return $this->render('Contact/contact.html.twig', [
            'controller_name' => 'ContactController'
            ,  "ContactForm" => $contactForm->createView()
        ]);
    }

}


