<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\OffreEmploi;
use App\Entity\Entreprise;
use Doctrine\Persistence\ManagerRegistry;

class EntrepriseController extends AbstractController
{
    #[Route('/afficherEntreprise')]
    public function afficherEntreprise(ManagerRegistry $doctrine): Response
    {
        

        return $this->render('entreprises.html.twig', ['tabEntrep' => $tabOffresEmplois, 'tabChomeurs' => $tabChomeurs]);
    }
    
    
    
    #[Route('/creationOffreEmploiHC')]
    public function creationOffreEmploiHC(ManagerRegistry $doctrine): Response
    {
        $entrepA = new Entreprise;
        $entrepA->setNom("Cegep de Singe et Rhum");
        $entrepA->setContact("Oliveir Paul-Hus");

        $entrepB = new Entreprise;
        $entrepB->setNom("Tim Horton");
        $entrepB->setContact("Ringo");

        $offreEmploi = new OffreEmploi();
        $offreEmploi->setTitre("Programmeur junior");
        $offreEmploi->setDescription("Junior et stagiaire bienvenus");
        $offreEmploi->setSalaireAnnuel(50000);
        $offreEmploi->setDatePublication(new \DateTime);
        $offreEmploi->setEntreprise($entrepA);

        $offreEmploiA = new OffreEmploi();
        $offreEmploiA->setTitre("Analyste QA");
        $offreEmploiA->setDescription("5 ans d'expérience");
        $offreEmploiA->setSalaireAnnuel(110000);
        $offreEmploiA->setDatePublication(new \DateTime);
        $offreEmploiA->setEntreprise($entrepA);

        $offreEmploiB = new OffreEmploi();
        $offreEmploiB->setTitre("Agent de pastoral");
        $offreEmploiB->setDescription("Animé la vie spirituel de la génération perdue");
        $offreEmploiB->setSalaireAnnuel("25000");
        $offreEmploiB->setDatePublication(new \DateTime);
        $offreEmploiB->setEntreprise($entrepB);


        $em = $doctrine->getManager();

        $em->persist($offreEmploi);
        $em->persist($offreEmploiA);
        $em->persist($offreEmploiB);

        $em->flush();
        
        return $this->RedirectToRoute('accueil');
    }
}
