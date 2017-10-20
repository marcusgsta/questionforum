<div class="public-profile">

<?php

$userObject = $data[0];
$acronym = $userObject->acronym;
$questions = $userObject->questions;
$answeredQuestions = $userObject->answeredQuestions;
$answers = $userObject->answers;
$comments = $userObject->comments;
$userRank = $userObject->rank;
$votesMade = $userObject->votesMade;

$title = $acronym;

if (isset($userObject->gravatar)) {
    $gravatar = $userObject->gravatar;
    echo "<img class='gravatar float-right' src='$gravatar' alt='Gravatar'>";
}
?>

<h2><?=$title?><span id="user-rank" class="badge badge-pill badge-primary">Rank: <?=$userRank?></span></h2>

<p class="votesMade">Antal röstningar gjorda: <?=$votesMade;?></p>
<p class="clear">Frågor ställda av <?=$acronym?>:</p>
<ul>
<?php foreach ($questions as $question) :
    $questionid = $question->id;
    $route = $this->url("question/show/$questionid"); ?>
    <li><a href='<?=$route?>'><?=$question->questiontitle->text;?></a></li>
<?php endforeach; ?>
</ul>
<p>Frågor besvarade av <?=$acronym?>:</p>
<ul>
<?php foreach ($answeredQuestions as $question) :
    $questionid = $question->id;
    $route = $this->url("question/show/$questionid");
    // var_dump($question);
    // exit;
?>
    <li><a href='<?=$route?>'><?=$question->questiontitle;?></a></li>
<?php endforeach; ?>
</ul>

<p>Svar av <?=$acronym?>:</p>
<ul>
<?php foreach ($answers as $answer) :
    $answerid = $answer->id;
    $questionid = $answer->questionid;
    $route = $this->url("question/show/$questionid");
    $route = $route . "#answer-" . $answerid;
?>
    <li><a href='<?=$route?>'><?=$answer->answertitle->text;?></a></li>
<?php endforeach; ?>
</ul>

<p><?=$acronym?>s kommentarer:</p>
<ul>
<?php foreach ($comments as $comment) :
    $questionid = $comment->questionid;
    $route = $this->url("question/show/$questionid");
    $route = $route . "#comment-" . $comment->id;
?>
    <li><a href='<?=$route?>'><?=$comment->excerpt;?></a></li>
<?php endforeach; ?>
</ul>
</div>
