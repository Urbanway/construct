<?php
/**
 * @package construct
 *
 *
 */

namespace Construct\Response;

class Redirect extends \Construct\Response
{

    public function __construct($url, $content = null, $headers = null, $statusCode = 301)
    {
        parent::__construct($content, $headers, $statusCode);
        $this->addHeader('HTTP/1.1 301 Moved Permanently');
        $this->addHeader('Location: ' . $url);
    }

}
