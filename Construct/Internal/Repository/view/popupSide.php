<div class="ipsFiles"></div>
<div class="ipsUploadProgressContainer  <?php echo $allowUpload ? '' : 'hidden' ?>">
    <div class="ipsCurErrors"></div>
    <div class="ipsBrowseButtonWrapper _browseButtonWrapper">
        <span class="_label _dragdropNotice"><?php _e('Drag&drop files here or click a button to upload.', 'Construct-admin'); ?></span>
        <a href="#" class="btn btn-primary" id="ipsModuleRepositoryUploadButton"><?php _e('Add new', 'Construct-admin'); ?></a>
    </div>
    <div class="_browseButtonWrapper">
        <span class="_label"><?php _e('Need more images? Browse and choose from thousands of them.', 'Construct-admin'); ?></span>
        <a href="#ipsModuleRepositoryTabBuy" class="btn btn-warning" id="ipsModuleRepositoryBuyButton"><?php _e('Buy images', 'Construct-admin'); ?></a>
    </div>
</div>
<div class="ipsUploadProgressItemSample hidden">
    <div class="ipModuleUploadProgressItem ipsUploadProgressItem">
        <div class="_progressbar ipsUploadProgressbar"></div>
        <p class="_title ipsUploadTitle"></p>
    </div>
</div>
<p class="ipsErrorSample alert alert-danger hidden"></p>
