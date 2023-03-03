<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Job;
use App\Entity\Status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'label' => 'Prénom : '
            ])
            ->add('lastName', null, [
                'label' => 'Nom : '
            ])
            ->add('isActive', null, [
                'label' => 'Décocher pour rendre inactif sans supprimer : ',
                'data' => true,
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'required' => true,
                'choice_label' => 'label',
                'label' => 'Choisissez le service du salarié : ',
                'multiple' => false,
                ])

            ->add('job', EntityType::class, [
                'class' => Job::class,
                'required' => true,
                'choice_label' => 'label',
                'label' => 'Choisissez la fonction du salarié : ',
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
