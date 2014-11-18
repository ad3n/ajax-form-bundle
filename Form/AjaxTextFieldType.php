<?php
/**
 * This file is part of JKN
 *
 * (c) Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 *
 * @author : Muhamad Surya Iksanudin
 **/
namespace Ihsan\AjaxFormBundle\Form;

class AjaxTextFieldType
{
    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'xtext';
    }
} 