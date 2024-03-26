<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Chomeur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\{SubmitType, TextType, DateType};


class ChomeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $requis = true;
        $builder
            ->add('nom', TextType::Class, array('required' => $requis) )
            ->add('courriel', TextType::Class, array('required' => $requis) )
            ->add('telephone', TextType::Class, array('required' => $requis) )
            ->add('dateNaissance', DateType::Class, array('required' => true)  )
            ->add('adresse', AdresseType::class)
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chomeur::class,
        ]);
    }
}
