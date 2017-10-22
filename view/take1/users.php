
<div class="users">


<?php

$userObjects = $data;

$title = "Alla användare";

?>

<h1><?=$title?></h1>


<?php
foreach ($userObjects as $user) :
    $userid = $user->id;
    $route = $this->url("user/show/$userid"); ?>

    <div class="card card-outline-info mb-3 text-center clearfix">
      <div class="card-block">
        <blockquote class="card-blockquote">

            <div class="gravatar float-left">
            <img src="<?=$user->gravatar;?>">
            </div>
            <div class="acronym">
                <?php
                $userid = $user->id;
                $route = $this->url("user/show/$userid");?>
                <span><a href="<?=$route?>"><?=$user->acronym;?></a></span>
            </div>

        </blockquote>

      </div>
    </div>




<?php endforeach; ?>
</div>
