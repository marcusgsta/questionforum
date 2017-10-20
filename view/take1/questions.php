<div class="questionssection">
<?php

if ($this->di->get("loginController")->anyLoggedin() == false) {
    $val = $this->url("user/login");
    echo "<a href='$val'>Logga in</a>";

    $val = $this->url("user/create");
    echo "<p><a href='$val'>Registrera ny användare</a></p>";
};
?>

<h2>De senaste frågorna</h2>

<?php $url = $this->url("question/create"); ?>
<div id="questionbutton" class="float-right">
<a href="<?=$url?>" class="btn btn-primary">Ställ en fråga</a>
</div>

<?php

$questions = $data;

foreach ($questions as $question) : ?>

<div class="question-wrap clear">
<?php
$id = $question->id;
$url = $this->url("question/show/$id"); ?>



    <div class="question">

        <p><a href="<?=$url?>"><?=$question->questiontitle->text;?></a></p>

        <p><?=$question->excerpt;?> [ . . . . . . ]</p>
    </div>

    <!-- <div class="share">
        <a href="#">Share</a>
    </div> -->
    <div class="stats">
        <span class="badge badge-primary"> <?=$question->votesum?></span>
        <span class="badge badge-info"><?=$question->answerCount?> svar</span>
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


<?php endforeach; ?>
</div>
