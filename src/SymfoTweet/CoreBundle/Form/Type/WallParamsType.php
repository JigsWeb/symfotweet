<?php
namespace SymfoTweet\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class WallParamsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, array(
                    'choices'  => array(
                      'Hashtag' => "#",
                      "Compte" => "from:",
                      "Mot-clé" => ""
                    ),
                    'choices_as_values' => true,
                  ))
            ->add('text', TextType::class, array(
              'label' => 'Contexte'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SymfoTweet\CoreBundle\Entity\WallParams',
        ));
    }
}
