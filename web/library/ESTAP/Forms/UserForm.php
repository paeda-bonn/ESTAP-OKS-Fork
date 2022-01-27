<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP\Forms;

use ESTAP\Config;
use ESTAP\User;
use PhoolKit\Form;
use PhoolKit\MinLengthIfSetValidator;
use PhoolKit\PasswordConfirmValidator;
use PhoolKit\RequireValidator;

/**
 * User data form.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
class UserForm extends Form
{
    /**
     * The user ID.
     *
     * @var integer
     */
    public $id;

    /**
     * The login name.
     *
     * @var string
     */
    public $login;

    /**
     * The password.
     *
     * @var string
     */
    public $password;

    /**
     * The password confirmation.
     *
     * @var string
     */
    public $passwordConfirmation;

    /**
     * The first name.
     *
     * @var string
     */
    public $firstName;

    /**
     * The last name.
     *
     * @var string
     */
    public $lastName;

    /**
     * @see PhoolKit.Form::getValidators()
     */
    public function getValidators()
    {
        return array(
            new RequireValidator("login", "firstName", "lastName"),
            new MinLengthIfSetValidator(Config::get()->getMinPasswordLength(), "password"),
            new PasswordConfirmValidator("password", "passwordConfirmation")
        );
    }

    /**
     * @see PhoolKit.Form::init()
     */
    public function init(User $user = null)
    {
        if ($user) {
            $this->id = $user->getId();
            $this->login = $user->getLogin();
            $this->firstName = $user->getFirstName();
            $this->lastName = $user->getLastName();
        } else {
            $this->id = null;
            $this->login = "";
            $this->firstName = "";
            $this->lastName = "";
        }
        $this->password = "";
        $this->passwordConfirmation = "";
    }
}
