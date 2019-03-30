<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP\Forms;

use ESTAP\Pupil;
use ESTAP\User;
use PhoolKit\Form;
use PhoolKit\RequireValidator;
use PhoolKit\MinLengthIfSetValidator;
use PhoolKit\PasswordConfirmValidator;

/**
 * Form for editing a teacher.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
class EditTeacherForm extends TeacherForm
{
    /**
     * @see PhoolKit.Form::getValidators()
     */
    public function getValidators()
    {
        $validators = parent::getValidators();
        $validators[] = new RequireValidator("id");
        return $validators;
    }      
}
