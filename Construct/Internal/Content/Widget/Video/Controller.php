<?php
/**
 * @package construct
 *
 */
namespace Construct\Internal\Content\Widget\Video;


class Controller extends \Construct\WidgetController
{
    public function getTitle()
    {
        return __('Video', 'Construct-admin', false);
    }


    public function generateHtml($revisionId, $widgetId, $data, $skin)
    {
        $videoHtml = $this->generateVideoHtml($data);
        if ($videoHtml) {
            $data['videoHtml'] = $videoHtml;
        }

        return parent::generateHtml($revisionId, $widgetId, $data, $skin);
    }


    protected function generateVideoHtml($data)
    {
        if (empty($data['url'])) {
            return false;
        }
        $url = $data['url'];


        if (preg_match('%^[^"&?/ ]{11}$%i', $url)) {
            //youtube id
            $url = 'http://www.youtube.com/embed/' . $url;
        }
        if (preg_match('%^[0-9]+$%i', $url)) {
            //vimeo id
            $url = 'http://player.vimeo.com/video/' . $url;
        }

        if (!preg_match('/^((http|https):\/\/)/i', $url)) {
            $url = 'http://' . $url;
        }

        if (preg_match('/^((http|https):\/\/)?(www.)?youtube.com/i', $url)) {
            //youtube video


            if (preg_match('/youtube.com\/watch\?v=/i', $url)) {
                $url = str_replace('youtube.com/watch?v=', 'youtube.com/embed/', $url);
            }
            if (ipIsManagementState()) {
                if (preg_match('/\?/s', $url)) {
                    $url .= '&wmode=opaque';
                } else {
                    $url .= '?wmode=opaque';
                }

            }
            return $this->renderView('view/youtube.php', $url, $data);
        }

        if (preg_match('/^((http|https):\/\/)?(www.)?youtu.be/i', $url)) {
            //youtube video

            $url = str_replace('youtu.be/', 'youtube.com/embed/', $url);
            if (ipIsManagementState()) {
                if (preg_match('/\?/s', $url)) {
                    $url .= '&wmode=opaque';
                } else {
                    $url .= '?wmode=opaque';
                }

            }
            return $this->renderView('view/youtube.php', $url, $data);
        }

        if (preg_match('/^((http|https):\/\/)?(www.)?(player.)?vimeo.com/i', $url)) {
            if (preg_match('%www.vimeo.com%i', $url)) {
                $url = str_replace('www.vimeo.com', 'player.vimeo.com', $url);
            }
            if (preg_match('%//vimeo.com%i', $url)) {
                $url = str_replace('//vimeo.com', '//player.vimeo.com', $url);
            }
            if (strpos($url, '/video') === false) {
                $url = str_replace('vimeo.com', 'vimeo.com/video', $url);
            }

            return $this->renderView('view/vimeo.php', $url, $data);
        }


        return false;
    }

    protected function renderView($viewFile, $url, $data)
    {
        $variables = array(
            'url' => $url,
            'style' => '',
            'iframeStyle' => ''
        );

        if (!empty($data['size']) && $data['size'] != 'auto') {
            if (empty($data['width'])) {
                $data['width'] = 853;
            }
            if (empty($data['height'])) {
                $data['height'] = 480;
            }
            $variables['iframeStyle'] = 'width: ' . $data['width'] . 'px; height: ' . $data['height'] . 'px;';
        } else {
            $variables['iframeStyle'] = 'height: 100%; width:100%; position: absolute; top: 0; left: 0;';
            if (!empty($data['ratio']) && $data['ratio'] != '16:9') {
                $variables['style'] = 'padding-bottom: 75% !important; position: relative;';
            } else {
                $variables['style'] = 'padding-bottom: 56.25% !important; position: relative;';
            }
        }


        return ipView($viewFile, $variables)->render();

    }

    public function adminHtmlSnippet()
    {


        $form = $this->editForm();
        $variables = array(
            'form' => $form
        );

        return ipView('snippet/edit.php', $variables)->render();
    }

    protected function editForm()
    {
        $form = new \Construct\Form();
        $form->setEnvironment(\Construct\Form::ENVIRONMENT_ADMIN);


        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'url',
                'label' => __('Url', 'Construct-admin', false),
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Select(
            array(
                'name' => 'size',
                'label' => __('Size', 'Construct-admin', false),
            ));

        $values = array(
            array('auto', __('Auto', 'Construct-admin', false)),
            array('custom', __('Custom', 'Construct-admin', false)),
        );
        $field->setValues($values);

        $form->addField($field);

        $field = new \Construct\Form\Field\Number(
            array(
                'name' => 'width',
                'label' => __('Width', 'Construct-admin', false),
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Number(
            array(
                'name' => 'height',
                'label' => __('Height', 'Construct-admin', false),
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Select(
            array(
                'name' => 'ratio',
                'label' => __('Aspect ratio', 'Construct-admin', false),
            ));
        $values = array(
            array('16:9', __('16:9', 'Construct-admin', false)),
            array('4:3', __('4:3', 'Construct-admin', false)),
        );
        $field->setValues($values);
        $form->addField($field);


        return $form; // Output a string with generated HTML form
    }

}
