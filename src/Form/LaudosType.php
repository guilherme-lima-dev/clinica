<?php

namespace App\Form;

use App\Entity\Exames;
use App\Entity\Laudos;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LaudosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dsLaudo', null, array(
                'label' => 'Descrição'
            ))
            ->add('data', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'class' => 'datepicker'
                )
            ))
            ->add('exames', null, array(
                'placeholder' => '-- Selecione --',
                'multiple' => true,
                'attr' => array(
                    'class' => 'select2',
                    'data-placeholder' => '-- Selecione --'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Laudos::class,
        ]);
    }
}
