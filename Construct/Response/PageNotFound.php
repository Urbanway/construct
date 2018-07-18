<?php
/**
 * @package construct
 *
 *
 */

namespace Construct\Response;

/**
 *
 * Event dispatcher class
 *
 */
class PageNotFound extends \Construct\Response\Layout
{

    public function __construct($content = null, $headers = null, $statusCode = 404)
    {
        if ($content === null) {
            $content = $this->generateError404Content();
        }
        parent::__construct($content, $headers, $statusCode);
        $this->addHeader('HTTP/1.0 404 Not Found');
        $this->setTitle(__('Error 404', 'Construct', false));

    }

    public function getLayout()
    {
        if ($this->layout) {
            return $this->layout;
        }
        return is_file(ipThemeFile('404.php')) ? '404.php' : 'main.php';
    }

    protected function generateError404Content()
    {
        $data = array(
            'title' => __('Error 404', 'Construct', false),
            'text' => self::error404Message()
        );
        $content = ipView(ipFile('Construct/Internal/Config/view/error404.php'), $data)->render();
        return $content;
    }

    /**
     * Find the reason why the user come to non-existent URL
     * @return string error message
     */
    protected function error404Message()
    {
        $message = '';
        if (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == '') {
            //mistyped URL
            $message = __('Sorry, but the page you were trying to get to does not exist.', 'Construct', false);
        } else {
            if (strpos($_SERVER['HTTP_REFERER'], ipConfig()->baseUrl()) < 5 && strpos(
                    $_SERVER['HTTP_REFERER'],
                    ipConfig()->baseUrl()
                ) !== false
            ) {
                //Broken internal link
                $message = '<p>' . __('Sorry, but the page you were trying to get to does not exist.', 'Construct') . '</p>';
            } elseif (strpos($_SERVER['HTTP_REFERER'], ipConfig()->baseUrl()) === false) {
                //Broken external link
                $message = '<p>' . __('Sorry, but the page you were trying to get to does not exist.', 'Construct') . '</p>';
            }
        }
        return $message;
    }


}


