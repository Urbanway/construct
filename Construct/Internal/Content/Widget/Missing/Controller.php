<?php
/**
 * @package construct
 *
 */
namespace Construct\Internal\Content\Widget\Missing;


class Controller extends \Construct\WidgetController
{


    public function getTitle()
    {
        return __('Missing', 'Construct-admin', false);
    }


    public function generateHtml($revisionId, $widgetId, $data, $skin)
    {

        if (ipIsManagementState()) {
            return parent::generateHtml($revisionId, $widgetId, $data, $skin);
        } else {
            return '';
        }
    }
}
