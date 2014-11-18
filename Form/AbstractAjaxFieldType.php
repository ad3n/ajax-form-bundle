<?php
/**
 * This file is part of JKN
 *
 * (c) Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 *
 * @author : Muhamad Surya Iksanudin
 **/
namespace Ihsan\AjaxFormBundle\Form;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AbstractAjaxFieldType
{
    /**
     * @param OptionsResolverInterface $resolver
     **/
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'use_ajax' => true,
            'script' => null,
            'method' => 'POST',
            'action' => null,
            'function' => null,
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
                $options['function'] = $options['function'] ?: sprintf('fn_%s', uniqid());

                if (isset($options['choices']) && ! empty($options['choices'])) {
                    $view->vars['attr']['onchange'] = sprintf('%s(this); return false;', $options['function']);
                } else {
                    $view->vars['attr']['onkeydown'] = sprintf('if (13 === event.keyCode) { event.preventDefault(); %s(this); return false; }', $options['function']);
                }

                $view->vars['script'] =
<<<EOD
<script type="text/javascript">
function %function%() {
    jQuery.ajax({
        type: '%method%',
        url: '%url%',
        data: {value: field.value},
        success: function(data) {
            %target%
        }
    });
}
</script>
EOD;
                $success = 'field.value = data;';
                if (isset($options['target'])) {
                    $success = sprintf('jQuery("%s").val(data)');
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