<div class="question-section">
<?php $url = $this->url("question/create"); ?>
<div id="questionbutton" class="float-right">
<a href="<?=$url?>" class="">Ställ en fråga</a>
</div>

<?php

$question = $data['question'];
$commentsQuestion = $data['commentsQuestion'];
$commentFormQuestion = $data['commentFormQuestion'];
$answerForm = $data['answerForm'];
$answers = $data['answers'];

?>

<div class="question-wrap clear">

    <div class="question">
        <h2><?=$question->questiontitle->text;?></h2>
        <p><?=$question->questiontext->text;?></p>

        <div class="share">
            <!-- <a href="#">Share</a> -->
<?php
$tags = $question->tags;
foreach ($tags as $tag):
$tagid = $tag->id;
$route = $this->url("tag/$tagid");
?>
            <span class="tag float-left"><a href="<?=$route;?>"><?=$tag->tagtext->text;?></a></span>
<?php endforeach; ?>
        </div>

        <div class="user-wrap float-right">
            <div class="created">
                <span>Frågat <?=$question->created;?></span>
            </div>
            <div class="gravatar float-left">
            <img src="<?=$question->user->gravatar;?>">
            </div>
            <div class="acronym">
            <?php $userid = $question->user->id;
                $route = $this->url("user/show/$userid");?>
                <span><a href="<?=$route?>"><?=$question->user->acronym;?></a></span>
            </div>

        </div> <!-- end of .user-wrap -->

    </div> <!-- end of .question -->

</div> <!-- end of .question-wrap -->

<div class="comments-question">
<?php foreach ($commentsQuestion as $comment) : ?>
    <div class="comment">
        <span><?=$comment->commenttext->text;?>
<?php $userid = $comment->user->id;
$route = $this->url("user/show/$userid"); ?>
            <span class="float-right">
                <a href="<?=$route;?>">
                    – <?=$comment->user->acronym;?>
                </a>
            </span>
        </span>
    </div>
<?php endforeach; ?>
</div> <!-- end of .comments-question -->

<!-- Comment Form Question -->
<!-- <a href="#" id="add-comment">Lägg till en kommentar</a> -->
<?php
if ($this->di->get("loginController")->anyLoggedin() == true) {
    echo "<div class='comment-form-question clear'>" . $commentFormQuestion . "</div>";
} else {
    $route = $this->url("user/login");
    echo "<p><a href='$route'>Logga in för att kommentera.</a></p>";
}?>

<div class="answer-wrap">

<?php foreach ($answers as $answer) : ?>
    <div class="answer">

        <h2><?=$answer->answertitle->text;?></h2>
        <p><?=$answer->answertext->text;?></p>

        <div class="share">
            <a href="#">Share</a>
        </div>

        <div class="user-wrap float-right">
            <div class="created">
                <span>Frågat <?=$answer->created;?></span>
            </div>
            <div class="gravatar float-left">
                <img src="<?=$answer->user->gravatar;?>">
            </div>
            <div class="acronym">
<?php $userid = $answer->user->id;
$route = $this->url("user/show/$userid");?>
            <span><a href="<?=$route?>"><?=$answer->user->acronym;?></a></span>
            </div>
        </div> <!-- end of .user-wrap -->

    </div> <!-- end of .answer -->

    <!-- COMMENTS FOR EACH ANSWER -->
    <div class="comments-answer">
<?php $commentsAnswer = $answer->commentsAnswer;?>
    <?php foreach ($commentsAnswer as $comment) : ?>
        <div class="comment">
            <span><?=$comment->commenttext->text;?>
    <?php $userid = $comment->user->id;
    $route = $this->url("user/show/$userid"); ?>
                <span class="float-right">
                    <a href="<?=$route;?>">
                        – <?=$comment->user->acronym;?>
                    </a>
                </span>
            </span>
        </div>
    <?php endforeach; ?>
</div> <!-- end of .comments-answer -->

    <!-- COMMENT FORM ANSWER (ONE FOR EVERY ANSWER)-->
<?php
if ($this->di->get("loginController")->anyLoggedin() == true) {
    echo "<div class='comment-form-answer'>" . $answer->commentForm . "</div>";
} else {
    $route = $this->url("user/login");
    echo "<p><a href='$route'>Logga in för att kommentera.</a></p>";
}?>

<?php endforeach;
if ($this->di->get("loginController")->anyLoggedin() == true) {
    echo "<div class='answer-section'>" . $answerForm . "</div>";
} else {
    $route = $this->url("user/login");
    echo "<a href='$route'>Logga in för att svara.</a>";
}?>

</div> <!-- end of .answer-wrap -->
</div> <!-- end of .question-section -->
