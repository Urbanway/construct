<?php 
namespace Plugin\PluginCreator;
/**
*  Helper
*/
class Helper
{
 
	 public static function createForm()
	 {
	 	 

		$form = new \Construct\Form(array('attributes','class=inline-form'));

		$form->addClass('form-inline');

		$form->addField(new \Construct\Form\Field\Hidden(
		    array(
		        'name' => 'aa', // HTML "name" attribute
		        'value' => 'PluginCreator.create'
		    	)
		    )
		);
		 

		$form->addField(
			new \Construct\Form\Field\Text(
			    array(
			        'name' => 'name', // HTML "name" attribute
			        'label' => 'Plugin Name', // Field label that will be displayed next to input field
		            'note' => 'Name of your plugin, eg. SuperSlider',
		            'validators' => array('Required')
			    )
			)
		);
		$field1 = new \Construct\Form\Field\Text(
			    array(
			        'name' => 'name', // HTML "name" attribute
			        'placeholder' => 'Field Name', // Field label that will be displayed next to input field
 			    )
			);
		$field2 = new \Construct\Form\Field\Select(
			    array(
			        'name' => 'name', // HTML "name" attribute
			        'placeholder' => 'Field Type', // Field label that will be displayed next to input field
 		            'values' => array('checkbox'=>'Checkbox','checkboxes'=>'Checkboxes','color'=>'Color','currency'=>'Currency','grid'=>'Grid (since 4.1.3)','info'=>'Info','integer'=>'Integer','radio'=>'Radio','repository'=>'RepositoryFile','richtext'=>'RichText','select'=>'Select','text'=>'Text','textarea'=>'Textarea ','url'=>'Url')
			    )
			);
		$field3 = new \Construct\Form\Field\Checkbox(
			    array(
			        'name' => 'name', // HTML "name" attribute
			        'placeholder' => 'Required', // Field label that will be displayed next to input field
 			    )
			);
		$form->addFieldset(new \Construct\Form\Fieldset('Add new Field <i>asd</i>'));
	    $form->addField($field1);
	    $form->addField($field2);
	    $form->addField($field3);
 
		$createButton =
			new \Construct\Form\Field\Submit(
			    array(
			        'value' => 'Create'
			    )
			 
		);
		$form->addFieldset(new \Construct\Form\Fieldset(' '));
	    $form->addField($createButton);

		return $form;
	}
}
 ?>