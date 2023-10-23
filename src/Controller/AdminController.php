<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


/**
 * Cette route placée avant la class permet d'intégrer à chaque route de ce controller le terme /admin
 * on a défini dans security.yaml que toutes les routes commençant par /admin était accessible pour le ROLE_ADMIN
 * 
 * @Route("/admin")
 */


class AdminController extends AbstractController
{
    /**
     * @Route("/back_office", name="back_office")
     */
    public function back_office(): Response
    {
        return $this->render('admin/back_office.html.twig');
    }



    /**
     * la fonction produit_afficher permet de gérer les produits (affichage sous forme de tableau avec les icônes modif et supp)
     * 
     * @Route("/gestion_produit/afficher", name="produit_afficher")
     */
    public function produit_afficher(ProduitRepository $repoProduit)
    {
        $produits = $repoProduit->findAll();

        return $this->render('admin/produit_afficher.html.twig', [
            "produits" => $produits
        ]);
    }





        /**
         * La fonction produit_ajouter permet d'ajouter un produit en bdd
         * 
         * @Route("/gestion_produit/ajouter", name="produit_ajouter", methods="POST|GET")
         */
        public function produit_ajouter(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger)
        {
            $produit = new Produit(); // création d'une instance de la class Produit
            //dd($produit);

            // Pour créer un formulaire il faut 2 arguments :
            // le dossier Type
            // l'instance
            // il peut y avoir un 3e argument : un array
            $form = $this->createForm(ProduitType::class, $produit, array(
                'ajouter' => true
            ));
            // createForm génère un objet => $form
            // pour afficher le use, sur la class ==> CTRL + ALT + i


            $form->handleRequest($request);

            dump($produit);
            

            if($form->isSubmitted() && $form->isValid())
            {
                


                // on récupère toutes les informations sur l'input files (image)
                $imageFile = $form->get('image')->getData();

                //dump($imageFile);die;

                
                if($imageFile)// s'il y a une image upload on rentre dans la condition
                // s'il n'y en a pas, on ne s'occupe pas de cette condition, le champ image dans la table peut être null
                {
                    // Réécriture du nom de l'image
                    


                    // dans $nomImage on récupère seulement la dénomination de l'image (sans l'extension)
                    $nomImage = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    
                    $safeFilename = $slugger->slug($nomImage); // ?
                    //dd($safeFilename );

                    $newName = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                    //dd($newName ); newName = nomDeLImage + caractères aléatoires + extenionDeLimage


                    try // on éxécute le code dans le try
                    {
                        $imageFile->move(
                            $this->getParameter('images_directory'),
                            $newName
                        );
                    }
                    catch (FileException $e) // s'il y a une erreur, on récupère l'erreur et on affiche la raison
                    {

                    }

                    $produit->setImage($newName);
                }

                
                $produit->setCreatedAt(new \DateTime('now'));

                //dump($produit);

                $manager->persist($produit);
                $manager->flush();

                return $this->redirectToRoute("produit_afficher");

            }



            return $this->render('admin/produit_ajouter.html.twig', [
                "formProduit" => $form->createView()
            ]);
        }

      /**
       * La fonction produit_modifier permet de modifier un produit existant
       * 
       * @Route ("/gestion_produit/modifier/{id}" , name="produit_modifier")
       */
      public function produit_modifier(Produit $produit, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger)
      {
        // $idProduit = $produit->getId();
        // dump($idProduit);
        //dd($produit);

        $form = $this->createForm(ProduitType::class, $produit, array(
            'modifier' => true
        ));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

                        // on récupère toutes les informations sur l'input files (imageFile)
                        $imageFile = $form->get('imageFile')->getData();

                        //dump($imageFile);die;
            
                        
                        if($imageFile)// s'il y a une image upload on rentre dans la condition
                        // s'il n'y en a pas, on ne s'occupe pas de cette condition, le champ image dans la table peut être null
                        {
                            // Réécriture du nom de l'image
                          
            
            
                            // dans $nomImage on récupère seulement la dénomination de l'image (sans l'extension)
                            $nomImage = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                            
                            $safeFilename = $slugger->slug($nomImage); // ?
                            //dd($safeFilename );
            
                            $newName = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
            
                            //dd($newName ); newName = nomDeLImage + caractères aléatoires + extenionDeLimage
            
            
                            try // on éxécute le code dans le try
                            {
                                $imageFile->move(
                                    $this->getParameter('images_directory'),
                                    $newName
                                );
                            }
                            catch (FileException $e) // s'il y a une erreur, on récupère l'erreur et on affiche la raison
                            {
            
                            }
            
                            $produit->setImage($newName);
                        }
            $manager->persist($produit);
            $manager->flush();

            return $this->redirectToRoute("produit_afficher" );
            //return $this->redirectToRoute("fiche_produit" ,  ['id' => $produit->getId()]  );
        }

        return $this->render('admin/produit_modifier.html.twig', [
            "formProduit" => $form->createView(),
            "produit" => $produit
        ]);
      }


      /**
       * La fonction produit_supprimer permet de supprimer un produit existant
       * 
       * @Route ("/gestion_produit/supprimer/{id}" , name="produit_supprimer", methods="delete")
       */
      public function produit_supprimer(Produit $produit, EntityManagerInterface $manager, Request $request )
      {

        if($this->isCsrfTokenValid("SUP". $produit->getId(), $request->get('_token')))
        {
            $manager->remove($produit);
            $manager->flush();

            return $this->redirectToRoute("produit_afficher");
        }
        
      }
}
