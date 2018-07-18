<?php
/**
 * @package        construct
 */

namespace Construct;

abstract class GridController extends \Construct\Controller
{
    public function index()
    {
        ipAddJs('Construct/Internal/Grid/assets/grid.js');
        ipAddJs('Construct/Internal/Grid/assets/gridInit.js');
        ipAddJs('Construct/Internal/Grid/assets/subgridField.js');


        $controllerClass = get_class($this);
        $controllerClassParts = explode('\\', $controllerClass);

        $aa = $controllerClassParts[count($controllerClassParts) - 2] . '.grid';

        $gateway = array('aa' => $aa);

        $variables = array(
            'gateway' => ipActionurl($gateway)
        );
        $content = ipView('Internal/Grid/view/placeholder.php', $variables)->render();
        return $content;
    }

    public function grid()
    {
        $worker = new \Construct\Internal\Grid\Worker($this->config());
        $result = $worker->handleMethod(ipRequest());

        if (is_array($result) && !empty($result['error']) && !empty($result['errors'])) {
            return new \Construct\Response\Json($result);
        }

        return new \Construct\Response\JsonRpc($result);
    }

    /**
     * @return array
     */
    abstract protected function config();


}
