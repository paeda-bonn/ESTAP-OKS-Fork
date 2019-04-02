<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP;

use PhoolKit\I18N;
use ESTAP\Exceptions\NoSuchUserException;
use ESTAP\Utils\DB;
use PDO;

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
     */
    private $create;

    protected function __construct($id, $login, $firstName, $lastName, $gender, $room, $active,$create)
    {
        parent::__construct($id, $login, $firstName, $lastName);
        $this->gender = $gender;
        $this->room = $room;
        $this->active = $active;
        $this->create = $create;
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

    public function isNew(){
        if($this->create){
            return true;
        }else{
            return false;
        }
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
    public function update($login, $firstName, $lastName, $gender, $room) 
    {
        $sql = "UPDATE teachers SET";
        $params = array(
            "login" => $login,
            "first_name" => $firstName,
            "last_name" => $lastName,
            "gender" => $gender,
            "room" => $room
        );
        $sql = "UPDATE teachers SET first_name=:first_name, last_name=:last_name, gender=:gender, room=:room WHERE login=:login";
        DB::exec($sql, $params);
        return true;
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
            $sql = "SELECT id, login, first_name, last_name, gender, room, active "
                . "FROM teachers ORDER BY login ASC"; 
            foreach (DB::query($sql) as $row)
            {
                $teacher = new Teacher(+$row["id"], $row["login"],
                    $row["first_name"], $row["last_name"], $row["gender"],
                    $row["room"], $row["active"],false);
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
            $sql = "SELECT login, first_name, last_name, gender, room, active "
                . "FROM teachers WHERE id=:id";
            $data = DB::querySingle($sql, array("id" => $id));
            if (!$data) throw new NoSuchUserException();
            $teacher = new Teacher($id, $data["login"], $data["first_name"], 
                $data["last_name"], $data["gender"], $data["room"], $data["active"],false);
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

    private static function ldapLogin($login,$password){
        $api_url = "http://localhost/Vertretungsplan/api/";
        $secret = "witt";
        $json = file_get_contents($api_url."/sessions.php?secret=$secret&create&username=$login&password=$password");
        $data = json_decode($json,true);
        if($data["access"] == true && $data["type"] == "teacher"){
            return true;
        }else{
            return false;
        }
    }


    private static function loadLdapData($login,$password){
        $api_url = "http://localhost/Vertretungsplan/api/";
        $secret = "witt";
        $json = file_get_contents($api_url."/sessions.php?secret=$secret&create&username=$login&password=$password");
        $data = json_decode($json,true);
        return $data;
    }

    
    public static function getByLogin($login, $password)
    {
        if(!self::ldapLogin($login, $password)){
            throw new NoSuchUserException();
        }
        $sql = "SELECT id, first_name, last_name, gender, room, active "
            . "FROM teachers WHERE login=:login";
        $data = DB::querySingle($sql, array("login" => $login));

        if(!$data){
            $data = self::loadLdapData($login,$password);
            $firstName = $data["user_info"]["givenname"];
            $lastName = $data["user_info"]["lastname"];
            $gender = "m";
            $room = "";
            $create = true;
            self::create($login, $firstName, $lastName, $gender, $room);
        }else{
            $create = false;
            $gender = $data["gender"];
            $room = $data["room"];
            $data = self::loadLdapData($login,$password);
            $firstName = $data["user_info"]["givenname"];
            $lastName = $data["user_info"]["lastname"];
            self::update($login, $firstName, $lastName, $gender, $room);
        }
        $sql = "SELECT id, first_name, last_name, gender, room, active "
            . "FROM teachers WHERE login=:login";
        $data = DB::querySingle($sql, array("login" => $login));
        $id = +$data["id"];
        $teacher = new Teacher($id, $login, $data["first_name"], 
            $data["last_name"], $data["gender"], $data["room"], $data["active"],$create);
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
    public static function create($login, $firstName, $lastName, $gender, $room)
    {
		$state = true;
        $sql = "INSERT INTO teachers " 
            . "(login, first_name, last_name, gender, room, active) "
            . "VALUES (:login, :first_name, :last_name, "
            . ":gender, :room, :active)";
        $id = DB::exec($sql, 
            array(
                "login" => $login,
                "first_name" => $firstName,
                "last_name" => $lastName,
                "gender" => $gender,
                "room" => $room,
				"active" => $state
            ), "teacher_id");       
        $teacher = new Teacher($id, $login, $firstName, $lastName, $gender, $room, $state,true);
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
