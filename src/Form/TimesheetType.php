<?php

namespace App\Form;

use App\Entity\Month;
use App\Entity\Timesheet;
use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimesheetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Crée des variables avec année en cours, année précédente et suivante
        $now = date("Y");
        $lastYear = $now - 1;
        $nextYear = $now ++;

        $builder

            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'lastName',
                'label' => 'Nom :'
            ])
            ->add('year', ChoiceType::class, [
                'label' => 'Année :',
                'choices' => array(
                    $lastYear => $lastYear,
                    $now => $now,
                    $nextYear => $nextYear,
                )
            ])
            ->add('month', EntityType::class, [
                'class' => Month::class,
                'choice_label' => 'label',
                'label' => 'Mois :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Timesheet::class,
        ]);
    }
}
