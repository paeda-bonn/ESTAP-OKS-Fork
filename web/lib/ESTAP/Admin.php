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
 * Represents a single admin of the school. Also provides static methods
 * to create new admins and fetch existing admins from the database.
 * 
 * @author Klaus Reimer <k@ailis.de>
 */
final class Admin extends User
{
    /**
     * Index from ID to cached admin.
     * 
     * @var object
     */
    private static $adminIndex = array();
    
    /**
     * Cached complete list of all admins.
     * 
     * @var array
     */
    private static $admins;
    
    /**
     * Creates a new admin.
     * 
     * @param string $id
     *           The unique admin ID.
     * @param string $login
     *           The login name.
     * @param string $firstName
     *           The first name.
     * @param string $lastName
     *           The last name.
     * @param boolean $admin
     *           If admin is an admin. Defaults to false.
     */
    protected function __construct($id, $login, $firstName, $lastName)
    {
        parent::__construct($id, $login, $firstName, $lastName);
    }
    
    /**
     * Updates the admin with new data.
     * 
     * @param string $login
     *            The new login name.
     * @param string $password
     *            The new password. Null to keep old one.
     * @param string $firstName
     *            The new first name.
     * @param string $lastName
     *            The new last name.
     * @param boolean $admin
     *            True to give the admin admin privileges, false to revoke
     *            them.
     * @return Admin
     *            The updated admin.
     */
    public function update($login, $password, $firstName, $lastName) 
    {
        $sql = "UPDATE admins SET";
        $this->login = $login;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $params = array(
            "id" => $this->getId(),
            "login" => $login,
            "first_name" => $firstName,
            "last_name" => $lastName
        );
        $sql = "UPDATE admins SET login=:login, first_name=:first_name, "
            . "last_name=:last_name";
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
     * Returns all admins from the database. This list is cached so it not
     * generated again in the same request.
     * 
     * @return array
     *            The array with all admins.
     */
    public static function getAll()
    {
        if (!self::$admins)
        {
            self::$admins = array(); 
            $sql = "SELECT id, login, first_name, last_name FROM admins"; 
            foreach (DB::query($sql) as $row)
            {
                $admin = new Admin(+$row["id"], $row["login"], 
                    $row["first_name"], $row["last_name"]);
                self::$admins[] = $admin;
                self::$adminIndex[$admin->getId()] = $admin;
            }
        }
        return self::$admins;
    }
    
    /**
     * Returns the admin with the specified ID
     * 
     * @param int $id
     *            The admin ID.
     * @return Admin
     *            The admin.
     * @throws NoSuchUserException
     *            When there is no admin with the specified ID. 
     */
    public static function getById($id)
    {
        if (array_key_exists($id, self::$adminIndex))
        {
            $admin = self::$adminIndex[$id];
        }
        else
        {
            $sql = "SELECT login, first_name, last_name FROM admins "
                . "WHERE id=:id";
            $data = DB::querySingle($sql, array("id" => $id));
            if (!$data) throw new NoSuchUserException();
            $admin = new Admin($id, $data["login"], $data["first_name"], 
                $data["last_name"]);
            self::$adminIndex[$id] = $admin;
        }
        return $admin;
    }
    
    /**
     * Returns the admin with the specified login and password.
     * 
     * @param string $login
     *            The login name.
     * @param string $password
     *            The password.
     * @return Admin
     *            The admin.
     * @throws NoSuchUserException
     *            When there is no admin with the specified login or the
     *            password is wrong. 
     */
    public static function getByLogin($login, $password)
    {
        $sql = "SELECT id, password, first_name, last_name "
            . "FROM admins WHERE login=:login";
        $data = DB::querySingle($sql, array("login" => $login));
        if (!$data) throw new NoSuchUserException();
        $correctHash = $data["password"];
        $hash = crypt($password, $correctHash);
        if ($hash != $correctHash) throw new NoSuchUserException();
        $id = +$data["id"];
        $admin = new Admin($id, $login, $data["first_name"], 
            $data["last_name"]);
        self::$adminIndex[$id] = $admin;
        return $admin;
    }
    
    /**
     * Creates a new admin in the database and returns it.
     * 
     * @param string $login
     *            The login name of the admin.
     * @param string $password
     *            The password of the admin.
     * @param string $firstName
     *            The first name of the admin.
     * @param string $lastName
     *            The first name of the admin.
     * @return Admin
     *            The created admin.
     */
    public static function create($login, $password, $firstName, $lastName)
    {
        $hash = crypt($password, '$6$' . uniqid() . '$');
        $sql = "INSERT INTO admins " 
            . "(login, password, first_name, last_name) "
            . "VALUES (:login, :password, :first_name, :last_name)";
        $id = DB::exec($sql, 
            array(
                "login" => $login,
                "password" => $hash,
                "first_name" => $firstName,
                "last_name" => $lastName
            ), "admin_id");       
        $admin = new Admin($id, $login, $firstName, $lastName);
        self::$adminIndex[$id] = $admin;
        return $admin;
    }
    
    /**
     * Deletes this admin from the database. The admin object is no longer
     * valid after this call so don't use it anymore.
     */
    public function delete()
    {
        self::deleteById($this->getId());
    }
    
    /**
     * Deletes the admin with the specified ID.
     * 
     * @param integer $id
     *            The admin ID.
     */
    public static function deleteById($id)
    {
        $sql = "DELETE FROM admins WHERE id=:id";
        DB::exec($sql, array("id" => $id));
    }
}
