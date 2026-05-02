<?php if ($pager->getPageCount() > 1): ?>
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center mt-3">

        <!-- Previous -->
        <?php if ($pager->hasPreviousPage()) : ?>
            <li class="page-item">
                <a class="page-link bg-dark text-light border-secondary" href="<?= $pager->getPreviousPage() ?>" aria-label="Previous">
                    &laquo;
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link bg-dark text-muted border-secondary">&laquo;</span>
            </li>
        <?php endif ?>

        <!-- Numbered Links -->
        <?php foreach ($pager->links() as $link): ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link <?= $link['active'] 
                    ? 'bg-primary text-white border-primary' 
                    : 'bg-dark text-light border-secondary' ?>" 
                   href="<?= $link['uri'] ?>">
                    <?= esc($link['title']) ?>
                </a>
            </li>
        <?php endforeach ?>

        <!-- Next -->
        <?php if ($pager->hasNextPage()) : ?>
            <li class="page-item">
                <a class="page-link bg-dark text-light border-secondary" href="<?= $pager->getNextPage() ?>" aria-label="Next">
                    &raquo;
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link bg-dark text-muted border-secondary">&raquo;</span>
            </li>
        <?php endif ?>

    </ul>
</nav>
<?php endif ?>
