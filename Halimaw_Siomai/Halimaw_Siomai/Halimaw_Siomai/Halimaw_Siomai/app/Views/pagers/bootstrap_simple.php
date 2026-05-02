<?php if ($pager->hasPreviousPage() || $pager->hasNextPage()) : ?>
<nav aria-label="Simple page navigation">
    <ul class="pagination justify-content-center mt-3">
        <?php if ($pager->hasPreviousPage()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getPreviousPage() ?>" aria-label="Previous">
                    &laquo; Previous
                </a>
            </li>
        <?php endif; ?>

        <?php if ($pager->hasNextPage()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getNextPage() ?>" aria-label="Next">
                    Next &raquo;
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif ?>
