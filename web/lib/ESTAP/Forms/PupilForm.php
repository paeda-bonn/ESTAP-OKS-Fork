<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP\Forms;

use ESTAP\User;
use PhoolKit\RequireValidator;

/**
 * Form for pupil data.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
class PupilForm extends UserForm
{
    /**
     * The class.
     *
     * @var string
     */
    public $class;

    /**
     * @see PhoolKit.Form::getValidators()
     */
    public function getValidators()
    {
        $validators = parent::getValidators();
        $validators[] = new RequireValidator("class");
        return $validators;
    }

    /**
     * @see PhoolKit.Form::init()
     */
    public function init(User $pupil = null)
    {
        parent::init($pupil);

        if ($pupil) {
            $this->class = $pupil->getClass();
        } else {
            $this->class = "";
        }
    }
}
