<?php
/**
 * @package construct
 *
 *
 */

namespace Construct\Internal\Admin;


class MenuItem extends \Construct\Menu\Item
{
    /**
     * @var string
     */
    protected $icon;

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
    }
}
