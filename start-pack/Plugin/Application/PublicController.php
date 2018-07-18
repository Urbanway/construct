<?php


namespace Plugin\Application;


class PublicController extends \Construct\Controller
{
    /**
     * Go to /day to see the result
     * @return \Construct\View
     */
    public function day($day = null)
    {
        // Uncomment to include assets
        // ipAddJs('assets/application.js');
        // ipAddCss('assets/application.css');

        if (!$day) {
            $day = date('l');
        }

        $data = array(
            'day' => $day
        );

        //change the layout if you like
        //ipSetLayout('home.php');

        return ipView('view/day.php', $data);
    }



}
