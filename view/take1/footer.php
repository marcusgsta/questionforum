</div>
<footer class="footer">
    <div class="container text-center">



<span class="text-muted">
    &copy; Marcus Gustafsson 2017
</span>

<?php
$val = $this->url("user/logout");

if (isset($data['user'])) : ?>
<a href="<?=$val?>" class="logout-link">Logga ut</a>
<?php endif; ?>

        </div>
</footer>
