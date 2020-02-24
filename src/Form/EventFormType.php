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
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('starttime', DateTimeType::class, [
                'label' => 'Date de début',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Durée'
            ])
            ->add('lastInscriptionTime', DateTimeType::class, [
                'label' => 'Date de clôture',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'
            ])
            ->add('places', NumberType::class, [
                'label' => 'Nombre de places',
                'required' => false
            ])
            ->add('information', TextType::class, [
                'label' => 'Informations'
            ])
            ->add('location', EntityType::class, [
                'label' => 'Lieu',
                'class' => Location::class
            ])
            //->add('add_location', LocationFormType::class, [
            //    'required' => false,
            //    'mapped' => false
            //])
            ->add('campus', EntityType::class, [
                'label' => 'Site',
                'class' => Campus::class
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrement'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
