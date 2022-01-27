<?php
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP\Forms;

use PhoolKit\RequireValidator;

/**
 * Form for editing a admin.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
class EditAdminForm extends UserForm
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
