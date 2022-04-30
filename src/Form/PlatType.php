<?php

namespace App\Form;

use App\Entity\Plat;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference',null,['required'=>false])
            ->add('designation',null,['required'=>false])
            ->add('prix',null,['required'=>false])
            ->add('description',null,['required'=>false])
            ->add('imagep',FileType::class,[
                'label'=>'Picture','mapped'=>false,
                'required'=>false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid picture',
                    ])
                ],
            ])

            ->add('nomprod',null,['required'=>false]);
          /*  ->add('nomprod',EntityType::class,[
                'class'=> Produit::class,
                'choice_label'=>'nomprod', ])
        */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void

    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}
