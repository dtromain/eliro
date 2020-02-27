<?php

namespace App\Form;

use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndexFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'required' => true,
                'label' => 'Site'
            ])
            ->add('search', TextType::class, [
                'required' => false,
                'label' => 'Le nom de la sortie contient'
            ])
            ->add('first_date', DateType::class, [
                'required' => false,
                'label' => 'Entre le',
                'widget' => 'single_text'
            ])
            ->add('second_date', DateType::class, [
                'required' => false,
                'label' => 'Et le',
                'widget' => 'single_text'
            ])
            ->add('isPlanner', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties dont je suis l\'organisateur'
            ])
            ->add('isParticipating', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties auxquelles je suis inscrit'
            ])
            ->add('isNotParticipating', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties auxquelles je ne suis pas inscrit'
            ])
            ->add('isPassed', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties passées'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
