<?php

namespace E02\Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface; // <-- fix typo here
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert; // <-- add this

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('message', 'text')
                ->add('timestamp', 'choice', [
                    'choices' => [
                        'yes' => 'Yes',
                        'no' => 'No',
                    ],
                    'expanded' => true, // radio buttons
                    'multiple' => false, // single choice
                    'label' => 'Include timestamp?',
                      
                ]);
    }

    public function getName()
    {
        return 'contact';
    }
}