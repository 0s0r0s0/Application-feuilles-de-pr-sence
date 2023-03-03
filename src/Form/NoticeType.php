<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Notice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoticeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('beginAt', null, [
                'label' => 'Date :'
            ])
            ->add('title', null, [
                'label' => 'Titre :'
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'required' => false,
                'choice_label' => 'lastName',
                'label' => 'Nom du salarié ',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Notice::class,
        ]);
    }
}
