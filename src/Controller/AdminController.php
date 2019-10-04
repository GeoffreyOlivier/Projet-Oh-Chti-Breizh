<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ArticleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{


    /**
     * @Route("/ajout", name="ajout")
     */
    public function ajout(Request $request, EntityManagerInterface $em)
    {



        $product = new Product();
        $articleForm = $this->createForm(ArticleFormType::class, $product);

        $articleForm->handleRequest($request);
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {

            //            $file = $menu->getPhoto();
            $file = $articleForm->get('photo')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('image_directory'), $fileName);
            $product->setPhoto($fileName);
            $em->persist($product);
            $em->flush();
            $this->addFlash('notice', 'Post Submitted Successfully!!!');
            return $this->redirectToRoute('ajout');
        }

        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
            "articleForm" => $articleForm->createView(),


        ]);


    }

}
