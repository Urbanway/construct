<?php
/**
 * @package construct
 *
 */
namespace Construct\Internal\Content\Widget\Map;


class Controller extends \Construct\WidgetController
{


    public function getTitle()
    {
        return __('Map', 'Construct-admin', false);
    }


    public function update($widgetId, $postData, $currentData)
    {
        return $postData;
    }


    public function generateHtml($revisionId, $widgetId, $data, $skin)
    {
        if (!$this->gmapsApiAvailable()) {
            if (ipIsManagementState()) {
                return '<div class="note"><a href="' . ipActionUrl(array('aa' => 'Config')) . '">' . __("Maps widget is based on Google Maps. Please set up Google Maps API key to make it work.", 'Construct-admin') . '</a></div>';
            } else {
                //don't show anything on public site
                return '';
            }
        }

        if (!empty($data['height'])) {
            $data['height'] = ((int)$data['height']) . 'px';
        } else {
            $data['height'] = '250px';
        }

        if (empty($data['mapTypeId'])) {
            $data['mapTypeId'] = null;
        }
        if (empty($data['zoom'])) {
            $data['zoom'] = null;
        }
        if (empty($data['lat'])) {
            $data['lat'] = null;
        }
        if (empty($data['lng'])) {
            $data['lng'] = null;
        }

        return parent::generateHtml($revisionId, $widgetId, $data, $skin);
    }

    public function adminHtmlSnippet()
    {
        return ipView('snippet/searchbox.php')->render();
    }

    private function gmapsApiAvailable()
    {
        return ipGetOption('Config.gmapsApiKey') || ipStorage()->get('Construct', 'upgradedFrom4.6.6', false);
    }



}
