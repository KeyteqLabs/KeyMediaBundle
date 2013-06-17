<?php
/**
 * File containing the abstract Gateway class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU General Public License v2.0
 * @version
 */

namespace KTQ\Bundle\KeyMediaBundle\FieldType\KeyMedia\KeyMediaStorage\Gateway;

use eZ\Publish\Core\FieldType\Url\UrlStorage\Gateway;
use eZ\Publish\SPI\Persistence\Content\VersionInfo;
use eZ\Publish\SPI\Persistence\Content\Field;

/**
 *
 */
class LegacyStorage extends Gateway
{
    /**
     * Connection
     *
     * @var mixed
     */
    protected $dbHandler;

    /**
     * Set database handler for this gateway
     *
     * @param mixed $dbHandler
     *
     * @return void
     * @throws \RuntimeException if $dbHandler is not an instance of
     *         {@link \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler}
     */
    public function setConnection($dbHandler)
    {
        // This obviously violates the Liskov substitution Principle, but with
        // the given class design there is no sane other option. Actually the
        // dbHandler *should* be passed to the constructor, and there should
        // not be the need to post-inject it.
        if (!$dbHandler instanceof \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler)
            throw new \RuntimeException( "Invalid dbHandler passed" );

        $this->dbHandler = $dbHandler;
    }

    /**
     * Returns the active connection
     *
     * @throws \RuntimeException if no connection has been set, yet.
     *
     * @return \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler
     */
    protected function getConnection()
    {
        if ($this->dbHandler === null)
            throw new \RuntimeException( "Missing database connection." );

        return $this->dbHandler;
    }

    /**
     * Fetches a row in ezurl table referenced by its $id
     *
     * @param mixed $id
     *
     * @return null|array Hash with columns as keys or null if no entry can be found
     */
    /*
    private function fetchById($id)
    {
        $dbHandler = $this->getConnection();

        $q = $dbHandler->createSelectQuery();
        $e = $q->expr;
        $q->select( "*" )
            ->from( $dbHandler->quoteTable( self::URL_TABLE ) )
            ->where(
                $e->eq( "id", $q->bindValue( $id, null, \PDO::PARAM_INT ) )
            );

        $statement = $q->prepare();
        $statement->execute();

        $rows = $statement->fetchAll( \PDO::FETCH_ASSOC );
        if ( count( $rows ) )
        {
            return $rows[0];
        }

        return false;
    }
    */
}
