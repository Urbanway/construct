<?php
/**
 * @package construct
 *
 *
 */
namespace Construct\Internal\Email;

class Db
{

    public static function getEmail($id)
    {
        return constructQuery()->selectRow('email_queue', '*', array('id' => $id));
    }

    public static function addEmail(
        $from,
        $fromName,
        $to,
        $toName,
        $subject,
        $email,
        $immediate,
        $html,
        $filesStr,
        $fileNamesStr,
        $mimeTypesStr
    ) {
        return constructQuery()->insert(
            'email_queue',
            array(
                'from' => $from,
                'fromName' => $fromName,
                'to' => $to,
                'toName' => $toName,
                'subject' => $subject,
                'email' => $email,
                'immediate' => $immediate ? 1 : 0,
                'html' => $html,
                'files' => $filesStr,
                'fileNames' => $fileNamesStr,
                'fileMimeTypes' => $mimeTypesStr,
            )
        );
    }

    public static function lock($count, $key)
    {
        $table = ipTable('email_queue');

        $sql = "update $table set
		`lock` = ?, `lockedAt` = CURRENT_TIMESTAMP
		where `lock` is NULL and send is NULL order by
		immediate desc, id asc limit " . $count;

        return constructQuery()->execute($sql, array($key));
    }

    public static function lockOnlyImmediate($count, $key)
    {
        $table = ipTable('email_queue');

        $sql = "update $table set
		`lock` = ?, `lockedAt` = CURRENT_TIMESTAMP
		where `immediate` and `lock` is NULL and `send` is NULL order by
		`id` asc limit " . $count;

        return constructQuery()->execute($sql, array($key));
    }

    public static function unlock($key)
    {
        return constructQuery()->update(
            'email_queue',
            array(
                'send' => date('Y-m-d H:i:s'),
                'lock' => null,
                'lockedAt' => null,
            ),
            array(
                'lock' => $key
            )
        );
    }

    public static function unlockOne($id)
    {
        return constructQuery()->update(
            'email_queue',
            array(
                'send' => date('Y-m-d H:i:s'),
                'lock' => null,
                'lockedAt' => null,
            ),
            array(
                'id' => $id,
            )
        );
    }


    public static function getLocked($key)
    {
        return constructQuery()->selectAll('email_queue', '*', array('lock' => $key));
    }

    public static function markSend($key)
    {
        return constructQuery()->update(
            'email_queue',
            array(
                'send' => date('Y-m-d H:i:s'),
            ),
            array(
                'lock' => $key
            )
        );
    }

    public static function delteOldSent($hours)
    {
        $table = ipTable('email_queue');
        $sql = "delete from $table where `send` is not NULL and " . constructQuery()->sqlMinAge('send', $hours, 'HOUR');
        return constructQuery()->execute($sql);
    }

    /*apparently there were some errors if exists old locked records. */
    public static function deleteOld($hours)
    {
        $table = ipTable('email_queue');
        $sql = "delete from $table where
        (`lock` is not NULL and " . constructQuery()->sqlMinAge('lockedAt', $hours, 'HOUR') .")
        or (`send` is not NULL and " . constructQuery()->sqlMinAge('send', $hours, 'HOUR') . ")";

        return constructQuery()->execute($sql);
    }

    public static function sentOrLockedCount($minutes)
    {
        $table = ipTable('email_queue');
        $sql = "select count(*) as `sent` from $table where
        (`send` is not NULL and " . constructQuery()->sqlMaxAge('send', $minutes, 'MINUTE') .")
        or (`lock` is not NULL and send is null) ";

        return constructQuery()->fetchValue($sql);
    }

}



