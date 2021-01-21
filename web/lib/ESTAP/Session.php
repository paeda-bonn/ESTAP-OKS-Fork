<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP;

use ESTAP\Exceptions\NoSuchUserException;
use LogicException;
use PhoolKit\I18N;
use PhoolKit\Request;
use RuntimeException;

/**
 * The user session.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
class Session
{
    /**
     * The currently logged in admin or NULL if not an admin or not yet
     * logged in.
     *
     * @var Admin
     */
    private $admin;

    /**
     * The currently logged in teacher or NULL if not a teacher or not yet
     * logged in.
     *
     * @var Teacher
     */
    private $teacher;

    /**
     * The currently logged in pupils. Empty if no pupils logged in.
     *
     * @var array
     */
    private $pupils = array();

    /**
     * Return the current session.
     *
     * @return Session
     *            The current session.
     */
    public static function get()
    {
        // Return session if it already exists in the HTTP session.
        if (isset($_SESSION["session"]))
            $session = $_SESSION["session"];

        // Create new session and store it in the HTTP session
        else {
            $session = new Session();
            $_SESSION["session"] = $session;
        }
        return $session;
    }

    /**
     * Returns the logged in teacher.
     *
     * @return User
     *             The logged in teacher.
     * @throws LogicException
     *             When no pupils logged in.
     */
    public function getTeacher()
    {
        if (!$this->teacher)
            throw new LogicException("No teacher logged in");
        return $this->teacher;
    }

    /**
     * Returns the logged in admin.
     *
     * @return User
     *             The logged in admin.
     * @throws LogicException
     *             When no admin logged in.
     */
    public function getAdmin()
    {
        if (!$this->admin)
            throw new LogicException("No admin logged in");
        return $this->admin;
    }

    /**
     * Returns the logged in pupils.
     *
     * @return array
     *             The logged in pupils.
     * @throws LogicException
     *             When no pupils logged in.
     */
    public function getPupils()
    {
        if (!$this->pupils)
            throw new LogicException("No pupils logged in");
        return $this->pupils;
    }

    /**
     * Returns the IDs of the logged in pupils
     *
     * @return array
     *             The IDs of logged in pupils.
     * @throws LogicException
     *             When no pupils logged in.
     */
    public function getPupilIds()
    {
        $ids = array();
        $pupils = $this->getPupils();
        foreach ($pupils as $pupil) $ids[] = $pupil->getId();
        return $ids;
    }

    /**
     * Returns the pupil with the given ID.
     *
     * @param int $id
     *            The pupil ID.
     * @return Pupil
     *            The Pupil
     * @throws LogicException
     *            When pupil is not logged in.
     */
    public function getPupil($id)
    {
        foreach ($this->pupils as $pupil) {
            if ($pupil->getId() == $id) return $pupil;
        }
        throw new NoSuchUserException();
    }

    /**
     * Perform a parent login.
     *
     * @param string $login
     *            The user name.
     * @param string $password
     *            The password
     * @return Pupil
     *            The pupil which was logged in.
     * @throws RuntimeException
     *            When login fails.
     */
    public function loginParent($login, $password)
    {
        try {
            return $this->pupils[] = Pupil::getByLogin($login, $password);
        } catch (NoSuchUserException $e) {
            throw new RuntimeException(I18N::getMessage("errors.login"));
        }
    }

    /**
     * Perform a teacher login.
     *
     * @param string $login
     *            The user name.
     * @param string $password
     *            The password
     * @return Session
     *            This session for method chaining.
     * @throws RuntimeException
     *            When login fails.
     */
    public function loginTeacher($login, $password)
    {
        try {
            $this->teacher = Teacher::getByLogin($login, $password);
        } catch (NoSuchUserException $e) {
            throw new RuntimeException(I18N::getMessage("errors.login"));
        }
        return $this;
    }

    /**
     * Perform a admin login.
     *
     * @param string $login
     *            The user name.
     * @param string $password
     *            The password
     * @return Session
     *            This session for method chaining.
     * @throws RuntimeException
     *            When login fails.
     */
    public function loginAdmin($login, $password)
    {
        try {
            $this->admin = Admin::getByLogin($login, $password);
        } catch (NoSuchUserException $e) {
            throw new RuntimeException(I18N::getMessage("errors.login"));
        }
        return $this;
    }

    /**
     * Perform a logout.
     *
     * @return Session
     *            This session for method chaining.
     */
    public function logout()
    {
        $this->admin = NULL;
        $this->teacher = NULL;
        $this->pupils = array();
        return $this;
    }

    /**
     * Checks if user is logged in.
     *
     * @return boolean
     *             True if user is logged in, false if not.
     */
    public function isLoggedIn()
    {
        return $this->isParent() || $this->isTeacher() || $this->isAdmin();
    }

    /**
     * Checks if current user is a teacher.
     *
     * @return boolean
     *            True if user is a teacher. False if not a teacher or not
     *            logged in.
     */
    public function isTeacher()
    {
        return !!$this->teacher;
    }

    /**
     * Checks if current user is an admin.
     *
     * @return boolean
     *            True if user is an admin. False if not an admin or not
     *            logged in.
     */
    public function isAdmin()
    {
        return !!$this->admin;
    }

    /**
     * Checks if current login is a parent.
     *
     * @return boolean
     *            True if user is a parent. False if not a parent or not
     *            logged in.
     */
    public function isParent()
    {
        return !!$this->pupils;
    }

    /**
     * Requires a teacher login. If this requirement is not satisfied
     * then redirect to the login page.
     *
     * @param int $teacherId
     *            Optional ID of a requried teacher.
     * @return Session
     *            This session for method chaining.
     */
    public function requireTeacher($teacherId = null)
    {
        if (!$this->isTeacher() || (!is_null($teacherId) && $teacherId !=
                $this->getTeacher()->getId())) {
            Request::redirect("loginTeacher.php");
        }
        return $this;
    }

    /**
     * Requires a teacher login. If this requirement is not satisfied
     * then redirect to the login page.
     *
     * @return Session
     *            This session for method chaining.
     */
    public function requireAdmin()
    {
        if (!$this->isAdmin()) Request::redirect("loginAdmin.php");
        return $this;
    }

    /**
     * Requires a parent login. If this requirement is not satisfied
     * then redirect to the login page.
     *
     * @param int $pupilId
     *            Optional ID of a requried pupil.
     * @return Session
     *            This session for method chaining.
     */
    public function requireParent($pupilId = null)
    {
        if (!$this->isParent()) Request::redirect("login.php");
        if (!is_null($pupilId)) {
            try {
                $this->getPupil($pupilId);
            } catch (NoSuchUserException $e) {
                Request::redirect("login.php");
            }
        }
        return $this;
    }
}
