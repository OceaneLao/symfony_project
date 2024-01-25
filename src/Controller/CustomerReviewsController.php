<?php

namespace App\Controller;

use App\Entity\CustomerReviews;
use App\Form\CustomerReviewsType;
use App\Repository\CustomerReviewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer/reviews')]
class CustomerReviewsController extends AbstractController
{
    #[Route('/', name: 'app_customer_reviews_index', methods: ['GET'])]
    public function index(CustomerReviewsRepository $customerReviewsRepository): Response
    {
        return $this->render('customer_reviews/index.html.twig', [
            'customer_reviews' => $customerReviewsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_customer_reviews_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $customerReview = new CustomerReviews();
        $form = $this->createForm(CustomerReviewsType::class, $customerReview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customerReview);
            $entityManager->flush();

            return $this->redirectToRoute('app_customer_reviews_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer_reviews/new.html.twig', [
            'customer_review' => $customerReview,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_customer_reviews_show', methods: ['GET'])]
    public function show(CustomerReviews $customerReview): Response
    {
        return $this->render('customer_reviews/show.html.twig', [
            'customer_review' => $customerReview,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_customer_reviews_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CustomerReviews $customerReview, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CustomerReviewsType::class, $customerReview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_customer_reviews_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer_reviews/edit.html.twig', [
            'customer_review' => $customerReview,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_customer_reviews_delete', methods: ['POST'])]
    public function delete(Request $request, CustomerReviews $customerReview, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customerReview->getId(), $request->request->get('_token'))) {
            $entityManager->remove($customerReview);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_customer_reviews_index', [], Response::HTTP_SEE_OTHER);
    }
}
