<?php


namespace Plugin\#NAME#;


class PublicController extends \Construct\Controller
{
    /**
     * Go to /day to see the result
     * @return \Ip\View
     */
    public function index()
    {
        $data['#NAMELOWER#'] = constructQuery()->selectAll('#NAMELOWER#','*');
        return ipView('view/index.php', $data);
    }

   public function view($id)
    {
        $data['#NAMELOWER#'] = constructQuery()->selectRow('#NAMELOWER#','*',array('id'=>$id));
        return ipView('view/single.php', $data);
    }



}
