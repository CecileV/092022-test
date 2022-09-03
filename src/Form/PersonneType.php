<?php

namespace App\Form;

use App\Entity\Personne;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('birth_date', BirthdayType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'label' => 'Date de Naissance',
                'constraints' => [
                    new Constraints\Callback(function($object, ExecutionContextInterface $context) {
                        $birth_date = $object;
                        if (!empty($birth_date)) {
                            $now = new DateTime();
                            $difference = $now->diff($birth_date);
                            $age = $difference->y;

                            if ($age > 150 || $age < 0) {
                                $context
                                    ->buildViolation('La personne ne peut pas avoir plus de 150 ans.')
                                    ->addViolation();
                            }
                        }
                    }),
                ],
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
            'method' => 'POST'
        ]);
    }
}
