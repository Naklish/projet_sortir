<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\Outing;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                'label' => 'Date et heure de la sortie :',
                'data' => new \DateTime()
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée (minutes) :',
                'attr' => array('min' => 0)
            ])
            ->add('deadlineRegistration', DateType::class, [
                'label' => 'Date limite d\'inscription :',
                'data' => new \DateTime()
            ])
            ->add('maxRegistration', IntegerType::class, [
                'label' => 'Nombre de places :',
                'attr' => array('min' => 0)
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description et infos :'
            ])
            ->add('campus',EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'label' => 'Campus :'
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'mapped' => false,
                'choice_label' => 'name',
                'label' => 'Ville :'
            ])
            ->add('locations', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
                'label' => 'Lieu :'
            ])
            ->add('create', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => array('class' => 'btn btn-outline-primary')
            ])
            ->add('publish', SubmitType::class, [
                'label' => 'Publier',
                'attr' => array('class' => 'btn btn-outline-primary')
            ])

            ->add('rue', TextType::class, [
                'label' => 'Rue :',
                'mapped' => false
            ])

            ->add('codePostal', TextType::class, [
                'label' => 'Code postal :',
                'mapped' => false
            ])

            ->add('latitude', TextType::class, [
                'label' => 'Latitude :',
                'mapped' => false
            ])

            ->add('longitude', TextType::class, [
                'label' => 'Longitude :',
                'mapped' => false
            ])
            ->add('remove', SubmitType::class, [
                'label' => 'Supprimer',
                'attr' => array('class' => 'btn btn-outline-primary')
            ])
            ->add('modify', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => array('class' => 'btn btn-outline-primary')
            ])
            ->add('cancelMotive', TextareaType::class, [
                'label' => 'Motif :',
                'mapped' => false
            ])
            ->add('register', SubmitType::class, [
                'label' => 'Enregister',
                'attr' => array('class' => 'btn btn-outline-primary')
            ])

            // il faut rajouter l'option : 'mapped' => false,
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
        ]);
    }
}
