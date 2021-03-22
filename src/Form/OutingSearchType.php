<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\OutingSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutingSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'required' => false,
                'class' => Campus::class,
                'mapped' => false,
                'choice_label' => 'name',
                'label' => 'Campus :',
                'row_attr' => [
                    'class' => 'row'
                ],
                'label_attr' => [
                    'class' => 'col-3 col-form-label'
                ],
                'attr' => [
                    'class' => 'col-6 form-control'
                ]
            ])
            ->add('searchBar', TextType::class, [
                'required' => false,
                'label' => 'Le nom de la sortie contient :',
                'row_attr' => [
                    'class' => 'row'
                ],
                'label_attr' => [
                    'class' => 'col-4 col-form-label'
                ],
                'attr' => [
                    'class' => 'col-5 form-control',
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('minDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Entre',
            ])
            ->add('maxDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'et'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OutingSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
