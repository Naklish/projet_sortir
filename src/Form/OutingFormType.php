<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\Outing;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie :'
            ])
            ->add('dateHourStart', DateTimeType::class, [
                'label' => 'Date et heure de la sortie :'
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée :'
            ])
            ->add('deadlineRegistration', DateType::class, [
                'label' => 'Date limite d\'inscription :'
            ])
            ->add('maxRegistration', TextType::class, [
                'label' => 'Nombre de places :'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description et infos :'
            ])
            ->add('campus',EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'label' => 'Campus :'
            ])
            ->add('locations', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'city.name',
                'label' => 'Lieu :'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
        ]);
    }
}
