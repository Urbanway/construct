<?php


namespace Construct\Internal\Core;


class Service
{
    public static function invalidateAssetCache()
    {
        ipStorage()->set('Construct', 'cacheVersion', ipStorage()->get('Construct', 'cacheVersion') + 1);
    }
}
