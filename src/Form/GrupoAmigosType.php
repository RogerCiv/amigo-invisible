<?php

namespace App\Form;

use App\Entity\GrupoAmigos;
use App\Entity\Invitacion;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrupoAmigosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('usuarios', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username', // Cambia 'id' al campo que deseas mostrar en la lista
                'multiple' => true,
                'expanded' => true, // Puedes cambiar esto según tus necesidades
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GrupoAmigos::class,
            'usuario' => null,	
        ]);
    }
}
