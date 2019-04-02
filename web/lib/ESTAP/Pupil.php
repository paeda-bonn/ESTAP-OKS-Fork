<?php 
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP;

use ESTAP\Exceptions\NoSuchUserException;
use ESTAP\Utils\DB;
use PDO;

/**
 * Represents a single pupil of the school. Also provides static methods
 * to create new pupils and fetch existing pupils from the database.
 * 
 * @author Klaus Reimer <k@ailis.de>
 */
final class Pupil extends User 
{
    /**
     * Index from ID to cached pupil.
     * 
     * @var object
     */
    private static $pupilIndex = array();
    
    /**
     * Cached complete list of all pupils.
     * 
     * @var array
     */
    private static $pupils;
    
    /**
     * The class.
     * 
     * @var string
     */
    private $class;
        
    /**
     * Creates a new pupil.
     * 
     * @param string $id
     *           The unique pupil ID.
     * @param string $login
     *           The login name.
     * @param string $firstName
     *           The first name.
     * @param string $lastName
     *           The last name.
     * @param string $class
     *           The class.
     */
    protected function __construct($id, $login, $firstName, $lastName, $class)
    {
        parent::__construct($id, $login, $firstName, $lastName, $class);
        $this->class = $class;
    }
    
    /**
     * Deletes this pupil from the database. The pupil object is no longer
     * valid after this call so don't use it anymore.
     */
    public function delete()
    {
        self::deleteById($this->getId());
    }
    
    /**
     * Deletes a single pupil.
     * 
     * @param integer $pupilId
     *            The ID of the pupil to delete.
     */
    public static function deleteById($id)
    {
        $sql = "DELETE FROM pupils WHERE id=:id";
        DB::exec($sql, array("id" => $id));
    }
    
    /**
     * Returns the class.
     * 
     * @return string
     *            The class.
     */
    public function getClass()
    {
        return $this->class;
    }
    
    /**
     * Returns the string representation of this pupil. This is the full name
     * of the pupil (With last name first) and the class in brackets.
     * 
     * @return string
     *             The string representation.
     */
    public function __toString()
    {
        return $this->getName(Pupil::LAST_FIRST) . " (" . $this->getClass() . ")";
    }
    
    /**
     * Updates the pupil with new data.
     * 
     * @param string $login
     *            The new login name.
     * @param string $password
     *            The new password. Null to keep old one.
     * @param string $firstName
     *            The new first name.
     * @param string $lastName
     *            The new last name.
     * @param string $class
     *            The new class.
     * @return Pupil
     *            The updated pupil.
     */
    public function update($login,$firstName, $lastName, $class) 
    {
        $sql = "UPDATE pupils SET";
        $params = array(
            "login" => $login,
            "first_name" => $firstName,
            "last_name" => $lastName,
            "class" => $class
        );
        $sql = "UPDATE pupils SET first_name=:first_name, "
            . "last_name=:last_name,class=:class";
        $sql .= " WHERE login=:login";
        DB::exec($sql, $params);
        return true;
    }

    /**
     * Returns the number of pupils from the database.
     * 
     * @return integer
     *            The number of pupils in the database.
     */
    public static function count($search = null)
    {
        $sql = "SELECT COUNT(*) FROM pupils";
        $params = array();
        if (!is_null($search) && !empty($search))
        {
            $sql .= " WHERE (first_name LIKE :search OR " . 
                "last_name LIKE :search OR login LIKE :search OR " .
                "class LIKE :search)";
            $params["search"] = "%$search%";
        }
        return DB::queryInt($sql, $params);
    }
    
    /**
     * Returns all pupils from the database. This list is cached so it not
     * generated again in the same request.
     * 
     * @return array
     *            The array with all pupils.
     */
    public static function getAll($search = null, $rowCount = null, $page = 0)
    {
        if (!self::$pupils)
        {
            self::$pupils = array(); 
            $sql = "SELECT id, login, first_name, last_name, class FROM " .
                "pupils ORDER BY last_name ASC";
            foreach (DB::query($sql) as $row)
            {
                $pupil = new Pupil(+$row["id"], $row["login"], 
                    $row["first_name"], $row["last_name"], $row["class"]);
                self::$pupils[] = $pupil;
                self::$pupilIndex[$pupil->getId()] = $pupil;
            }
        }
        return self::$pupils;
    }

