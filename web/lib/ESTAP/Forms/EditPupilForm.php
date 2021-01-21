<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP\Forms;

use PhoolKit\RequireValidator;

/**
 * Form for editing a pupil.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
class EditPupilForm extends PupilForm
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
