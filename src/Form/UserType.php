<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                "required" => false,
                "attr" => [
                    "placeholder" => "Veuillez saisir votre email"
                ]
            ])

            ->add('password', PasswordType::class, [
                "required" => false,
                "label" => "Mot de passe",
                "attr" => [
                    "placeholder" => "Veuillez saisir un mot de passe"
                ]
            ])

            ->add('confirm_password', PasswordType::class, [
                "required" => false,
                "label" => "Confirmation du mot de passe",
                "attr" => [
                    "placeholder" => "Veuillez confirmer le mot de passe"
                ]
            ])

            ->add('nom', TextType::class, [
                "required" => false,
                "attr" => [
                    "placeholder" => "Veuillez saisir votre nom"
                ]
            ])

            ->add('prenom', TextType::class, [
                "required" => false,
                "label" => "Prénom",
                "attr" => [
                    "placeholder" => "Veuillez saisir votre prénom"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
