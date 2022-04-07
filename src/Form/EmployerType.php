<?php

namespace App\Form;

use App\Entity\Employer;
use App\Entity\Jobem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPrenom')
            ->add('telephone')
            ->add('adresse')
            ->add('picture')
            ->add('salaire')
            ->add('jobEm',EntityType::class,[
                'class'=> Jobem::class,
                'choice_label'=>'jobEm', ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employer::class,
        ]);
    }
}
