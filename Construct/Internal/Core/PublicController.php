<?php
/**
 * @package   construct
 */


namespace Construct\Internal\Core;


class PublicController extends \Construct\Controller
{
    /**
     * Dummy function used to preserve user session
     */
    public function ping()
    {
        return new \Construct\Response\Json(array(1));
    }

    public function pageNotFound()
    {
        $content = null;
        $error404Page = ipContent()->getPageByAlias('error404');
        if ($error404Page) {
            $revision = \Construct\Internal\Revision::getPublishedRevision($error404Page->getId());
            $content = \Construct\Internal\Content\Model::generateBlock('main', $revision['revisionId'], 0, 0);
        }
        return new \Construct\Response\PageNotFound($content);
    }
}
