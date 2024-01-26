<?php

namespace App\Controller;

use App\Form\CartType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(Request $request): Response
    {
        $cart = $request->getSession()->get('cart',[]);
        $form = $this->createForm(CartType::class,);
        return $this->render('cart/index.html.twig', [
            'carts' => $request->getSession()->get('cart', []),
        ]);
    }
}
