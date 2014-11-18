<?php
/**
 * This file is part of JKN
 *
 * (c) Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 *
 * @author : Muhamad Surya Iksanudin
 **/
namespace Ihsan\AjaxFormBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AjaxChoiceFieldType extends AbstractAjaxFieldType
{
    /**
     * {@inheritDoc}
     **/
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array('target'));
        $resolver->setDefaults(array(
            'event' => 'onchange',
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'xchoice';
    }
} 