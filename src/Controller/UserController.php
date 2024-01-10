<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/* Supprimer 
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;*/
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    //Ajouter des méthodes pour récupérer les données dans les champs de saisie
    #[Route('/user', name: 'app_user', methods: ['GET', 'POST'])]

    // ADD (ajouter une entité)
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        //Déclarer $form (fichier UserType.php)
        $form = $this->createForm(UserType::class);

        //createFormBuilder() permet de créer le formulaire
        // $form = $this->createFormBuilder()
        //->add('nom',Type::class)
        // ->add('email', EmailType::class)
        // ->add('password', PasswordType::class)
        // ->add('submit', SubmitType::class)
        // ->getForm()
        // ; 

        // handleRequest() est une méthode permettant de gérer le traitement de la saisie du formulaire.
        $form->handleRequest($request);

        // Conditions 
        // Si le formulaire est envoyé (submit) et validé
        if ($form->isSubmitted() && $form->isValid()) {

            // Récupérer les datas
            $data = $form->getData();

            // Récupérer l'entité 
            $user = new User();

            // Mapper les données des utilisateurs
            $user->setEmail($data["email"]);
            $user->setPassword($data["password"]);

            // persist() permet de vérifier toutes les conditions pour l'insertion (exemple : id)
            $em->persist($user);

            // Donner l'autorisation d'insérer l'id 
            $em->flush();
            //dd($data, $user);
        }
        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // LECTURE / READ
    #[Route('/user/{id}', name: 'user_detail', requirements: ["id" => "\d+"])]
    public function read($id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find((int)$id);
        //dd($user);
        // Afficher la lecture
        return $this->render("user/read.html.twig", [
            "user" => $user,
        ]);
    }

    // UPDATE
    #[Route('/user/{id}/edit', 
    name: 'user_edit', 
    //Ajouter une méthode
    methods: ['GET','POST'],
    requirements: ["id" => "\d+"])]
    
    public function edit(
        $id, 
        //Ajouter Render Request
        Request $request, 
        UserRepository $userRepository,
        // Besoin de la BDD
        EntityManagerInterface $em
        )
    {
        // Chercher le user 
        $user = $userRepository->find((int)$id);
        // dd($user);

        // Créer le formulaire avec createForm() en ajoutant des datas (éviter des valeurs null)
        $form = $this->createForm(UserType::class, $user);
        // dd($form);

        //Utilisation de la méthode handleRequest()
        $form->handleRequest($request);
        //Ajouter une condition
        if ($form->isSubmitted() && $form->isValid() ){
            //Déclarer une variable user
            $user = $form->getData();
            //Pas besoin de $em->persist() car pas de vérification 
            $em->flush();
            dd($form->getData());
        }

        //Retourner dans la vue
        return $this->render("user/edit.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    // DELETE
    #[Route('/user/{id}/delete',
    name: 'user_delete',
    methods: ['GET'],
    requirements: ["id" => "\d+"]
    )]

    public function delete(
        $id, 
        Request $request, 
        UserRepository $userRepository,
        EntityManagerInterface $em
        ): Response
    {
        $user = $userRepository->find((int)$id);
        //Condition pour tester la méthode
        if($request->query->get('method')=== 'DELETE'){
            $em->remove($user);
            $em->flush();
            // dd($id);

            //Redirection sur une autre route
            return $this->redirectToRoute(('app_user'));
        }
        return $this->render("user/read.html.twig", [
            "user" => $user,
        ]);
    }
}
