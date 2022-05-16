<?php

namespace App\Form;

use App\Entity\Commentaire;
use App\Entity\Evenement;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commantaire')
            ->add('nbrlike')
            ->add('idu',EntityType::class,[
                'class'=> Utilisateur::class,
                'choice_label'=>'idu',])
            ->add('idevent',EntityType::class,[
                'class'=> Evenement::class,
                'choice_label'=>'ide',])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
