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

        <div class="vote-wrap">
            <div class="vote-sum float-left">
                <!-- <button type="button" class="btn btn-primary btn-sm float-left" aria-label="Left Align"> -->
                  <span class="badge badge-info" aria-hidden="true"><?=$question->votesum;?></span>
                <!-- </button> -->
            </div>


<?php
$route = $this->url("question/vote/$question->id");

switch ($this->di->get("loginController")->anyLoggedin() == false || $question->userHasVoted) :
    case true:
        echo '<div class="vote float-left">
                    <button type="button" class="btn btn-primary btn-sm float-left" disabled aria-label="Left Align">
                        <span class="glyphicon glyphicon-menu-up" aria-hidden="true">↑</span>
                    </button>
                <br>
                    <button type="button" class="btn btn-primary btn-sm" disabled>
                        <span class="glyphicon glyphicon-menu-down" aria-hidden="true">↓</span>
                    </button>
            </div>';
        break;
    case false:
        echo "<div class='vote float-left'>
    <!-- <a href='#'>Share</a> -->
    <a href='" . $route . "/1" . "'>
        <button type='button' class='btn btn-primary btn-sm float-left' aria-label='Left Align'>
            <span class='glyphicon glyphicon-menu-up' aria-hidden='true'>↑</span>
        </button>
    </a>
    <br>
    <a href='" . $route . "/0" . "'>
        <button type='button' class='btn btn-primary btn-sm'>
            <span class='glyphicon glyphicon-menu-down' aria-hidden='true'>↓</span>
        </button>
    </a>
</div>";
        break;
endswitch;?>


        </div> <!-- end of .vote-wrap -->
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
    echo "<div class='comment-form-question clear'>" . $commentFormQuestion . "</div>";
} else {
    $route = $this->url("user/login");
    echo "<p><a href='$route'>Logga in för att kommentera.</a></p>";
}?>

<!-- ANSWERS SECTION -->
<?php include("answers.php");?>

</div> <!-- end of .question-section -->
