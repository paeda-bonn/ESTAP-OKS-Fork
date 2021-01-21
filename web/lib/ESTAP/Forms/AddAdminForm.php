<?php
/*
 * Copyright 2014 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP\Forms;

use PhoolKit\RequireValidator;

/**
 * Form for adding an admin.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
class AddAdminForm extends UserForm
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
