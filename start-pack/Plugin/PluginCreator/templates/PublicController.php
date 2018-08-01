<?php


namespace Plugin\#NAME#;


class PublicController extends \Ip\Controller
{
    /**
     * Go to /day to see the result
     * @return \Ip\View
     */
    public function index()
    {
        $data['#NAMELOWER#'] = ipdb()->selectAll('#NAMELOWER#','*');
        return ipView('view/index.php', $data);
    }

   public function view($id)
    {
        $data['#NAMELOWER#'] = ipdb()->selectRow('#NAMELOWER#','*',array('id'=>$id));
        return ipView('view/single.php', $data);
    }



}
