<?php
/**
 * @package   construct
 *
 *
 */
namespace Construct\Internal\Languages;


class AdminController extends \Construct\GridController
{
    protected $beforeUpdate;

    public function index()
    {
        ipAddJs('Construct/Internal/Languages/assets/languages.js');
        $response = parent::index() . $this->helperHtml();
        return $response;
    }


    protected function helperHtml()
    {

        $helperData = array(
            'addForm' => $form = Helper::getAddForm()
        );
        return ipView('view/helperHtml.php', $helperData)->render();
    }


    protected function config()
    {

        $reservedDirs = ipGetOption('Config.reservedDirs');
        if (!is_array($reservedDirs)) {
            $reservedDirs = [];
        }

        return array(
            'type' => 'table',
            'table' => 'language',
            'allowCreate' => false,
            'allowSearch' => false,
            'actions' => array(
                array(
                    'label' => __('Add', 'Construct-admin', false),
                    'class' => 'ipsCustomAdd'
                )
            ),
            'preventAction' => array($this, 'preventAction'),
            'beforeUpdate' => array($this, 'beforeUpdate'),
            'afterUpdate' => array($this, 'afterUpdate'),
            'beforeDelete' => array($this, 'beforeDelete'),
            'deleteWarning' => __(
                'Are you sure you want to delete? All pages and other language related content will be lost forever!',
                'Construct-admin',
                false
            ),
            'sortField' => 'languageOrder',
            'fields' => array(
                array(
                    'label' => __('Title', 'Construct-admin', false),
                    'field' => 'title',
                ),
                array(
                    'label' => __('Abbreviation', 'Construct-admin', false),
                    'field' => 'abbreviation',
                    'showInList' => true
                ),
                array(
                    'type' => 'Checkbox',
                    'label' => __('Visible', 'Construct-admin', false),
                    'field' => 'isVisible'
                ),
                array(
                    'label' => __('Url', 'Construct-admin', false),
                    'field' => 'url',
                    'showInList' => false,
                    'validators' => array(
                        array('Regex', '/^([^\/\\\])+$/', __('You can\'t use slash in URL.', 'Construct-admin', false)),
                        array(
                            'Unique',
                            array('table' => 'language', 'allowEmpty' => true),
                            __('Language url should be unique', 'Construct-admin', false)
                        ),
                        array('NotInArray', $reservedDirs, __('This is a system directory name.', 'Construct-admin', false)),
                    )
                ),
                array(
                    'label' => __('RFC 4646 code', 'Construct-admin', false),
                    'field' => 'code',
                    'showInList' => false,
                    'validators' => array(
                        array(
                            'Unique',
                            array('table' => 'language'),
                            __('Language code should be unique', 'Construct-admin', false)
                        ),
                    )
                ),
                array(
                    'type' => 'Select',
                    'label' => __('Text direction', 'Construct-admin', false),
                    'field' => 'textDirection',
                    'showInList' => false,
                    'values' => array(
                        array('ltr', __('Left To Right', 'Construct-admin', false)),
                        array('rtl', __('Right To Left', 'Construct-admin', false))
                    )
                ),
            )
        );
    }


    public function addLanguage()
    {
        ipRequest()->mustBePost();
        $data = ipRequest()->getPost();
        if (empty($data['code'])) {
            throw new \Construct\Exception('Missing required parameter');
        }
        $code = $data['code'];
        $abbreviation = strtoupper($code);
        $url = $code;

        $languages = ipContent()->getLanguages();
        foreach ($languages as $language) {
            if ($language->getCode() == $code) {
                return new \Construct\Response\Json(array(
                    'error' => 1,
                    'errorMessage' => __('This language already exist.', 'Construct-admin', false)
                ));
            }
        }

        $languages = Fixture::languageList();
        $directionality = Service::TEXT_DIRECTION_LTR;
        if (!empty($languages[$code])) {
            $language = $languages[$code];
            $title = $language['nativeName'];
            if (!empty($language['directionality']) && $language['directionality'] == 'rtl') {
                $directionality = Service::TEXT_DIRECTION_RTL;
            }
        } else {
            $title = $code;
        }

        Service::addLanguage($title, $abbreviation, $code, $url, 1, $directionality);

        return new \Construct\Response\Json([]);
    }

