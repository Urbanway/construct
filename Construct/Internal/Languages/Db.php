<?php
/**
 * @package construct
 *
 *
 */
namespace Construct\Internal\Languages;

class Db
{


    public static function getLanguageById($id)
    {
        return constructQuery()->selectRow('language', '*', array('id' => $id));
    }

    public static function newUrl($preferredUrl)
    {
        $suffix = '';
        $url = constructQuery()->selectAll('language', 'id', array('url' => $preferredUrl . $suffix));
        if (empty($url)) {
            return $preferredUrl;
        }

        while (!empty($url)) {
            $suffix++;
            $url = constructQuery()->selectAll('language', 'id', array('url' => $preferredUrl . $suffix));
        }

        return $preferredUrl . $suffix;
    }


}

