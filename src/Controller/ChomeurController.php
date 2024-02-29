<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response, Request};
use Symfony\Component\Routing\Attribute\Route;


use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Chomeur;
use App\Entity\Adresse;
use App\Form\ChomeurType;

 

class ChomeurController extends AbstractController
{
    
    #[Route('/chomeur_details/{id}', name:'chomeur_details')]
    public function chomeur_details(ManagerRegistry $doctrine, $id): Response
    {
       $em = $doctrine->getManager();

       $chomeur = $em->getRepository(Chomeur::class)->find($id);

       //dd($chomeur);
       return $this->render("detailsChomeur.html.twig", ['chomeur' => $chomeur]);
    }

    #[Route('/creationChomeur')]
    public function creationChomeur(ManagerRegistry $doctrine, Request $request): Response
    {
        ini_set('date.timezone', 'America/New_York');
        $chom = new Chomeur();
        $chom->setDateInscription(new \DateTime);
        
        $formExterne = $this->createForm(ChomeurType::class, $chom);
       
        $formExterne->handleRequest($request);
        
        if ($formExterne->isSubmitted())
        {
            // Nous somme en mode soumission de form
            {
                //Est-ce que les donnée de l'utilsateurs sont valides
                if ($formExterne->isValid())
                {
                     $em = $doctrine->getManager();
                     $em->persist($chom);
                     $em->flush();
 
                     $this->addFlash('succes', "Chômeur " . $chom->getNom() . " créé avec succès");
                     return $this->redirectToRoute('accueil');
                }
                else
                {
                    // Les données contiennent des erreurs
                    $this->addFlash('erreur', "Au moins une erreur dans les données fournies");
                }
            }
        }
     
        return $this->render('ajouterChomeur.html.twig',
                          array('formChomeur' => $formExterne->CreateView()));
}



    #[Route('/creationChomeurHC')]
    public function creationChomeurHC(ManagerRegistry $doctrine): Response
    {
        $chom = new Chomeur();

        $chom->setNom("Pedro Canada");
        $chom->setCourriel("pedro@gmail.com");
        $chom->setTelephone("1234567890");
        $chom->setDateNaissance(new \DateTime());
        $chom->setDateInscription(new \DateTime());

        $adr = new Adresse();
        $adr->setNumCivique("1234");
        $adr->setRue("des Pignons");
        $adr->setVille("St-Lin des Laurentides");

        $chom->setAdresse($adr);

        $em = $doctrine->getManager();

        $em->persist($chom);
        $em->flush();
        
        return $this->RedirectToRoute('accueil');
    }


}
