<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Product;
use App\Entity\Sauce;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{


    /**
     * @var ProductRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ProductRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/produits", name="menu" )
     * @return Response
     * Ce controller permet d'afficher les articles dans la page "La carte"
     */
    public function index(): Response
    {

        $repo = $this->getDoctrine()->getRepository(Product::class);
        $menu = $repo->findAll();
        $sandwich = $repo->findBy(array('categorie' => 1));
        $frite = $repo->findBy(array('categorie' => 6));
        $boisson = $repo->findBy(array('categorie' => 3));
        $dessert = $repo->findBy(array('categorie' => 2));
        $wrap = $repo->findBy(array('categorie' => 4));

        return $this->render('menu/menu.html.twig', [
            'menu' => $menu,
            'sandwich' => $sandwich,
            'frite' => $frite,
            'boisson' => $boisson,
            'dessert' => $dessert,
            'wrap' => $wrap,
        ]);

    }

    /**
     * @Route("/produits/{slug}-{id}", name="menu.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Product $product
     * @return Response
     * cette partie est utiliser pour afficher l'article avant l'ajout au panier
     */
    public function show(Product $product, string $slug): Response
    {

        $repo = $this->getDoctrine()->getRepository(Sauce::class);
        $sauce = $repo->findAll();


        if ($product->getSlug() !== $slug) {
            $repo = $this->getDoctrine()->getRepository(Product::class);
            $product = $repo->findAll();
            return $this->redirectToRoute('menu.show', [
                'id' => $product->getId(),
                'slug' => $product->getSlug()
            ], 301);
        }

        return $this->render('menu/show.html.twig', [
            "sauce"=> $sauce,
            "menu" => $product,
            "product" => $product,
            "Current_menu" => "products"
        ]);

    }
//
//
//    /**
//     * @Route("/produits/{slug}-{id}", name="menu.showmenu", requirements={"slug": "[a-z0-9\-]*"})
//     * @param Menu $menu
//     * @return Response
//     */
//    public function menushow(Menu $menu, string $slug): Response
//    {
//
//
//        if ($menu->getSlug() !== $slug) {
//            $repo = $this->getDoctrine()->getRepository(Menu::class);
//            $menu = $repo->findAll();
//            return $this->redirectToRoute('menu.show', [
//                'id' => $menu->getId(),
//                'slug' => $menu->getSlug()
//            ], 301);
//        }
//
//        return $this->render('menu/show.html.twig', [
//            "menu" => $menu,
//            "Current_menu" => "menu"
//        ]);
//
//    }


    /**
     * @Route("/export", name="menu.export" )
     * @return Response
     */
    public function export()
    {
        $events = $this->repository->findAll();
        $rows = array();
        foreach ($events as $event) {
            $data = array($event->getId(), $event->getNom(), $event->getDescription(), $event->getPrix());
            $rows[] = implode(',', $data);
        }
        $content = implode("\n", $rows);
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="produits.csv"');
        return $response;
    }

}