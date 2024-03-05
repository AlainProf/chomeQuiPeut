<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\OffreEmploi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\{SubmitType};

class OffreEmploiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('salaireAnnuel')
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nom',
            ])
            ->add('publier', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OffreEmploi::class,
        ]);
    }
}
