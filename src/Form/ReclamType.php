<?php

namespace App\Form;

use App\Entity\Reclam;
use App\Entity\TypeRec;
use App\Entity\Utilisateur;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReclamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description',TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
            ])
            ->add('etatrec',null,['empty_data' => 'En attente'])
            ->add('idu',EntityType::class,[
                'class'=> Utilisateur::class,
                'choice_label'=>'idu', ])
            ->add('typerec',EntityType::class,[
                'class'=> TypeRec::class,
                'choice_label'=>'typerec',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclam::class,
        ]);
    }
}
