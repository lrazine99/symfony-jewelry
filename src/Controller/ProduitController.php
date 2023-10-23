<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{

    // Lors de la création d'une entity, est également créé son repository
    // (Lors de la création d'un controller, est également créé son template)
    // repository : tous types de la requête SELECT : par rapport à l'entité relié
    // repository = récupérer

    // EntityManagerInterface (alias manager)
    // ==> envoyer (INSERT INTO / UPDATE / DELETE)
    // --------------------------------------------------




    /**
     * la fonction catalogue permet d'afficher tous les produits sur une vue client
     * 
     * @Route("/catalogue", name="catalogue")
     */
    public function catalogue(ProduitRepository $repoProduit): Response
    {
        // entre parenthèses de la fonction on appelle des dépendances
        // ce que la fonction a besoin 
        // on appelle la class ProduitRepository ==> on place son instance dans le terme qui suit

        $produits = $repoProduit->findAll(); // findAll = SELECT * FROM produit WHERE prix BETWEEN 100 AND 500

        //dd($produits);


        return $this->render('produit/catalogue.html.twig', [
            'produits' => $produits
        ]);
    }






     /**
      * La fonction fiche_produit permet d'afficher la fiche d'un produit existant
      *
      * @Route ("/fiche_produit/{id}", name="fiche_produit")
      */
      public function fiche_produit(Produit $produit)
      { // $id, ProduitRepository $repoProduit

        //$repoProduit = $this->getDoctrine()->getRepository(Produit::class);
     
        //$produit = $repoProduit->find($id); // SELECT * FROM produit WHERE id = $id

        //dump($produit);

        return $this->render('produit/fiche_produit.html.twig' , [
            'produit' => $produit
        ]);
      }






}
