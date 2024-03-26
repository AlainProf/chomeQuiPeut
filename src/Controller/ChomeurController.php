<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response, Request};
use Symfony\Component\Routing\Attribute\Route;


use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Chomeur;
use App\Entity\OffreEmploi;
use App\Entity\Adresse;
use App\Entity\Postulation;
use App\Form\ChomeurType;

 

class ChomeurController extends AbstractController
{
    
    #[Route('/chomeurs', name:'chomeurs')]
    public function chomeurs(ManagerRegistry $doctrine): Response
    {
        $tabChomeurs = $doctrine->getManager()->getRepository(Chomeur::class)->findAll();
        return $this->render("chomeurs.html.twig", ['chomeurs' =>$tabChomeurs]);
    }


     #[Route('/postuler', name:'postuler')]
     public function postuler(ManagerRegistry $doctrine, Request $request): Response
     {
        $tabChomeurs = $doctrine->getManager()->getRepository(Chomeur::class)->findAll();
        $tabOffresEmplois = $doctrine->getManager()->getRepository(OffreEmploi::class)->findAll();

        $soumettre = $request->query->get('soumettre');
        if (isset($soumettre))
        {
            $em = $doctrine->getManager();
            $idChomeur = $request->query->get('chomeurPostulant');
            $chomeurPostulant = $em->getRepository(Chomeur::class)->find($idChomeur);

            $idOE = $request->query->get('offreEmploiPostulee');
            $offreEmploiPostulee = $em->getRepository(OffreEmploi::class)->find($idOE);
            
            $postulation = new Postulation;

            $postulation->setDatePostulee(new \DateTime);
            $postulation->setStatut("initial");
            $postulation->setChomeurPostulant($chomeurPostulant);
            $postulation->setOffreEmploiPostulee($offreEmploiPostulee);

            $em->persist($postulation);
            $em->flush();

            $this->addFlash("notice", $chomeurPostulant->getNom() . " a postulé sur " . $offreEmploiPostulee->getTitre());

            return $this->redirectToRoute("chomeurs");
        }

        return $this->render("postulation.html.twig", ['chomeurs' => $tabChomeurs, 'offresEmplois' => $tabOffresEmplois]);
    }


    #[Route('/chomeur_details/{id}', name:'chomeur_details')]
    public function chomeur_details(ManagerRegistry $doctrine, $id): Response
    {
       $em = $doctrine->getManager();

       $chomeur = $em->getRepository(Chomeur::class)->find($id);

       //dd($chomeur);
       return $this->render("detailsChomeur.html.twig", ['chomeur' => $chomeur]);
    }

    //-----------------------------------------------
    //
    //-----------------------------------------------
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
