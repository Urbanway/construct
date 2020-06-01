<form class=" ipsAjaxSubmit ipsModuleFormAdmin form-inline" method="post" action="" enctype="multipart/form-data" novalidate="novalidate">
	<fieldset>
		
		<input id="field_6712802595364143105" class="" name="securityToken" type="hidden" value="<?php echo ipSecurityToken() ?>">
		
		
		<input name="antispam[]" type="hidden" value="">
		<input name="antispam[]" type="hidden" value="<?php echo  htmlspecialchars(md5(date('Y-m-d') . ipConfig()->get('sessionName'))) ?>">
		<input id="field_3023215861767864321" class="" name="aa" type="hidden" value="PluginCreator.create">
		<div class="form-group type-text">
			<label for="pluginName">
			Plugin Name        </label>
			<input id="pluginName" required="" class="form-control" name="plugin_name" type="text" >    
			<div class="help-block">Name of your plugin, eg. SuperSlider</div>
		</div>
	</fieldset>
	<div id="template_container">
		<legend>Add Form Fields  <a href="#" onclick="Add(this)" class="bttn bttn-primary pull-right"> Add new field</a></legend>
		<fieldset id="template">
			<div class="form-group type-text">
				
				<input id="name" class="form-control " name="name[]" type="text" placeholder="Field name">   
				<input id="slug" class="form-control " name="slug[]" type="text"  placeholder="Computer field name">    
			</div>
			<div class="form-group type-select">
				
				
				<select id="type" name="type[]" class="form-control ">
					<option value="Checkbox">Checkbox</option>
					<option value="Checkboxes">Checkboxes</option>
					<option value="Color">Color</option>
					<option value="Currency">Currency</option>
 					<option value="Info">Info</option>
					<option value="Integer">Integer</option>
					<option value="Radio">Radio</option>
					<option value="RepositoryFile">Repository File</option>
					<option value="RichText">RichText</option>
					<option value="Select">Select</option>
					<option value="Text">Text</option>
					<option value="Textarea">Textarea </option>
					<option value="Url">Url</option>
				</select>
			</div>
			 
			<div class="form-group   pull-right">
				<a href="#" onclick="Remove(this)">Remove me </a>
			</div>
		</fieldset>
	</div>
	<div class="form-group type-submit">
 		<button id="create" class="bttn bttn-default " name="create" type="submit">Create</button>     
	</div>
</form>