<div class="_pages ipsPages">
    <?php echo $pagination->render(ipFile('Construct/Internal/Grid/view/pagination.php')); ?>
    <?php echo ipView('Construct/Internal/Grid/view/pageSize.php', $this->getVariables()); ?>
</div>
