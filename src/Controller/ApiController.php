<?php

namespace App\Controller;

use App\Entity\Product;
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

class ApiController extends AbstractController
{
    //Afficher un produit
    #[Route('/api', name: 'api_get_all', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->json($products);
    }

    //Créer un produit
    #[Route('/api', name: 'api_create', methods:['POST'])]
    public function createProduct(
        Request $request, 
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        ): Response
    {
        $product = new Product;

        if ($request->request->has('name')){
            $product->setName($request->request->get('name'));
        }

        if ($request->request->has('price')){
            $product->setPrice((int)$request->request->get('price'));
        }

        if ($request->request->has('quantity')){
            $product->setQuantity((int)$request->request->get('quantity'));
        }

        if ($request->request->has('description')){
            $product->setDescription($request->request->get('description'));
        }

        if($request->files->has('image')){
            $image = $request->files->get('image')->getData();
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
                $product->setImage($s3->getObjectUrl('symfonyproject', $fileName));
            }
        }

        $entityManager->persist($product);
        $entityManager->flush();

        dd($product);
        return $this->json($product, 201, ["Access-Control-Allow-Origin" => "*"]);
    }
}
