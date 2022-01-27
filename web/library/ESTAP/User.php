<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP;

/**
 * Base class for user-like objects (Teachers and pupils).
 *
 * @author Klaus Reimer <k@ailis.de>
 */
abstract class User
{
    /** Constant for name format "Last, First". */
    const LAST_FIRST = 0;

    /** Constant for name format "First Last". */
    const FIRST_LAST = 1;

    /**
     * The unique ID of the user.
     *
     * @var int
     */
    protected $id;

    /**
     * The login name of the user.
     *
     * @var string
     */
    protected $login;

    /**
     * The first name of the user.
     *
     * @var string
     */
    protected $firstName;

    /**
     * The last name of the user.
     *
     * @var string
     */
    protected $lastName;

    /**
     * Creates a new user.
     *
     * @param string $id
     *           The unique ID of the user.
     * @param string $login
     *           The login name of the user.
     * @param string $firstName
     *           The first name of the user.
     * @param string $lastName
     *           The last name of the user.
     */
    protected function __construct($id, $login, $firstName, $lastName)
    {
        $this->id = $id;
        $this->login = $login;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * Returns the unique user ID.
     *
     * @return string
     *            The unique user ID.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the login name.
     *
     * @return string
     *            The login name.
     */
    public final function getLogin()
    {
        return $this->login;
    }

    /**
     * Returns the first name.
     *
     * @return string
     *            The first name.
     */
    public final function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Returns the last name of the user.
     *
     * @return string
     *            The last name of the user.
     */
    public final function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Returns the full name of the user.
     *
     * @param int $format
     *            Optional name format. Defaults to User::FIRST_LAST. Can
     *            also be User::LAST_FIRST.
     * @return string
     *            The full name of the user.
     */
    public function getName($mode = User::FIRST_LAST)
    {
        if ($mode == User::LAST_FIRST) {
            return $this->lastName . ", " . $this->firstName;
        } else {
            return $this->firstName . " " . $this->lastName;
        }
    }
}
