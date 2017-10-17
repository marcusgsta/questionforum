<div class="tags-section">
<h2>Taggar</h2>

<?php

$tags = $data;


foreach ($tags as $tag) : ?>

<div class="tag-wrap">

<?php
$tagid = $tag->id;
$route = $this->url("tag/$tagid")
?>
    <div class="tag">
        <a href="<?=$route;?>">
            <?=$tag->tagtext;?>
        </a>
<ul>
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