    /**
     * Searches for pupils.
     * 
     * @param string $search
     *            The search string. Null or empty if searching for all.
     * @param integer $rowCount
     *            The maximum number of pupils to return.
     * @param integer $page
     *            The page of pupils to return.
     * @return array
     *            The array with all found pupils.
     */
    public static function search($search = null, $rowCount = null, $page = 0)
    {
        $pupils = array();
        $sql = "SELECT id, login, first_name, last_name, class FROM " .
            "pupils";
        if (!is_null($search) && !empty($search))
        {
            $sql .= " WHERE (first_name LIKE :search OR " . 
                "last_name LIKE :search OR login LIKE :search OR " .
                "class LIKE :search)";
        }
        $sql .= " ORDER BY last_name ASC";
        if (!is_null($rowCount))
        {
            $sql .= sprintf(" LIMIT %d,%d", $page * $rowCount, $rowCount);
        }
        $params = array();
        if (!is_null($search) && !empty($search)) 
            $params["search"] = "%$search%";
        foreach (DB::query($sql, $params) as $row)
        {
            $pupil = new Pupil(+$row["id"], $row["login"], 
                $row["first_name"], $row["last_name"], $row["class"]);
            $pupils[] = $pupil;
            self::$pupilIndex[$pupil->getId()] = $pupil;
        }
        return $pupils;        
    }
    
    /**
     * Returns the pupil with the specified ID
     * 
     * @param int $id
     *            The pupil ID.
     * @return Pupil
     *            The pupil.
     * @throws NoSuchUserException
     *            When there is no pupil with the specified ID. 
     */
    public static function getById($id)
    {
        if (array_key_exists($id, self::$pupilIndex))
        {
            $pupil = self::$pupilIndex[$id];
        }
        else
        {
            $sql = "SELECT login, first_name, last_name, class FROM pupils "
                . "WHERE id=:id";
            $data = DB::querySingle($sql, array("id" => $id));
            if (!$data) throw new NoSuchUserException();
            $pupil = new Pupil($id, $data["login"], $data["first_name"], 
                $data["last_name"], $data["class"]);
            self::$pupilIndex[$id] = $pupil;
        }
        return $pupil;
    }
    
    /**
     * Returns the pupil with the specified login and password.
     * 
     * @param string $login
     *            The login name.
     * @param string $password
     *            The password.
     * @return Pupil
     *            The pupil.
     * @throws NoSuchUserException
     *            When there is no pupil with the specified login or the
     *            password is wrong. 
     */


    private static function ldapLogin($login,$password){
        $api_url = "http://localhost/Vertretungsplan/api/";
        $secret = "witt";
        $json = file_get_contents($api_url."/sessions.php?secret=$secret&create&username=$login&password=$password");
        $data = json_decode($json,true);
        if($data["access"] == true && $data["type"] == "student"){
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
        $sql = "SELECT id, first_name, last_name, class "
            . "FROM pupils WHERE login=:login";
        $data = DB::querySingle($sql, array("login" => $login));

        if(!$data){
            $data = self::loadLdapData($login,$password);
            $firstName = $data["user_info"]["givenname"];
            $lastName = $data["user_info"]["lastname"];
            $class = $data["user_info"]["class"];
            self::create($login, $firstName, $lastName, $class);
        }else{
            $data = self::loadLdapData($login,$password);
            $firstName = $data["user_info"]["givenname"];
            $lastName = $data["user_info"]["lastname"];
            $class = $data["user_info"]["class"];
            self::update($login,$firstName, $lastName, $class);
        }
        $sql = "SELECT id, first_name, last_name, class "
        . "FROM pupils WHERE login=:login";
        $data = DB::querySingle($sql, array("login" => $login));
        $id = +$data["id"];
        $pupil = new Pupil($id, $login, $data["first_name"], $data["last_name"], $data["class"]);
        self::$pupilIndex[$id] = $pupil;
        return $pupil;
    }
    
    /**
     * Creates a new pupil in the database and returns it.
     * 
     * @param string $login
     *            The login name of the pupil.
     * @param string $password
     *            The password of the pupil.
     * @param string $firstName
     *            The first name of the pupil.
     * @param string $lastName
     *            The first name of the pupil.
     * @param string $class
     *            The class of the pupil.
     * @return Pupil
     *            The created pupil.
     */
    public static function create($login,$firstName, $lastName,
        $class)
    {
        $sql = "INSERT INTO pupils (login, first_name, last_name, class) VALUES (:login, :first_name, :last_name, :class)";
        $id = DB::exec($sql, 
            array(
                "login" => $login,
                "first_name" => $firstName,
                "last_name" => $lastName,
                "class" => $class
            ), "pupil_id");       
        $pupil = new Pupil($id, $login, $firstName, $lastName, $class);
        self::$pupilIndex[$id] = $pupil;
        return $pupil;
    }
    
    /**
     * Deletes all pupils.
     */
    public static function deleteAll()
    {
        $sql = "DELETE FROM pupils";
        DB::exec($sql);
    }
}
