<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Plat;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CommandeplatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('livraison')
            ->add('quantity')
            ->add('idU',EntityType::class,[
                'class' => Utilisateur::class,
                'choice_label'=>'nomPrenom',


            ])
            ->add('referenceplat')
            ->add('Valider',SubmitType::class);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
