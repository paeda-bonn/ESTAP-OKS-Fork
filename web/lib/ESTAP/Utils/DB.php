<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 */

namespace ESTAP\Utils;

use PDO;

/**
 * Singleton for creating and caching the PDO database connection handle.
 *
 * @author Klaus Reimer <k@ailis.de>
 */
final class DB
{
    /**
     * The database connection handle. NULL if not yet open.
     *
     * @var PDO
     */
    private static $handle;


    /**
     * Private constructor to prevent instantiation.
     */
    private function __construct()
    {
        // Nothing to do
    }

    /**
     * Opens a connection to the database (if not already done) and returns
     * the connection handle. There is no need in closing the handle. This
     * done automatically when the request ends or when web server process
     * dies (if persistent mode is enabled).
     *
     * @return PDO
     *            The connection handle. Never null.
     */
    public static function open()
    {
        if (!self::$handle) {
            self::$handle = new PDO(ESTAP_DB_DSN, ESTAP_DB_USER, ESTAP_DB_PASS,
                array(
                    PDO::ATTR_PERSISTENT, ESTAP_DB_PERSISTENT
                ));
            self::$handle->setAttribute(PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION);
        }
        return self::$handle;
    }

    /**
     * Utility method to easily send a query.
     *
     * @param string $sql
     *            The SQL statement.
     * @param array $params
     *            Hash map with parameters.
     * @return array
     *            Array of hash maps with result data. Never null.
     */
    public static function query($sql, $params = array())
    {
        $stmt = self::open()->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $data;
    }

    /**
     * Performs a query for a single result. Only the first row is returned.
     * If the result was empty then NULL is returned.
     *
     * @param string $sql
     *            The SQL statement.
     * @param array $params
     *            Hash map with parameters.
     * @return array
     *            Hash map with result data of first row or null if no row
     *            was found.
     */
    public static function querySingle($sql, $params = array())
    {
        $result = self::query($sql, $params);
        if (count($result) == 0) return NULL;
        return $result[0];
    }

    /**
     * Performs a query for a single integer value. If the result was empty
     * then NULL is returned.
     *
     * @param string $sql
     *            The SQL statement.
     * @param array $params
     *            Hash map with parameters.
     * @return integer
     *            The integer value or NULL if not found.
     */
    public static function queryInt($sql, $params = array())
    {
        $row = self::querySingle($sql, $params);
        if (is_null($row)) return false;
        $values = array_values($row);
        return intval($values[0], 10);
    }

    /**
     * Performs a query for a single boolean value. If the result was empty then
     * false is returned.
     *
     * @param string $sql
     *            The SQL statement.
     * @param array $params
     *            Hash map with parameters.
     * @return boolean
     *            The boolean value.
     */
    public static function queryBoolean($sql, $params = array())
    {
        $row = self::querySingle($sql, $params);
        if (is_null($row)) return false;
        $values = array_values($row);
        return !!$values[0];
    }

    /**
     * Executes an SQL command. For an INSERT statement you should specify
     * a sequence name if you want this method to return the ID for the new
     * row. Otherwise this method returns the number of affected rows.
     *
     * @param string $sql
     *            The SQL statement.
     * @param array $params
     *            Hash map with parameters.
     * @param string $seqName
     *            The sequence name to return the last ID from.
     */
    public static function exec($sql, $params = array(), $seqName = NULL)
    {
        $db = self::open();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $stmt->closeCursor();
        if ($seqName)
            return $db->lastInsertId($seqName);
        else
            return $stmt->rowCount();
    }

    /**
     * Starts a new transaction.
     */
    public static function beginTransaction()
    {
        self::open()->beginTransaction();
    }

    /**
     * Commits the current transaction.
     */
    public static function commit()
    {
        self::open()->commit();
    }

    /**
     * Performs a rollback of the current transaction.
     */
    public static function rollBack()
    {
        self::open()->rollBack();
    }
}
