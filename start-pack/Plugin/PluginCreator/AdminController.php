<?php
namespace Plugin\PluginCreator;


class AdminController extends \Construct\Controller
{
	protected function createFile($value, $template, $savepath){
 
		$template = file_get_contents($template);
		$template = str_replace("#NAME#", $value, $template);
		$template = str_replace("#NAMELOWER#",strtolower($value), $template);
		$newFile = fopen($savepath, 'w');
		fwrite($newFile, $template);
		fclose($newFile);

	}
 
	protected function createGrid ($name,$fields=array(), $template, $savepath){


		$grid_fields = '';
		foreach ($fields as $key => $value) {

		//	print_r($value);
			$select = '';
			if($value[2] == 'Select') {
			$select = "'values' => array(
							array('1', 'Option 1'),
							array('2', 'Option 2')
						),";
			} else {
				$select = '';
			}			
			if($value[2] == 'RepositoryFile') {
				$fieldValue = '$fieldValue';
				$dbRecord = '$dbRecord';
			$preview = "'preview' => function($fieldValue, $dbRecord){ 
							return '<img src=\"'.ipFileUrl('file/repository/' . $fieldValue).'\" style=\"width:50px\">';
						}";
			} else {
				$preview = '';
			}
			$grid_fields  .= "
					array(
	                    'type' => '".$value[2]."',
	                    'label' => '".$value[0]."',
	                    'field' => '".$value[1]."',".$select." ".$preview."
	                ),";
		}
 		$template = file_get_contents($template);
		$template = str_replace("#NAME#", $name, $template);
		$template = str_replace("#FIELDS#", $grid_fields, $template);
		$template = str_replace("#NAMELOWER#",strtolower($name), $template);

		$newFile = fopen($savepath, 'w');
		fwrite($newFile, $template);
		fclose($newFile);

	}
                     
	protected function createWorker ($plugin_name,$fields=array(), $template, $savepath){


		$worker_fields = '';
		foreach ($fields as $key => $value) {
 				switch ($value[2]) {
					case 'Checkbox':
						$type = 'int(1)';
						break;
					case 'Checkboxes':
						$type = 'int(11)';
						break;
					case 'Radio':
						$type = 'int(11)';
						break;
					case 'Select':
						$type = 'int(11)';
						break;
					case 'Url':
						$type = 'varchar(255)';
						break;
					case 'Text':
						$type = 'varchar(255)';
						break;
					case 'Color':
						$type = 'varchar(255)';
						break;
					case 'Currency':
						$type = 'varchar(255)';
						break;
					case 'Info':
						$type = 'text';
						break;
					case 'RichText':
						$type = 'text';
						break;
					case 'Textarea':
						$type = 'text';
						break;					
					case 'RepositoryFile':
						$type = 'text';
						break;
 
					
					default:
						$type = 'varchar(255)';
						break;
				}
 				$worker_fields .= "`$value[1]` $type NOT NULL,\n";
		}
 		$template = file_get_contents($template);
		$template = str_replace("#NAME#", $plugin_name, $template);
		$template = str_replace("#FIELDS#", $worker_fields, $template);
		$template = str_replace("#NAMELOWER#",strtolower($plugin_name), $template);

		$newFile = fopen($savepath, 'w');
		fwrite($newFile, $template);
		fclose($newFile);

	}
	protected function createIndexView ($name,$fields=array(), $template, $savepath){
		$th = '';
 		$worker_fields = '';
		foreach ($fields as $key => $value) {
		 			$type = $value[1];
				 $th .= "<th>".$value[0]."</th>";
 				$worker_fields .= '<td><?php echo $value[\''.$type."']?> </td>\n";
		}
 		$template = file_get_contents($template);
		$template = str_replace("#NAME#", $name, $template);
		$template = str_replace("#FIELDS#", $worker_fields, $template);
		$template = str_replace("#TH#", $th, $template);
		$template = str_replace("#NAMELOWER#",strtolower($name), $template);

		$newFile = fopen($savepath, 'w');
		fwrite($newFile, $template);
		fclose($newFile);

	}                     
	protected function createSingleView ($name, $fields=array(), $template , $savepath){

		$slug='';
		$worker_fields = '';
		foreach ($fields as $key => $value) {
		 			$type = $value[0];
		 			$slug = "'".$value[1]."'";
				 
 				$worker_fields .= '<tr><td>'.$type.'</td><td><?php echo $value['.$slug."]?> </td></tr>\n";
		}
 		$template = file_get_contents($template);
		$template = str_replace("#NAME#", $name, $template);
		$template = str_replace("#FIELDS#", $worker_fields, $template);
		$template = str_replace("#NAMELOWER#",strtolower($name), $template);

		$newFile = fopen($savepath, 'w');
		fwrite($newFile, $template);
		fclose($newFile);

	} 	public function index()
	{
		$form = Helper::createForm();

		$data['form'] = $form;
        $renderedHtml = ipView('view/index.php', $data)->render();
        return $renderedHtml;

	}

	public function create()
	{
		$request = ipRequest()->getPost();
		//print_r($request);
		$fields = array();
		$count = count($request['slug']);

		for ($i=0; $i < $count; $i++) { 
			$fields[] = array($request['name'][$i],$request['slug'][$i],$request['type'][$i]);
		}

		 
 		//print_r($namespace);//['namespace']
        $form = Helper::createForm();

		$plugin_name = ucwords($request['plugin_name']);
        $errors = $form->validate($request);
	 	if ($errors) {
            // Validation error

            $status = array('status' => 'error', 'errors' => $errors);

            return new \Construct\Response\Json($status);
        } else {
	try {
		if(ipRequest()->getPost() == true) {
			$dir = 'Plugin/'.$plugin_name;
			if(!is_dir($dir)) {
				mkdir('Plugin/'.$plugin_name, 0777,true);
				chmod('Plugin/'.$plugin_name, 0777);
			}
			$setup = 'Plugin/'.$plugin_name.'/Setup';
			if(!is_dir($setup)) {
				mkdir('Plugin/'.$plugin_name.'/Setup', 0777,true);
				chmod('Plugin/'.$plugin_name.'/Setup', 0777);
			}

			$assets = 'Plugin/'.$plugin_name.'/assets';
			if(!is_dir($assets)) {
				mkdir('Plugin/'.$plugin_name.'/assets', 0777,true);
				chmod('Plugin/'.$plugin_name.'/assets', 0777);
			}

			$view = 'Plugin/'.$plugin_name.'/view';
			if(!is_dir($view)) {
				mkdir('Plugin/'.$plugin_name.'/view', 0777,true);
				chmod('Plugin/'.$plugin_name.'/view', 0777);
			}
 		}
 		$this->createFile($plugin_name, 'Plugin/PluginCreator/templates/Event.php',$dir.'/Event.php');
		$this->createFile($plugin_name, 'Plugin/PluginCreator/templates/Filter.php',$dir.'/Filter.php');

		$this->createFile($plugin_name, 'Plugin/PluginCreator/templates/plugin.json',$setup.'/plugin.json');
		$this->createFile($plugin_name, 'Plugin/PluginCreator/templates/application.js',$assets.'/'.strtolower($plugin_name).'.js');
		$this->createFile($plugin_name, 'Plugin/PluginCreator/templates/application.css',$assets.'/'.strtolower($plugin_name).'.css');


		$this->createFile($plugin_name, 'Plugin/PluginCreator/templates/PublicController.php',$dir.'/PublicController.php');
		$this->createFile(strtolower($plugin_name), 'Plugin/PluginCreator/templates/routes.php',$dir.'/routes.php');
			
 
		$this->createWorker($plugin_name, $fields, 'Plugin/PluginCreator/templates/Worker.php',$setup.'/Worker.php');
		$this->createGrid($plugin_name, $fields, 'Plugin/PluginCreator/templates/AdminController.php',$dir.'/AdminController.php');

		$this->createSingleView($plugin_name,$fields, 'Plugin/PluginCreator/templates/single.php',$view.'/single.php');
		$this->createIndexView($plugin_name,$fields, 'Plugin/PluginCreator/templates/index.php',$view.'/index.php');

        } catch (\Construct\Exception $e) {
            return JsonRpc::error($e->getMessage());
        }
        // TODO jsonrpc
    		$actionUrl = ipActionUrl(array('aa' => 'PluginCreator.showSuccessMessage'));
            $status = array('redirectUrl' => $actionUrl);
            return new \Construct\Response\Json($status);
        } 
	}
	 public function showSuccessMessage()
    {
        $renderedHtml = ipView('view/success.php')->render();

        return $renderedHtml;
    }

}
