<?php
/**
 * @package construct
 *
 */
namespace Construct\Internal\Content\Widget\Heading;

class Controller extends \Construct\WidgetController
{

    public function getTitle()
    {
        return __('Heading', 'Construct-admin', false);
    }


    public function getActionButtons()
    {
        return array(
            array(
                'label' => __('H1', 'Construct-admin'),
                'class' => 'ipsH1'
            ),
            array(
                'label' => __('H2', 'Construct-admin'),
                'class' => 'ipsH2'
            ),
            array(
                'label' => __('H3', 'Construct-admin'),
                'class' => 'ipsH3'
            ),
            array(
                'label' => __('H4', 'Construct-admin'),
                'class' => 'ipsH4'
            ),
            array(
                'label' => __('H5', 'Construct-admin'),
                'class' => 'ipsH5'
            ),
            array(
                'label' => __('H6', 'Construct-admin'),
                'class' => 'ipsH6'
            ),
            array(
                'label' => __('Options', 'Construct-admin'),
                'class' => 'ipsOptions'
            )
        );
    }

    public function adminHtmlSnippet()
    {
        $maxLevel = (int) ipGetOption('Content.widgetHeadingMaxLevel', 6);
        if ($maxLevel > 6) {
            $maxLevel = 6;
        }
        if ($maxLevel < 1) {
            $maxLevel = 1;
        }
        $variables = array(
            'optionsForm' => $this->optionsForm(),
        );
        $variables2 = array(
            'maxLevel' => $maxLevel
        );
        return ipView('snippet/options.php', $variables)->render() . "\n" . ipView('snippet/controls.php', $variables2)->render();
    }

    public function generateHtml($revisionId, $widgetId, $data, $skin)
    {
        $data['showLink'] = false;
        if (!empty($data['link'])) {
            if (!preg_match('/^((http|https):\/\/)/i', $data['link'])) {
                $data['link'] = 'http://' . $data['link'];
            }

            // hiding link in administration
            if (!ipIsManagementState()) {
                $data['showLink'] = true;
            }
        }

        if (empty($data['level']) || (int)$data['level'] < 1) {
            $data['level'] = 1;
        }

        return parent::generateHtml($revisionId, $widgetId, $data, $skin);
    }

    protected function optionsForm()
    {
        $curUrl = \Construct\Internal\UrlHelper::getCurrentUrl();

        $form = new \Construct\Form();
        $form->setEnvironment(\Construct\Form::ENVIRONMENT_ADMIN);


        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'anchor',
                'label' => __('Anchor', 'Construct-admin', false),
                'note' => __('Anchor', 'Construct-admin') . ' <span class="ipsAnchorPreview">' . $curUrl . '#</span>'
            ));
        $form->addField($field);


        $field = new \Construct\Form\Field\Url(
            array(
                'name' => 'link',
                'label' => __('Link', 'Construct-admin', false),
            ));
        $form->addField($field);


        $field = new \Construct\Form\Field\Checkbox(
            array(
                'name' => 'blank',
                'label' => __('Open in new window', 'Construct-admin', false),
            ));
        $form->addField($field);


        return $form; // Output a string with generated HTML form
    }

}