    public function preventAction($method, $params, $statusVariables)
    {
        if ($method === 'delete') {
            $languages = ipContent()->getLanguages();
            if (count($languages) === 1) {
                return __('Can\'t delete the last language.', 'Construct-admin', false);
            }
        } elseif ($method === 'move') {
            $languages = ipContent()->getLanguages();
            $firstLanguage = $languages[0];

            if ($firstLanguage->getUrlPath() === '') {
                if ($params['beforeOrAfter'] == 'before' && $params['targetId'] == $firstLanguage->getId()
                ) { // moving some language to the top slot

                    $commands = [];

                    // revert drag action
                    $config = new \Construct\Internal\Grid\Model\Config($this->config());
                    $display = new  \Construct\Internal\Grid\Model\Display($config, $config,$statusVariables);
                    $html = $display->fullHtml();
                    $commands[] = \Construct\Internal\Grid\Model\Commands::setHtml($html);

                    // show message
                    $pattern = __(
                        'Please set %s language url to non empty before moving other language to top.',
                        'Construct-admin',
                        false
                    );
                    $commands[] = \Construct\Internal\Grid\Model\Commands::showMessage(
                        sprintf($pattern, $firstLanguage->getAbbreviation())
                    );

                    return $commands;

                } elseif ($params['beforeOrAfter'] == 'after' && $params['id'] == $firstLanguage->getId()
                ) { // moving first language down

                    $commands = [];

                    // revert drag action
                    $config = new \Construct\Internal\Grid\Model\Config($this->config());
                    $display = new  \Construct\Internal\Grid\Model\Display($config, $config, $statusVariables);
                    $html = $display->fullHtml();
                    $commands[] = \Construct\Internal\Grid\Model\Commands::setHtml($html);

                    // show message
                    $pattern = __('Please set %s language url to non empty before moving it down.', 'Construct-admin', false);
                    $commands[] = \Construct\Internal\Grid\Model\Commands::showMessage(
                        sprintf($pattern, $firstLanguage->getAbbreviation())
                    );

                    return $commands;
                }
            } // $firstLanguage->getUrlPath() === ''
        }
        return null;
    }

    public function beforeDelete($id)
    {
        Service::delete($id);
    }

    public function beforeUpdate($id, $newData)
    {
        $this->beforeUpdate = Db::getLanguageById($id);
    }

    public function afterUpdate($id, $newData)
    {
        $updated = Db::getLanguageById($id);
        if ($updated['url'] != $this->beforeUpdate['url']) {
            $languagePath = $updated['url'] == '' ? '' : $updated['url'] . '/';
            $languagePathBefore = $this->beforeUpdate['url'] == '' ? '' : $this->beforeUpdate['url'] . '/';

            $oldUrl = ipConfig()->baseUrl() . $languagePathBefore;
            $newUrl = ipConfig()->baseUrl() . $languagePath;
            ipEvent('ipUrlChanged', array('oldUrl' => $oldUrl, 'newUrl' => $newUrl));
            $oldUrl = ipConfig()->baseUrl() . 'index.php/' . $languagePathBefore;
            $newUrl = ipConfig()->baseUrl() . 'index.php/' . $languagePath;
            ipEvent('ipUrlChanged', array('oldUrl' => $oldUrl, 'newUrl' => $newUrl));
        }

        if ($updated['code'] != $this->beforeUpdate['code']) {
            ipDb()->update(
                'page',
                array('languageCode' => $updated['code']),
                array('languageCode' => $this->beforeUpdate['code'])
            );
        }

        ipContent()->_invalidateLanguages();

        ipEvent('ipLanguageUpdated', array('old' => $this->beforeUpdate, 'new' => $updated));
    }

}
