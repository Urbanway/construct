<?php
/** @var $form \Construct\Form */
?>
<form <?php echo $form->getClassesStr(); ?> <?php echo $form->getAttributesStr(); ?>
    method="<?php echo $form->getMethod(); ?>" action="<?php echo $form->getAction(); ?>" enctype="multipart/form-data">
    <?php foreach ($form->getFieldsets() as $fieldsetKey => $fieldset) { ?>
        <fieldset <?php echo $fieldset->getAttributesStr($this->getDoctype()) ?>>
            <?php if ($fieldset->getLabel()) { ?>
                <legend><?php echo esc($fieldset->getLabel()); ?></legend>
            <?php } ?>
            <?php foreach ($fieldset->getFields() as $fieldKey => $field) { ?>
                <?php
                switch ($field->getLayout()) {
                    case \Construct\Form\Field::LAYOUT_DEFAULT:
                    case \Construct\Form\Field::LAYOUT_NO_LABEL:
                        echo ipView('field.php', array('field' => $field))->render() . "\n";
                        break;
                    case \Construct\Form\Field::LAYOUT_BLANK:
                    default:
                        echo $field->render($this->getDoctype(), \Construct\Form::ENVIRONMENT_ADMIN) . "\n";
                        break;
                }
                ?>
            <?php } ?>
        </fieldset>
    <?php } ?>
</form>
