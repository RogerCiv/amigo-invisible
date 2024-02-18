<?php

namespace App\Form;

use App\Entity\GrupoAmigos;
use App\Entity\Invitacion;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvitacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('grupoAmigos', EntityType::class, [
                'class' => GrupoAmigos::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('usuarioInvitado', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
            ->add('usuarioCreador', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invitacion::class,
        ]);
    }
}
