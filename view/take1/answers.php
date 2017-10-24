<div class="answer-wrap clear" id="answer-wrap">

<div class="sort-answers float-right">
    <div class="btn-group" role="group" aria-label="Basic example">

  <!-- <button type="button" class="btn btn-secondary">Middle</button>
  <button type="button" class="btn btn-secondary">Right</button> -->

<?php
$page = $this->di->get("request")->getRoute();
$page = $this->url($page);

$questionid = $question->id;
$route = $this->url("question/show/$questionid/sortedvotes"); ?>

    <a href="<?=$route . '#answer-wrap'?>"><button type="button" class="btn btn-secondary" <?=$dis = $page == $route ? "disabled" : "";?>>Populäraste</button></a>
<?php
$route = $this->url("question/show/$questionid/sortedoldest"); ?>
    <a href="<?=$route . '#answer-wrap'?>"><button type="button" class="btn btn-secondary" <?=$dis = $page == $route ? "disabled" : "";?>>Äldsta</button></a>
<?php
$route = $this->url("question/show/$questionid/sortednewest"); ?>
    <a href="<?=$route . '#answer-wrap'?>"><button type="button" class="btn btn-secondary" <?=$dis = $page == $route ? "disabled" : "";?>>Nyaste</button></a>

    </div> <!-- end of .btn-group -->
</div> <!-- end of .sort-answers -->

<div id="answers" class="clear">

<?php foreach ($answers as $answer) :
    if ($answer->accepted) : ?>
    <div class="answer accepted-bg clear" id="answer-<?=$answer->id?>">
<?php else : ?>
    <div class="answer clear" id="answer-<?=$answer->id?>">
<?php endif; ?>

        <h2><?=$answer->answertitle->text;?></h2>
        <p><?=$answer->answertext->text;?></p>

            <!-- VOTE SECTION -->

        <div class="vote-wrap">
            <div class="vote-sum float-left">
                <!-- <button type="button" class="btn btn-primary btn-sm float-left" aria-label="Left Align"> -->
                  <span class="badge badge-info" aria-hidden="true"><?=$answer->votesum;?></span>
                <!-- </button> -->
            </div>


<?php
$route = $this->url("answer/vote/$answer->id");

switch ($this->di->get("loginController")->anyLoggedin() == false || $answer->userHasVoted) :
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
    <a href='" . $route . "/1'>
        <button type='button' class='btn btn-primary btn-sm float-left' aria-label='Left Align'>
            <span class='glyphicon glyphicon-menu-up' aria-hidden='true'>↑</span>
        </button>
    </a>
    <br>
    <a href='" . $route . "/0'>
        <button type='button' class='btn btn-primary btn-sm'>
            <span class='glyphicon glyphicon-menu-down' aria-hidden='true'>↓</span>
        </button>
    </a>
</div>";

        break;
endswitch;?>


        </div> <!-- end of .vote-wrap -->


        <div class="accepted">
<?php

if ($answer->accepted) {
    echo "<span class='badge badge-success'>ACCEPTERAT SVAR</span>";
} else if ($answer->acceptButton == true && $question->hasAccepted == false) {
    $answerid = $answer->id;
    $route = $this->url("question/accept/$answerid");
    echo "<a class='btn btn-primary' href='$route'>Märk svar som accepterat</a>";
};
?>
        </div>
<?php
$user = $answer->user;
$created = $answer->created;
$updated = $answer->updated;
include("userbadge.php"); ?>

    </div> <!-- end of .answer -->

    <!-- COMMENTS FOR EACH ANSWER -->
    <div class="comments-answer">
<?php $commentsAnswer = $answer->commentsAnswer;?>

    <?php foreach ($commentsAnswer as $comment) : ?>
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
</div> <!-- end of .comments-answer -->

    <!-- COMMENT FORM ANSWER (ONE FOR EVERY ANSWER)-->
<?php $answerid = $answer->id; ?>
    <a class="btn btn-primary comment-button float-right" data-toggle="collapse" href="#comment-form-answer-<?=$answerid;?>" aria-expanded="false" aria-controls="collapseExample">
      Ge kommentar
    </a>
<?php

if ($this->di->get("loginController")->anyLoggedin() == true) {
    echo "<div class='comment-form-answer collapse clear' id='comment-form-answer-$answerid'>" . $answer->commentForm . "</div>";
} else {
    $route = $this->url("user/login");
    echo "<p><a href='$route'>Logga in för att kommentera.</a></p>";
}?>

<?php endforeach;
if ($this->di->get("loginController")->anyLoggedin() == true) {
    echo "<div class='answer-section clear'>" . $answerForm . "</div>";
} else {
    $route = $this->url("user/login");
    echo "<a href='$route'>Logga in för att svara.</a>";
}?>
</div> <!-- end of #answers -->
</div> <!-- end of .answer-wrap -->
