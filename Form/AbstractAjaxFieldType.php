<?php
/**
 * This file is part of JKN
 *
 * (c) Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 *
 * @author : Muhamad Surya Iksanudin
 **/
namespace Ihsan\AjaxFormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class AbstractAjaxFieldType extends AbstractType
{
    /**
     * @param OptionsResolverInterface $resolver
     **/
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'use_ajax' => true,
            'script' => null,
            'method' => 'GET',
            'event' => 'onkeydown',
            'function' => null,
        ));

        $resolver->setOptional(array('target', 'event', 'function'));
        $resolver->setRequired(array('action'));

        $resolver->setAllowedValues(array(
            'method' => array('post', 'get', 'POST', 'GET'),
            'event' => array('onchange', 'onkeydown'),
        ));
    }

    /**
     * {@inheritDoc}
     **/
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['use_ajax']) {
            /**
             * Perform jQuery Ajax in Form
             **/
            if (isset($options['target'])) {
                if ('id' === $options['target']['type']) {
                    $options['target']['selector'] = sprintf('#%s', $options['target']['selector']);
                } elseif ('class' === $options['target']['type']) {
                    $options['target']['selector'] = sprintf('.%s', $options['target']['selector']);
                }
            }

            if ($options['script']) {
                $view->vars['script'] = sprintf('<script type="text/javascript">%s</script>', $options['script']);
            } else {
                $options['function'] = $options['function'] ?: sprintf('fn_%s_%s', uniqid(), $form->getName());

                if ('onchange' === $options['event']) {
                    $view->vars['attr']['onchange'] = sprintf('%s(this); return false;', $options['function']);
                } else {
                    $view->vars['attr']['onkeydown'] = sprintf('if (13 === event.keyCode) { event.preventDefault(); %s(this); return false; }', $options['function']);
                }

                $view->vars['script'] =
<<<EOD
<script type="text/javascript">
function %function%(field) {
    jQuery.ajax({
        type: '%method%',
        url: Routing.generate(%url%),
        data: {value: field.value},
        success: function(data) {
            %target%
        }
    });
}
</script>
EOD;
                $success = '';
                if (isset($options['target'])) {
                    if (isset($options['target']['handler'])) {
                        $success = strtr($options['target']['handler'], array('%target-selector%' => $options['target']['selector']));
                    } else {
                        $success = sprintf('jQuery("%s").val(data)', $options['target']['selector']);
                    }
                }

                if ('GET' === strtoupper($options['method'])) {
                    $options['action'] = sprintf('\'%s\', {id: field.value}', $options['action']);
                } else {
                    $options['action'] = sprintf('\'%s\'', $options['action']);
                }

                $view->vars['script'] = strtr($view->vars['script'], array(
                    '%function%' => $options['function'],
                    '%method%' => $options['method'],
                    '%url%' => $options['action'],
                    '%target%' => $success,
                ));
            }
        }
    }
} 