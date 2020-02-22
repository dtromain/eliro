<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('starttime', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'
            ])
            ->add('duration', NumberType::class)
            ->add('lastInscriptionTime', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'
            ])
            ->add('places', NumberType::class, [
                'required' => false
            ])
            ->add('information', TextType::class)
            ->add('location', EntityType::class, [
                'class' => Location::class
            ])
            //->add('add_location', LocationFormType::class, [
            //    'required' => false,
            //    'mapped' => false
            //])
            ->add('campus', EntityType::class, [
                'class' => Campus::class
            ])
            ->add('save', SubmitType::class, ['label' => 'Save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
