<div class="questionssection">
<h2>De senaste frågorna</h2>

<?php $url = $this->url("question/create"); ?>

<div id="questionbutton" class="float-right">
<a href="<?=$url?>" class="btn btn-primary">Ställ en fråga</a>
</div>

<?php

$questions = $data['latestQuestions'];
$popularTags = $data['popularTags'];
$mostActiveUsers = $data['mostActiveUsers'];

foreach ($questions as $question) : ?>

<div class="question-wrap clear">
<?php
$id = $question->id;
$url = $this->url("question/show/$id"); ?>
    <div class="question">
        <p><a href="<?=$url?>"><?=$question->questiontitle->text;?></a></p>
        <p><?=$question->excerpt;?> [ . . . . . . ]</p>
    </div>

    <div class="share">
        
    </div>
    <div class="user-wrap float-right">
        <div class="created">
            <span>Frågat <?=$question->created;?></span>
        </div>
        <div class="gravatar float-left">
        <img src="<?=$question->user->gravatar;?>">
        </div>
        <div class="acronym">
            <?php
            $userid = $question->user->id;
            $route = $this->url("user/show/$userid");?>
            <span><a href="<?=$route?>"><?=$question->user->acronym;?></a></span>
        </div>

    </div> <!-- end of .user-wrap -->

</div>
<!--</div><! end of .questionssection -->
<?php endforeach; ?>

    <!-- TAG SECTION -->

<div class="tag-section">

<h3>Populäraste taggar:</h3>
<ul class="taglist-index">
<?php foreach ($popularTags as $tag) :
    $tagid = $tag->id;
    $route = $this->url("tag/$tagid")
?>

<li>
    <a href="<?=$route;?>" class="btn btn-primary float-left">
        <?=$tag->tagtext . "  (" . $tag->count . ")";?>
    </a>
</li>

<?php endforeach; ?>
</ul>
</div> <!-- end of tag-section -->

<div class="active-users clear">
        <h3>Mest aktiva användare</h3>
<?php foreach ($mostActiveUsers as $user) :
    $userid = $user->id;
    $user->count = isset($user->count) ? $user->count : 0;
    $route = $this->url("user/show/$userid");
?>
<li>
    <a href="<?=$route;?>">
        <?=$user->acronym . " (" . $user->count . " frågor ställda)";?>
    </a>
</li>
<?php endforeach; ?>
</div>
