<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Stage;
use App\Entity\Tuteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('poste', TextType::class)
            ->add('type',ChoiceType::class, [
                'choices' => [ 'Oui' => true,'Non' => false,],
                'label' => 'Plein temps ?'])
            ->add('description', TextareaType::class)
            ->add('actif',ChoiceType::class, [
                'choices' => [
                    'Oui' => true,'Non' => false, ], 'label' => 'Actif ?'])
            ->add('email', EmailType::class)
            ->add('societe', TextType::class)
            ->add('ville', TextType::class)
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class, 'choice_label' => 'nom',])
            ->add('tuteurs', EntityType::class, [
                'class' => Tuteur::class, 'multiple' => true, 'expanded' => true, 'choice_label' => 'nom',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}
