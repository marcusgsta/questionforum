<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>

    <?php foreach ($stylesheets as $stylesheet) : ?>
    <link rel="stylesheet" type="text/css" href="<?= $this->asset($stylesheet) ?>">
    <?php endforeach; ?>

    <?php foreach ($javascripts as $javascript) : ?>
        <script type="text/javascript" src="<?=$this->asset($javascript); ?>">
        </script>
    <?php endforeach; ?>

</head>
<body>

<?php if ($this->regionHasContent("header")) : ?>
<div class="header-wrap">
    <?php $this->renderRegion("header") ?>
</div>
<?php endif; ?>

<?php if ($this->regionHasContent("navbar")) : ?>
<div class="navbar-wrap">
    <?php $this->renderRegion("navbar") ?>
</div>
<?php endif; ?>

<?php if ($this->regionHasContent("main")) : ?>
<div class="main-wrap">
    <?php $this->renderRegion("main") ?>


<?php if ($this->regionHasContent("questionsection")) : ?>
    <div class="questionsection-wrap">
        <?php $this->renderRegion("questionsection") ?>
    </div>
<?php endif; ?>

</div> <!-- end of main -->

<?php endif; ?>

<?php if ($this->regionHasContent("commentsection")) : ?>
<div class="commentsection">
    <?php $this->renderRegion("commentsection") ?>
</div>
<?php endif; ?>

<?php if ($this->regionHasContent("footer")) : ?>
<div class="footer-wrap">
    <?php $this->renderRegion("footer") ?>
</div>
<?php endif; ?>

</body>
</html>
