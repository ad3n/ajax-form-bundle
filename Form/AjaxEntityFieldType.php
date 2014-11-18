<?php
/**
 * This file is part of JKN
 *
 * (c) Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 *
 * @author : Muhamad Surya Iksanudin
 **/
namespace Ihsan\AjaxFormBundle\Form;

class AjaxEntityFieldType extends AjaxChoiceFieldType
{
    public function getParent()
    {
        return 'entity';
    }

    public function getName()
    {
        return 'xentity';
    }
} 