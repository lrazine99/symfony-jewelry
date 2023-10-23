<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    
    
    /**
     * @Route("/base", name="bases")
     */
    // En commentaire avec la syntaxe @ + Terme = annotation
    // annotation = route
    // 1e paramètre : c'est l'url
    // 2e paramètre : name de la route qui est utilisé comme lien dans les path()
    public function base(): Response
    {


        $welcome = "Bienvenue sur la bijouterie";

        //dump($welcome);die;
        // dd($welcome);
        // juste dump() affiche la valeur dans la navbar de symfony qui se situe en bas de la page

        // dump(); die; => arrêt de la lecture du code, il s'affiche sur la navigateur
        // RACCOURCI : dd();
        return $this->render('base/bases.html.twig', [

            'welcomeTwig' => $welcome,
        // {{ variable twig }} => variable du controller de cette fonction             

        ]);
    }


    /**
     * @Route("/", name="accueil")
     */
    public function accueil(): Response
    {
        return $this->render('base/accueil.html.twig' , [
            'sexe' => 'f'
        ]);
    }


    
}
