<?php
/**
 * @package construct
 *
 */
namespace Construct\Internal\Content;


class SiteController extends \Construct\Controller
{


    public function widgetPost()
    {
        $widgetId = ipRequest()->getPost('widgetId');

        if (!$widgetId) {
            return \Construct\Response\JsonRpc::error('Missing widgetId POST variable');
        }
        $widgetId = $_POST['widgetId'];

        $widgetRecord = Model::getWidgetRecord($widgetId);

        try {
            if (!$widgetRecord) {
                return \Construct\Response\JsonRpc::error(
                    "Can't find requested Widget: " . $widgetId,
                    10
                );
            }

            $widgetObject = Model::getWidgetObject($widgetRecord['name']);
            if (!$widgetObject) {
                return \Construct\Response\JsonRpc::error(
                    "Can't find requested Widget: " . $widgetRecord['name'],
                    20
                );
            }

            return $widgetObject->post($widgetId, $widgetRecord['data']);
        } catch (\Construct\Exception\Content $e) {
            return \Construct\Response\JsonRpc::error($e->getMessage());
        }
    }

}
