<?php

namespace App\Form;

use App\Entity\Track;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TrackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('duration')
            ->add('file', FileType::class, [
                'data_class' => null,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '20Mi',
                        'mimeTypes' => [
                            'audio/mpeg'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid audio file',
                    ])
                ],
            ])
            ->add('url', UrlType::class)
            ->add('album')
            ->add('artiste')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Track::class,
        ]);
    }
}
