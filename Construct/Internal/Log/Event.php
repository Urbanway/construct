<?php
/**
 * @package construct
 *
 *
 */
namespace Construct\Internal\Log;

class Event
{
    public static function ipCronExecute($info)
    {
        if ($info['firstTimeThisMonth'] || $info['test']) {
            Db::deleteOldLogs(ipGetOption('Log.existenceInDays', '90'));
        }
    }
}
