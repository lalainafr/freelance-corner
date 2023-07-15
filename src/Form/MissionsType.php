<?php

namespace App\Form;

use App\Entity\Missions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MissionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'label' => 'Nom',
            'attr' => [
                'placeholder' => 'Tapez le nom de la mission',
            ]
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'attr' => [
                'placeholder' => 'Donnez une description de la mission',
            ]
        ])
        ->add('price', TextType::class, [
            'label' => 'Prix',
            'attr' => [
                'placeholder' => 'Mettez prix pourr effectuer la mission',
            ]
        ])
        ->add('deadline', DateTimeType::class,[
            'widget' => 'single_text',
            'label' => 'Délais de livraison',
        ])
        ->add('createdAt',DateTimeType::class ,[
            'label' => false,
            'attr' => [
                'class' => 'd-none'
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Valider',
            'attr' => [
                'class' => 'btn btn-info'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Missions::class,
        ]);
    }
}
