<?php
/**
 * @package   construct
 */


namespace Construct;


abstract class Payment
{

    public abstract function name();

    public abstract function icon($width = null, $height = null);

    /**
     * HTML to be displayed in payment selection window.
     * Used only if there are more than one payment method installed.
     * All this HTML will be surrounded by A tag to be clickable.
     * @return string
     */
    public function html()
    {
        return '<img src="'. escAttr($this->icon()) .'" alt="' . escAttr($this->name()) .'" />';
    }

    /**
     * This method should generate payment URL.
     * Typical actions of this method:
     * 1 write down all passed data to database table
     * 2 return URL which starts payment method execution
     *
     * @param array $data payment data
     */
    public abstract function paymentUrl($data);
}
