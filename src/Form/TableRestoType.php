<?php

namespace App\Form;

use App\Entity\TableResto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TableRestoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbrplace',null,['required'=>false])
            ->add('etat', ChoiceType::class, [
                'choices'  => [
                    'Reserve' => "Reserve",
                    'Disponible' => "Disponible",
                ],
            ])
            ->add('imagetable',FileType::class,array('data_class'=> null, 'label' => 'Image'))
            ->add('vip', ChoiceType::class, [
                'choices'  => [
                    'Oui' => "Oui",
                    'Non' => "Non",
                ],
            ])
            ->add('prix',null,['required'=>false])
            ->add('Enregistrer',SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TableResto::class,
        ]);
    }
}
