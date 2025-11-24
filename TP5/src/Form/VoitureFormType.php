<?php
// src/Form/VoitureForm.php

namespace App\Form;

use App\Entity\Voiture;
use App\Entity\Modele;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VoitureForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('serie', TextType::class)
            ->add('dateMiseEnMarche', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('modeleEntity', EntityType::class, [
                'class' => Modele::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Sélectionner un modèle',
                'required' => false,
            ])
            ->add('prixJour', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}