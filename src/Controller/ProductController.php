<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Aws\Credentials\Credentials;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
    ): Response {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
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
                        'Key'    => '$fileName', //Compte => Informations d'identification de sécurité => Clés d'accès
                        'Body'   => $image,
                        // 'ACL'    => 'public-read',
                        'SourceFile' => $image->getRealPath(),
                        'ContenFile' => $image->getMimeType(),
                        'ContentSHA256' => $sha256
                    ]);

                    dd($this->$s3->getObjectCurl('symfonyproject', $fileName));
                } catch (S3Exception $error) {
                    dd($error->getMessage());
                }
                $product->setImage($fileName);
                dd($fileName);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
