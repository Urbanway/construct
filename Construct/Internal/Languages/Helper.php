<?php

/**
 * @package construct
 *
 *
 */
namespace Construct\Internal\Languages;


class Helper
{
    public static function getAddForm()
    {
        $form = new \Construct\Form();

        $field = new \Construct\Form\Field\Hidden(
            array(
                'name' => 'aa',
                'value' => 'Languages.addLanguage'
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Select(
            array(
                'name' => 'languageCode',
                'values' => self::getLanguageSelectValues()
            ));
        $form->addField($field);

        return $form;
    }


    private static function getLanguageSelectValues()
    {
        $answer = [];
        $languages = Fixture::languageList();
        foreach ($languages as $key => $language) {
            $answer[] = array(
                $key,
                $language['name'] . ' (' . $language['nativeName'] . ')'
            );
        }


        usort($answer, array(__CLASS__, 'cmp'));

        return $answer;
    }


    protected static function cmp($a, $b)
    {
        if ($a[0] == $b[0]) {
            return 0;
        }
        return ($a[0] < $b[0]) ? -1 : 1;
    }

}



