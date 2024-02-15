<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Chomeur;
use App\Entity\Adresse;
use Doctrine\Persistence\ManagerRegistry;
 

class ChomeurController extends AbstractController
{
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
