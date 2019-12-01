<?php

namespace App\Form;

use App\Entity\Pacientes;
use App\Entity\Prontuarios;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProntuariosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dsProntuario', null, array(
                'label' => 'Descrição'
            ))
            ->add('data', DateType::class, array(
                'label' => 'Data',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'class' => 'datepicker'
                )
            ))
            ->add('laudos', ChoiceType::class, array(
                'label' => 'Laudo',
                'multiple' => false,
                'placeholder' => '-- Selecione --',
                'attr' => array(
                    'class' => 'select2'
                )
            ))
            ->add('idpacientes', EntityType::class, array(
                'label' => 'Paciente',
                'multiple' => false,
                'class'=> Pacientes::class,
                'choice_label' => 'nome',
                'attr' => array(
                    'class' => 'select2'
                ),
                'placeholder' => '-- Selecione --',
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prontuarios::class,
        ]);
    }
}
