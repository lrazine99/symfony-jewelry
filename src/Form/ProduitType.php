<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        if($options['ajouter'] == true)
        {

            $builder
                ->add('titre', TextType::class, [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => "Saisir le titre",
                        'class' => "bg-white"
                    ],
                    'constraints' =>[
                        new NotBlank([
                            'message' => 'Veuillez renseigner le titre'
                        ]),
                        new Length([
                            'min' => 2,
                            'minMessage' => "Veuillez renseigner un titre de 2 caractères minimum"
                        ])
                    ]
                ])

                ->add('category', EntityType::class,[
                    "class" => Category::class,
                    "choice_label" => "nom"
                ])


                ->add('prix', NumberType::class)

                ->add('image', FileType::class, [
                    'label' => 'Image',
                    'required' => false
                ])





                // ->add('save', SubmitType::class, [
                //     'attr' => ['class' => 'btn btn-danger'],
                // ])
            ;
        }


        elseif($options['modifier'] == true)
        {

            $builder
                ->add('titre', TextType::class, [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => "Saisir le titre",
                        'class' => "bg-white"
                    ],
                    'constraints' =>[
                        new NotBlank([
                            'message' => 'Veuillez renseigner le titre'
                        ]),
                        new Length([
                            'min' => 2,
                            'minMessage' => "Veuillez renseigner un titre de 2 caractères minimum"
                        ])
                    ]
                ])


                ->add('category', EntityType::class,[
                    "class" => Category::class,
                    "choice_label" => "nom"
                ])


                ->add('prix', NumberType::class)

                // ->add('image', FileType::class, [
                //     'label' => 'Image',
                //     'required' => false
                // ])

                ->add('imageFile', FileType::class, array(
                    'data_class' => null,
                    'required' => false,
                    'mapped' => false
                    ))



                // ->add('save', SubmitType::class, [
                //     'attr' => ['class' => 'btn btn-danger'],
                // ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'ajouter' => false,
            'modifier' => false

        ]);
    }
}
