<?php

namespace App\Form;

use App\Entity\Sortie;
use App\Entity\Lieu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie'
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de début',
                'widget' => 'single_text'
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'label' => 'Date limite d’inscription',
                'widget' => 'single_text'
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'Nombre max d’inscriptions'
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée (en minutes)'
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Informations supplémentaires',
                'required' => false
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'label' => 'Lieu',
                'placeholder' => '-- Choisir un lieu --'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
