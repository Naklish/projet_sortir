<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class OutingCancelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cancelMotive', TextareaType::class, [
                'label' => 'Motif :',
                'mapped' => false
            ])
            ->add('register', SubmitType::class, [
                'label' => 'Enregister',
                'attr' => array('class' => 'btn btn-lg')
            ])
            ;
    }
}