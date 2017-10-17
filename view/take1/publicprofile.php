<div class="public-profile">

<?php

$userObject = $data[0];
$acronym = $userObject->acronym;
$questions = $userObject->questions;
$answeredQuestions = $userObject->answeredQuestions;
$title = $acronym . " – offentlig profil";

if (isset($userObject->gravatar)) {
    $gravatar = $userObject->gravatar;
    echo "<img class='gravatar float-right' src='$gravatar' alt='Gravatar'>";
}
?>

<h1><?=$title?></h1>
<p>Frågor ställda av <?=$acronym?>:</p>
<ul>
<?php foreach ($questions as $question) :
    $questionid = $question->id;
    $route = $this->url("question/show/$questionid"); ?>
    <li><a href='<?=$route?>'><?=$question->questiontitle;?></a></li>
<?php endforeach; ?>
</ul>
<p>Frågor besvarade av <?=$acronym?>:</p>
<ul>
<?php foreach ($answeredQuestions as $question) :
    $questionid = $question->id;
    $route = $this->url("question/show/$questionid"); ?>
    <li><a href='<?=$route?>'><?=$question->questiontitle;?></a></li>
<?php endforeach; ?>
</ul>

</div>
