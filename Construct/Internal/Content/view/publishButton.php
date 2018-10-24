<?php

$buttonAction = 'ipsContentPublish';
$buttonText = __('Published', 'Construct-admin', false);
$buttonClass = ' ';
$button2Class = 'bttn-default';
$revisionClass = ' ';
$button2Action = 'ipsContentSave';
$button2Text = __('Save', 'Construct-admin', false);
if (!$isPublished) {
    $buttonText = __('Publish', 'Construct-admin', false);
    $buttonClass = 'bttn-warning';
    $revisionClass = 'bttn-warning';
}

if (!$isVisible && ipIsManagementState()) {
    $buttonAction = 'ipsContentSave';
    $button2Action = 'ipsContentPublish';

    $button2Class = 'bttn-warning';
    $revisionClass = 'bttn-warning';
    $buttonClass = 'bttn-default';

    $buttonText = __('Save', 'Construct-admin', false);
    $button2Text = __('Publish', 'Construct-admin', false);
}

?>
<div class="ipModuleContentPublishButton bttn-group">
    <button type="button" class="bttn <?php echo $buttonClass ?> text-white menubar-bttn <?php echo $buttonAction ?>"><?php echo esc($buttonText) ?></button>
    <button type="button" class="bttn <?php echo $revisionClass ?> menubar-bttn select-toggle ipsContentRevisions" data-toggle="dropdown"><i class="fa fa-fw fa-caret-down"></i></button>
    <ul class="_revisions dropdown-menu" role="menu">
        <li class="_button"><button type="button" class="bttn <?php echo $button2Class ?>  bttn-block <?php echo $button2Action ?>"><?php echo esc($button2Text) ?></button></li>
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
