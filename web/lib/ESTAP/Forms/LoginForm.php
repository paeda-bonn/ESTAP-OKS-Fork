<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP\Forms;

use PhoolKit\Form;
use PhoolKit\RequireValidator;

/**
 * The login form.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
class LoginForm extends Form
{
    /** 
     * The login name.
     * 
     * @var string
     */
    public $login = "";

    /** 
     * The password.
     * 
     * @var string
     */
    public $password = "";
    
    /**
     * If another login should be performed after successful login.
     * 
     * @var boolean
     */
    public $another = false;
    
    /**
     * @see PhoolKit.Form::getValidators()
     */
    public function getValidators()
    {
        return array(
            new RequireValidator("login", "password")
        );
    }
}
