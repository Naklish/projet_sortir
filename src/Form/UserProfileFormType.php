<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Pseudo :',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3])
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom :',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3])
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom :',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3])
                ]
            ])
            ->add('phone', NumberType::class, [
                'label' => 'Téléphone :',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3])
                ]
            ])
            ->add('mail', EmailType::class, [
                'label' => 'E-mail :',
                'constraints' => new NotBlank(),
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'constraints' => new NotBlank(),
            ])
            ->add('password', RepeatedType::class, [
                'constraints' => new NotBlank(),
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs des mots de passe doivent correspondre',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe :'],
                'second_options' => ['label' => 'Confirmation :']
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo :',
                'label_attr' => array('id' => 'label-input-file'),
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            "image/jpeg", "image/gif", "image/png", "image/x-png", "image/x-citrix-png", "image/x-citrix-jpeg", "image/pjpeg",
                        ],
                        'mimeTypesMessage' => 'Format de la photo : jpeg, gif, png, x-png, x-citrix-png, x-citrix-jpeg, pjpeg'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
