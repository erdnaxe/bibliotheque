<?php

namespace BooklistBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title', 'text', array('label' => 'book.title'))
                ->add('writer', 'text', array('label' => 'book.author'))
                ->add('editor', 'text', array('label' => 'book.editor'))
                ->add('quality', 'choice', array(
                    'label' => 'book.quality',
                    'choices' => array(
                        0 => 'book.qualities.new',
                        1 => 'book.qualities.good',
                        2 => 'book.qualities.medium',
                        3 => 'book.qualities.used',
                        4 => 'book.qualities.old'
                    ),
                    'expanded' => true
                ))
                ->add('toSell', 'choice', array(
                    'label' => 'book.toSell',
                    'choices' => array(0 => 'No', 1 => 'Yes'),
                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BooklistBundle\Entity\Book'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'booklistbundle_book';
    }

}
