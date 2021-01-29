<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP;

use ESTAP\Exceptions\NoSuchUserException;
use ESTAP\Utils\DB;
use PhoolKit\I18N;

/**
 * Represents a single teacher of the school. Also provides static methods
 * to create new teachers and fetch existing teachers from the database.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
final class Teacher extends User
{
    /** Constant for name format "Gender Last". */
    const GENDER_LAST = 3;
    const GENDER_ACC_LAST = 4;

    /**
     * Index from ID to cached teacher.
     *
     * @var object
     */
    private static $teacherIndex = array();

    /**
     * Cached complete list of all teachers.
     *
     * @var array
     */
    private static $teachers;

    /**
     * The gender ('f' for female or 'm' for male)
     *
     * @var string
     */
    private $gender;

    /**
     * The room.
     *
     * @var string
     */
    private $room;

    /**
     * The active state.
     *
     * @var boolean
     */
    private $active;

    /**
     * The active state.
     *
     * @var string
     */
    private $vcLink;

    /**
     * Creates a new teacher.
     *
     * @param string $id
     *           The unique teacher ID.
     * @param string $login
     *           The login name.
     * @param string $firstName
     *           The first name.
     * @param string $lastName
     *           The last name.
     * @param string $gender
     *           The gender ('f' for female or 'm' for male).
     * @param string $room
     *           The room
     * @param $active //TODO add Doc
     * @param $vcLink
     */
    protected function __construct($id, $login, $firstName, $lastName, $gender, $room, $active, $vcLink)
    {
        parent::__construct($id, $login, $firstName, $lastName);
        $this->gender = $gender;
        $this->room = $room;
		$this->active = $active;
		$this->vcLink = $vcLink;
    }

    /**
     * Returns the gender. 'f' for female or 'm' for male.
     *
     * @return string
     *            The gender.
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Returns the room.
     *
     * @return string
     *            The room.
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Checks if teacher is active.
     *
     * @return boolean
     *            True if teacher is active, false it not.
     */
    public function isActive()
    {
        return $this->active;
    }

    //TODO add JDoc
    public function getVCLink()
    {
        return $this->vcLink;
    }

    /**
     * Deletes this teacher from the database. The teacher object is no longer
     * valid after this call so don't use it anymore.
     */
    public function delete()
    {
        self::deleteById($this->getId());
    }

    /**
     * Deletes the teacher with the specified ID.
     *
     * @param integer $id
     *            The teacher ID.
     */
    public static function deleteById($id)
    {
        $sql = "DELETE FROM teachers WHERE id=:id";
        DB::exec($sql, array("id" => $id));
    }

    /**
     * Activates or deactivates the teacher with the specified ID.
     *
     * @param integer $id
     *            The teacher ID.
     * @param boolean $state
     *            The state to set. True to activate, false to deactivate.
     */
    public static function setActiveById($id, $state)
    {
        $sql = "UPDATE teachers SET active=:state WHERE id=:id";
        DB::exec($sql, array("id" => $id, "state" => $state));
    }

    /**
     * Updates the teacher with new data.
     *
     * @param string $login
     *            The new login name.
     * @param string $password
     *            The new password. Null to keep old one.
     * @param string $firstName
     *            The new first name.
     * @param string $lastName
     *            The new last name.
     * @param string $gender
     *            The new gender. 'f' for female or 'm' for male.
     * @param string $room
     *            The new room.
     * @return Teacher
     *            The updated teacher.
     */
    public function update($login, $password, $firstName, $lastName, $gender, $room, $vcLink)
    {
        $sql = "UPDATE teachers SET";
        $this->login = $login;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->gender = $gender;
        $this->room = $room;
        $params = array(
            "id" => $this->getId(),
            "login" => $login,
            "first_name" => $firstName,
            "last_name" => $lastName,
            "gender" => $gender,
            "room" => $room,
            "vclink" => $vcLink
        );
        $sql = "UPDATE teachers SET login=:login, first_name=:first_name, last_name=:last_name, gender=:gender, room=:room, vclink=:vclink";
        if ($password)
        {
            $params["password"] = crypt($password, '$6$' . uniqid() . '$');
            $sql .= ", password=:password";
        }
        $sql .= " WHERE id=:id";
        DB::exec($sql, $params);
        return $this;
    }

    /**
     * Returns all teachers from the database. This list is cached so it not
     * generated again in the same request.
     *
     * @return array
     *            The array with all teachers.
     */
    public static function getAll()
    {
        if (!self::$teachers)
        {
            self::$teachers = array();
            $sql = "SELECT id, login, first_name, last_name, gender, room, active, vclink FROM teachers ORDER BY login ASC";
            foreach (DB::query($sql) as $row)
            {
                $teacher = new Teacher(+$row["id"], $row["login"], $row["first_name"], $row["last_name"], $row["gender"], $row["room"], $row["active"], $row["vclink"]);
                self::$teachers[] = $teacher;
                self::$teacherIndex[$teacher->getId()] = $teacher;
            }
        }
        return self::$teachers;
    }

    /**
     * Returns the teacher with the specified ID
     *
     * @param int $id
     *            The teacher ID.
     * @return Teacher
     *            The teacher.
     * @throws NoSuchUserException
     *            When there is no teacher with the specified ID.
     */
    public static function getById($id)
    {
        if (array_key_exists($id, self::$teacherIndex))
        {
            $teacher = self::$teacherIndex[$id];
        }
        else
        {
            $sql = "SELECT login, first_name, last_name, gender, room, active, vclink FROM teachers WHERE id=:id";
            $data = DB::querySingle($sql, array("id" => $id));
            if (!$data) throw new NoSuchUserException();
            $teacher = new Teacher($id, $data["login"], $data["first_name"], $data["last_name"], $data["gender"], $data["room"], $data["active"], $data["vclink"]);
            self::$teacherIndex[$id] = $teacher;
        }
        return $teacher;
    }

    /**
     * Returns the teacher with the specified login and password.
     *
     * @param string $login
     *            The login name.
     * @param string $password
     *            The password.
     * @return Teacher
     *            The teacher.
     * @throws NoSuchUserException
     *            When there is no teacher with the specified login or the
     *            password is wrong.
     */
    public static function getByLogin($login, $password)
    {
        $sql = "SELECT id, password, first_name, last_name, gender, room, active "
            . "FROM teachers WHERE login=:login";
        $data = DB::querySingle($sql, array("login" => $login));
        if (!$data) throw new NoSuchUserException();
        $correctHash = $data["password"];
        $hash = crypt($password, $correctHash);
        if ($hash != $correctHash) throw new NoSuchUserException();
        $id = +$data["id"];
        $teacher = new Teacher($id, $login, $data["first_name"], $data["last_name"], $data["gender"], $data["room"], $data["active"], $data["vclink"]);
        self::$teacherIndex[$id] = $teacher;
        return $teacher;
    }

    /**
     * Creates a new teacher in the database and returns it.
     *
     * @param string $login
     *            The login name of the teacher.
     * @param string $password
     *            The password of the teacher.
     * @param string $firstName
     *            The first name of the teacher.
     * @param string $lastName
     *            The first name of the teacher.
     * @param string $gender
     *            The gender. 'f' for female or 'm' for male.
     * @param string $roomt
     *            The room.
     * @return Teacher
     *            The created teacher.
     */
    public static function create($login, $password, $firstName, $lastName, $gender, $room, $vcLink)
    {
		$state = true;
		$hash = crypt($password, '$6$' . uniqid() . '$');
        $sql = "INSERT INTO teachers (login, password, first_name, last_name, gender, room, active, vclink) VALUES (:login, :password, :first_name, :last_name, :gender, :room, :active, :vclink)";
        $id = DB::exec($sql,
            array(
                "login" => $login,
                "password" => $hash,
                "first_name" => $firstName,
                "last_name" => $lastName,
                "gender" => $gender,
                "room" => $room,
				"active" => $state,
                "vclink" => $vcLink
            ), "teacher_id");
        $teacher = new Teacher($id, $login, $firstName, $lastName, $gender, $room, $state, $vcLink);
        self::$teacherIndex[$id] = $teacher;
        return $teacher;
    }

    /**
     * Deletes all teachers.
     */
    public static function deleteAll()
    {
        $sql = "DELETE FROM teachers";
        DB::exec($sql);
    }

     /**
     * Activates all teachers.
     */
    public static function activateAll()
    {
        $sql = "UPDATE teachers set active=1";
        DB::exec($sql);
    }

    /**
     * Returns the list of genders.
     *
     * @return array
     *            The possible genders.
     */
    public static function getGenders()
    {
        return array(
            "f" => I18N::getMessage("gender.f"),
            "m" => I18N::getMessage("gender.m")
        );
    }

    /**
     * Returns the full name of the teacher.
     *
     * @param int $format
     *            Optional name format. Defaults to User::FIRST_LAST. Can
     *            also be User::LAST_FIRST or Teacher::GENDER_LAST
     * @return string
     *            The full name of the user.
     */
    public function getName($mode = User::FIRST_LAST)
    {
        if ($mode == Teacher::GENDER_LAST)
        {
            $gender = I18N::getMessage("gender." . $this->getGender());
            return $gender . " " . $this->lastName;
        }
        elseif ($mode == Teacher::GENDER_ACC_LAST)
        {
            $gender = I18N::getMessage("gender.acc." . $this->getGender());
            return $gender . " " . $this->lastName;
        }
        else
        {
            return parent::getName($mode);
        }
    }
}
