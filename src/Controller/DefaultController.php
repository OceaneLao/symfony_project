<?php

namespace App\Controller;

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

    public function home()
    {
        return $this->render("home.html.twig");
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
}
