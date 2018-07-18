<?php
/**
 * @package construct
 *
 */
namespace Construct\Internal\Content\Widget\Html;


class Controller extends \Construct\WidgetController
{
    public function getTitle()
    {
        return __('HTML', 'Construct-admin', false);
    }

    public function adminHtmlSnippet()
    {
        return ipView('snippet/edit.php')->render();
    }

}
