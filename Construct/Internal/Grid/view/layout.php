<?php
    // Header
    echo ipView('Construct/Internal/Grid/view/header.php', $this->getVariables());

    // Actions
    echo ipView('Construct/Internal/Grid/view/actions.php', $this->getVariables());
    if ($pagination->pagerSize() * ($pagination->totalPages() - 1) + count($data) > 100) {
        // Show top pagination if we have more than 100 records.
        echo ipView('Construct/Internal/Grid/view/pages.php', array_merge($this->getVariables(), array('position' => 'top')));
    }

    // Main content
    echo ipView('Construct/Internal/Grid/view/table.php', $this->getVariables());

    // Actions
    if ($pagination->pagerSize() * ($pagination->totalPages() - 1) + count($data) > 10 || $pagination->currentPage() > 1) {
        //show pagination if we have more than 10 records
        echo ipView('Construct/Internal/Grid/view/pages.php', array_merge($this->getVariables(), array('position' => 'bottom')));
    }

    // Modals
    echo ipView('Construct/Internal/Grid/view/deleteModal.php', $this->getVariables());
    echo ipView('Construct/Internal/Grid/view/updateModal.php', $this->getVariables());
    echo ipView('Construct/Internal/Grid/view/createModal.php', $this->getVariables());
    echo ipView('Construct/Internal/Grid/view/searchModal.php', $this->getVariables());
    echo ipView('Construct/Internal/Grid/view/moveModal.php', $this->getVariables());

    // Footer
    echo ipView('Construct/Internal/Grid/view/footer.php', $this->getVariables());

?>
