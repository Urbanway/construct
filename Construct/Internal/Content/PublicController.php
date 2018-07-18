<?php
/**
 * @package construct
 *
 */
namespace Construct\Internal\Content;


class PublicController extends \Construct\Controller
{
    public function index()
    {


        $revision = \Construct\ServiceLocator::content()->getCurrentRevision();
        if ($revision) {
            return \Construct\Internal\Content\Model::generateBlock('main', $revision['revisionId'], 0, ipIsManagementState());
        } else {
            return '';
        }

    }


}
