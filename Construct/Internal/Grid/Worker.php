<?php
/**
 * @package   construct
 */

namespace Construct\Internal\Grid;


class Worker
{

    protected $config = null;
    protected $model = null;

    /**
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        if (empty($this->config['type'])) {
            $this->config['type'] = 'table';
        }
    }

    public function handleMethod(\Construct\Request $request)
    {
        switch ($this->config['type']) {
            case 'table':
                $this->model = new Model\Table($this->config, $request);
                break;
            default:
                throw new \Construct\Exception('Undefined Grid type');
        }
        $commands = $this->model->handleMethod();
        return $commands;
    }


}
