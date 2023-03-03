<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\TimeTable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class TimeTableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

        ->add('employee', EntityType::class, [
        'class' => Employee::class,
        'choice_label' => 'lastName',
        'label' => 'Nom :'
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TimeTable::class,
        ]);
    }
}
