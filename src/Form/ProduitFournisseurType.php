<?php

namespace App\Form;

use App\Entity\Fournisseur;
use App\Entity\Produit;
use App\Entity\ProduitFournisseur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitFournisseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idf',EntityType::class,[
                'class'=> Fournisseur::class,
                'choice_label'=>'idF'])
            ->add('nomprod',EntityType::class,[
                'class'=> Produit::class,
                'choice_label'=>'nomProd',])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProduitFournisseur::class,
        ]);
    }
}
