<div class="question-section">
<?php $url = $this->url("question/create"); ?>
<div id="questionbutton" class="float-right">
<a href="<?=$url?>" class="btn btn-primary">Ställ en fråga</a>
</div>

<?php

$question = $data['question'];
$commentsQuestion = $data['commentsQuestion'];
$commentFormQuestion = $data['commentFormQuestion'];
$answerForm = $data['answerForm'];
$answers = $data['answers'];

?>

<div class="question-wrap clear">

    <div class="question" id="question-<?=$question->id;?>">
        <h2><?=$question->questiontitle->text;?></h2>
        <p><?=$question->questiontext->text;?></p>

<!-- check if logged in user has written the question -->
<?php
$userid = $question->userid;
$loggedinUser = $this->di->get("userController")->getLoggedinUser();

if (isset($loggedinUser->id)) {
    if ($userid == $loggedinUser->id || $loggedinUser->role == 10) {
        include("edit-button.php");
    };
};
?>
        <!-- VOTE BUTTONS -->
<?php include("vote-buttons.php"); ?>

        <!-- TAGS -->
        <div class="tags">
<?php
$tags = $question->tags;
foreach ($tags as $tag) :
    $tagid = $tag->id;
    $route = $this->url("tag/$tagid");
?>
            <span class="tag float-left">
                <a href="<?=$route;?>">
                    <?=$tag->tagtext->text;?>
                </a>
            </span>
<?php endforeach; ?>
        </div>

        <!-- USER BADGE -->
<?php
$user = $question->user;
$created = $question->created;
$updated = $question->updated;
include("userbadge.php");
?>

    </div> <!-- end of .question -->
</div> <!-- end of .question-wrap -->

<div class="comments-question">
<?php foreach ($commentsQuestion as $comment) : ?>
    <div class="comment" id="comment-<?=$comment->id?>">
        <span><?=$comment->commenttext->text;?>

<?php include("comment-vote.php"); ?>

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
    echo "<a id='show-hide-form' href=''>Kommentera</a><div class='comment-form comment-form-question hidden clear'>" . $commentFormQuestion . "</div>";
} else {
    $route = $this->url("user/login");
    echo "<p><a href='$route'>Logga in för att kommentera.</a></p>";
}?>

<!-- ANSWERS SECTION -->
<?php include("answers.php");?>

</div> <!-- end of .question-section -->
