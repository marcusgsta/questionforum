<div class="card card-outline-info mb-3 text-center float-right">
  <div class="card-block">
    <blockquote class="card-blockquote">

        <div class="created">
            <span>Frågat <?=$created;?></span>
        </div>
<?php if (isset($updated)) : ?>
        <div class="updated">
        <span>Redigerat <?=$updated;?></span>
        </div>
<?php endif; ?>
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
