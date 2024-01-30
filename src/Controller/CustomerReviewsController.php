<?php

namespace App\Controller;

use App\Entity\CustomerReviews;
use App\Form\CustomerReviewsType;
use App\Repository\CustomerReviewsRepository;
use Aws\Credentials\Credentials;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function new(Request $request, 
    EntityManagerInterface $entityManager,
    SluggerInterface $slugger,
    ): Response
    {
        // phpinfo();
        $customerReview = new CustomerReviews();
        $form = $this->createForm(CustomerReviewsType::class, $customerReview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('picture')->getData();
            if ($image) {
                $originalName = pathinfo(
                    $image->getClientOriginalName(),
                    PATHINFO_FILENAME
                );

                $nameSlugged = $slugger->slug($originalName);
                $fileName = $nameSlugged . '-' . uniqid() . '.' . $image->guessExtension();
                try {
                    $sha256 = hash_file('sha256', $image->getRealPath());
                    // Instantiate an Amazon S3 client.
                    $credentials = new Credentials('AKIA47CRY3PFDBV5NM4F','pnI1eMF1AELEqxvm5VjX4ifnu8D2c0mI3Hbpy82G');
                    $s3 = new S3Client([
                        'version' => 'latest',
                        'region'  => 'eu-north-1', //Services => Stockage => S3 => Région AWS
                        'credentials' => $credentials
                    ]);
                    // Upload a publicly accessible file. The file size and type are determined by the SDK.
                    $s3->putObject([
                        'Bucket' => 'symfonyproject',
                        'Key'    => $fileName, //Compte => Informations d'identification de sécurité => Clés d'accès
                        'Body'   => $image,
                        'ACL'    => 'public-read',
                        'SourceFile' => $image->getRealPath(),
                        'ContenFile' => $image->getMimeType(),
                        'ContentSHA256' => $sha256
                    ]);

                } catch (S3Exception $error) {
                    dd($error->getMessage());
                }
                $customerReview->setPicture($s3->getObjectUrl('symfonyproject', $fileName));
            }
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
