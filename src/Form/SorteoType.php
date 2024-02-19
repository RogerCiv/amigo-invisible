<?php

namespace App\Form;

use App\Entity\Sorteo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SorteoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('presupuesto')
            ->add('fecha')
            // ->add('grupoAmigos', EntityType::class, [
            //     'class' => 'App\Entity\GrupoAmigos',
            //     'choice_label' => 'nombre', // Ajusta este campo según tu entidad GrupoAmigos
            //     'label' => 'Grupo de Amigos',
            //     // Otros opciones que desees configurar
            // ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sorteo::class,
        ]);
    }
}
