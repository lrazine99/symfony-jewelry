<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User(); // on créé une instance de l'entity User 

        $form = $this->createForm(UserType::class, $user);// on créé un formulaire : 2 arguments : le formulaire (UserType) et l'instance de User

        $form->handleRequest($request); // traitement du formulaire


        if($form->isSubmitted() && $form->isValid()) // si le formulaire a été soumis (submit) et qu'il est valide on rentre dans la condition
        { 

            // On observe que dans l'instance request, à la méthode request (post) on retrouve l'instance User qui contient les valeurs des champs
            //dd($request); 


            // Pour encoder un mot de passe avec la class UserPasswordEncoderInterface :
            // - il faut implémenter l'entité User avec UserInterface (class qui permet de désigner l'entity qui permettra l'inscription connexion : security sur le site)
            // - dans security.yaml il faudra préciser l'encoder : l'entity et l'agorithm (clé de hashage)

            // $hash récupère le mot de passe encodé 
            $hash = $encoder->encodePassword($user, $user->getPassword());



            
            // on a besoin de la classe EntityManagerInterface qui permet d'envoyer des données (insérer modifier supprimer) en base de données

            // on injecte le mot de passe encodé dans l'instance sur le champ password 
            $user->setPassword($hash);

            $user->setRoles(['ROLE_USER']);
            $manager->persist($user); // on prépare l'envoie, c'est-à-dire ce que l'on veut envoyer : instance User (contient les données du formulaire)
            $manager->flush(); // on envoie l'instance en base de données


            return $this->redirectToRoute('connexion'); // une fois envoyé, on redirige l'utilisateur sur la route connexion

        }


        return $this->render('security/inscription.html.twig', [
            "form" => $form->createView() // on apporte à twig la vue du formulaire 
        ]);
    }


    /**
     * @Route("/connexion", name="connexion")
     */
    public function connexion(): Response
    {
        return $this->render('security/connexion.html.twig');
    }


    /**
     * La fonction roles() permet de checker les roles des personnes se connectant
     * elle permet de rediriger l'utilisation en fonction de son rôle
     * Relié à default_target_path dans security.yaml 
     * 
     * @Route("/roles", name="roles")
     */
    public function roles()
    {
        if($this->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute('back_office');
        }
        elseif($this->isGranted('ROLE_USER'))
        {
            return $this->redirectToRoute('accueil');
        }

    }



    /**
     * @Route("/deconnexion", name="deconnexion")
     */
    public function deconnexion()
    {

    }






}
