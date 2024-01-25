<?php

namespace App\Controller;

use App\Repository\CustomerReviewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    #[Route(
        "/article/{id}/",
        name: "detail_article",
        methods: ["get"],
        requirements: ["id" => "\d+"]
    )]

    public function hello($id)
    {
        return $this->render("example.html.twig", [
            "articles" => [
                "titre1" => "Titre1",
                "titre2" => "Titre2",
                "titre3" => "Titre3"
            ],
            "visible" => true
        ]);
    }

    #[Route(
        "/home",
        name: "home",
        methods: ["get"],
    )]

    //Importer avis client dans la Homepage
    //Entrer paramètre dans la fonction home
    //Récupérer le Repository et l'appeler avec $
    public function home(CustomerReviewsRepository $reviews)
    {
        //Ajouter un array pour afficher les avis clients
        return $this->render("home.html.twig", [
            "reviews" => $reviews->findAll()
        ]);
    }

    #[Route(
        "/concept",
        name: "concept",
        methods: ["get"],
    )]

    public function concept()
    {
        return $this->render("concept.html.twig");
    }

    #[Route(
        "/about",
        name: "about",
        methods: ["get"],
    )]

    public function about()
    {
        return $this->render("about.html.twig");
    }

    #[Route(
        "/contact",
        name: "contact",
        methods: ["get"],
    )]

    public function contact()
    {
        return $this->render("contact.html.twig");
    }
}
