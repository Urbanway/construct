<?php

$buttonAction = 'ipsContentPublish';
$buttonText = __('Published', 'Construct-admin', false);
$buttonClass = 'btn-default';
$button2Class = 'btn-default';
$revisionClass = 'btn-default';
$button2Action = 'ipsContentSave';
$button2Text = __('Save', 'Construct-admin', false);
if (!$isPublished) {
    $buttonText = __('Publish', 'Construct-admin', false);
    $buttonClass = 'btn-warning';
    $revisionClass = 'btn-warning';
}

if (!$isVisible && ipIsManagementState()) {
    $buttonAction = 'ipsContentSave';
    $button2Action = 'ipsContentPublish';

    $button2Class = 'btn-warning';
    $revisionClass = 'btn-warning';
    $buttonClass = 'btn-default';

    $buttonText = __('Save', 'Construct-admin', false);
    $button2Text = __('Publish', 'Construct-admin', false);
}

?>
<div class="ipModuleContentPublishButton btn-group">
    <button type="button" class="btn <?php echo $buttonClass ?> navbar-btn <?php echo $buttonAction ?>"><?php echo esc($buttonText) ?></button>
    <button type="button" class="btn <?php echo $revisionClass ?> navbar-btn dropdown-toggle ipsContentRevisions" data-toggle="dropdown"><i class="fa fa-fw fa-caret-down"></i></button>
    <ul class="_revisions dropdown-menu" role="menu">
        <li class="_button"><button type="button" class="btn <?php echo $button2Class ?>  btn-block <?php echo $button2Action ?>"><?php echo esc($button2Text) ?></button></li>
        <li class="divider"></li>
        <?php foreach ($revisions as $revisionKey => $revision){
            $revisionClass = '';
            if ($revision['revisionId'] == $currentRevision['revisionId']) {
                $revisionClass .= $revisionClass ? ' ' : '';
                $revisionClass .= 'active';
            }
            ?>
            <li<?php echo $revisionClass ? ' class="'.$revisionClass.'"' : ''; ?>>
                <a href="<?php echo $managementUrls[$revisionKey]; ?>">
                    <strong><?php echo (int)$revision['revisionId']; ?></strong> - <?php echo ipFormatDateTime(strtotime($revision['createdAt']), 'Construct-admin'); echo $revision['isPublished'] ? ' '.esc(__('Published', 'Construct-admin')) . ' ' : ''; ?>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>
