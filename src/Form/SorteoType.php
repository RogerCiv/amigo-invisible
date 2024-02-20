<?php

namespace App\Form;

use App\Entity\Emparejamiento;
use App\Entity\Sorteo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SorteoType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('presupuesto')
            ->add('fecha')
            ->add('tipoSorteo', ChoiceType::class, [
                'label' => 'Tipo de Sorteo',
                'choices' => [
                    'Normal' => 'normal',
                    'Entre Grupo de Amigos' => 'grupo_amigos',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('grupoAmigos', EntityType::class, [
                'class' => 'App\Entity\GrupoAmigos',
                'choice_label' => 'nombre',
                'label' => 'Grupo de Amigos',
                'required' => false,
                'disabled' => false, // Este campo estará activado 
            ]);

            $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
               
                $sorteo = $event->getData();
            
                if ($sorteo->getTipoSorteo() == 'grupo_amigos') {
            
                    $usuariosGrupo = $sorteo->getGrupoAmigos() ? $sorteo->getGrupoAmigos()->getUsuarios()->toArray() : [];
            
                    shuffle($usuariosGrupo);
            
                    foreach ($usuariosGrupo as $index => $usuario) {
                        $destinatario = $usuariosGrupo[($index + 1) % count($usuariosGrupo)]; // Circular
            
                        $emparejamiento = new Emparejamiento();
                        $emparejamiento->setUsuarioRegala($usuario);
                        $emparejamiento->setUsuarioRecibe($destinatario);
                        $emparejamiento->setSorteo($sorteo);
            
                        $sorteo->addEmparejamiento($emparejamiento);
                        
                        
                        if ($sorteo->getId() === null) {
                            $sorteo->setStatus('finalizado');
                            $this->entityManager->persist($emparejamiento);
                        }
                    }
                    $this->entityManager->flush();
                }
            });
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sorteo::class,
        ]);
    }
}
