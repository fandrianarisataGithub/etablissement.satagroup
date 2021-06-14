<?php

namespace App\Form;

use App\Entity\Admin;
use App\Entity\Universite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'choices'  => [
                    "Admin de l'établissement" => "admin",
                    'super Admin' => "super_admin"
                ],
            ])
            ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('matricule')
            ->add('pdp')
            ->add('etablissement', EntityType::class, [
                "class" => Universite::class,
                "choice_label" => "nom"
            ])
        ;
        $builder->get('roles') // child with name 'roles'
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                     // transform the array to a string
                     return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                     // transform the string back to an array
                     return [$rolesString];
                }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
