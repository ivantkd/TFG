<?php

namespace App\Form;

use App\Entity\Tickets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TicketsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Departamento')
            ->add('TipoIncidente')
            ->add('Dispositivo')
            ->add('Explicacion', TextAreaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tickets::class,
        ]);
    }
}
