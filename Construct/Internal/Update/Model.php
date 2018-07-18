<?php
/**
 * @package   construct
 */


namespace Construct\Internal\Update;


class Model
{

    /**
     * DON'T USE THIS FUNCTION IN THE CORE. UPDATE SCRIPT WILL FAIL IF THIS CLASS WILL BE LOADED BEFORE NEW CORE IS DOWNLOADED.
     * @return int
     */
    public static function getDbVersion()
    {
        return 101; //CHANGE_ON_VERSION_UPDATE
    }

    public static function migrationsAvailable()
    {
        $curDbVersion = ipStorage()->get('Construct', 'dbVersion');
        return $curDbVersion < Model::getDbVersion();
    }

    public static function runMigrations()
    {
        $curDbVersion = ipStorage()->get('Construct', 'dbVersion');
        for ($i = $curDbVersion + 1; $i <= Model::getDbVersion(); $i++) {
            $migrationMethod = 'update_' . $i;
            if (method_exists(__NAMESPACE__ . '\Migration', $migrationMethod)) {
                Migration::$migrationMethod();
            }
            ipStorage()->set('Construct', 'dbVersion', $i);
        }
    }
}
