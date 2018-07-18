<?php
/**
 * @package construct
 *
 *
 */
namespace Construct\Internal\Config;


class AdminController extends \Construct\Controller
{

    public function index()
    {


        ipAddJs('Construct/Internal/Config/assets/config.js');
        ipAddCss('Construct/Internal/Config/assets/config.css');

        $form = Forms::getForm();
        $advancedForm = false;
        if (ipAdminPermission('Config advanced')) {
            $advancedForm = Forms::getAdvancedForm();
        }
        $data = array(
            'form' => $form,
            'advancedForm' => $advancedForm
        );
        return ipView('view/configWindow.php', $data)->render();

    }


    public function saveValue()
    {
        $request = \Construct\ServiceLocator::request();

        $request->mustBePost();

        $post = $request->getPost();
        if (empty($post['fieldName'])) {
            throw new \Exception('Missing required parameter');
        }
        $fieldName = $post['fieldName'];
        if (!isset($post['value'])) {
            throw new \Exception('Missing required parameter');
        }
        $value = $post['value'];

        if (
            !in_array($fieldName, array('websiteTitle', 'websiteEmail', 'gmapsApiKey'))
            &&
            !(
                in_array($fieldName, array('automaticCron', 'cronPassword', 'removeOldRevisions', 'removeOldRevisionsDays', 'removeOldEmails', 'removeOldEmailsDays', 'allowAnonymousUploads', 'trailingSlash'))
                &&
                ipAdminPermission('Config advanced')
            )
        ) {
            throw new \Exception('Unknown config value');
        }




        $emailValidator = new \Construct\Form\Validator\Email();
        $error = $emailValidator->getError(array('value' => $value), 'value', \Construct\Form::ENVIRONMENT_ADMIN);
        if ($fieldName === 'websiteEmail' && $error !== false) {
            return $this->returnError($error);
        }


        if (in_array($fieldName, array('websiteTitle', 'websiteEmail'))) {
            if (!isset($post['languageId'])) {
                throw new \Exception('Missing required parameter');
            }
            $languageId = $post['languageId'];
            $language = ipContent()->getLanguage($languageId);
            ipSetOptionLang('Config.' . $fieldName, $value, $language->getCode());
        } else {
            ipSetOption('Config.' . $fieldName, $value);
        }


        return new \Construct\Response\Json(array(1));

    }

    private function returnError($errorMessage)
    {
        $data = array(
            'error' => $errorMessage
        );
        return new \Construct\Response\Json($data);
    }
}
