<div class="questions-section">
<h2></h2>

<?php

$tag = $data[0];
$questions = $tag->question;
?>

<div class="question-wrap">
<h1>Frågor med taggen '<?=$tag->tagtext?>'</h1>
<?php
foreach ($questions as $question) :
    $questionid = $question->id;
    $route = $this->url("question/show/$questionid")
?>
    <div class="question">
        <h3><a href="<?=$route?>"><?=$question->questiontitle;?></a></h3>
        <p><?=$question->questiontext?></p>
    </div>

<?php endforeach; ?>
    </div>

</div>
