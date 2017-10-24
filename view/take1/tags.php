<div class="tags-section">
<h2>Taggar</h2>

<?php

$tags = $data;


foreach ($tags as $tag) : ?>

<div class="tag-wrap badge">

<?php
$tagid = $tag->id;
$route = $this->url("tag/$tagid")
?>
    <div class="tag float-left">
        <a href="<?=$route;?>" class="btn btn-primary">
            <?=$tag->tagtext;?>
        </a>
<ul class="taglist">
<?php foreach ($tag->question as $question) :
    $questionid = $question->id;
    $route = $this->url("question/show/$questionid")
?>
    <li><a href="<?=$route?>"><?=$question->questiontitle;?></a></li>
<?php endforeach; ?>
</ul>
    </div>

</div>

<?php endforeach; ?>
</div>
