<?php

namespace App\Form;

use App\Entity\Calendrier;
use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('beginAt', null, [
                'label' => 'Début : ',
                'data' => new \DateTime(),
                'date_format' =>'dd MM yyyy'
            ])
            ->add('endAt', null, [
                'label' => 'Définir une fin si vous le souhaitez : ',
                'help' => '(Optionnel)',
                'date_format' =>'dd MM yyyy'
            ])
            ->add('title', null, [
                'label' => 'Titre : '
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'required' => false,
                'choice_label' => 'lastName',
                'label' => 'Nom du salarié : ',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calendrier::class,
        ]);
    }
}