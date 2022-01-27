<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP\Forms;

use PhoolKit\RequireValidator;

/**
 * Form for adding a teacher.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
class AddTeacherForm extends TeacherForm
{
    /**
     * @see PhoolKit.Form::getValidators()
     */
    public function getValidators()
    {
        $validators = parent::getValidators();
        $validators[] = new RequireValidator("password");
        return $validators;
    }
}
