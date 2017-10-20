<div class="vote-wrap">
    <div class="vote-sum float-left">
        <!-- <button type="button" class="btn btn-primary btn-sm float-left" aria-label="Left Align"> -->
          <span class="badge badge-info" aria-hidden="true"><?=$comment->votesum;?></span>
        <!-- </button> -->
    </div>


<?php
$route = $this->url("comment/vote/$comment->id");

switch ($this->di->get("loginController")->anyLoggedin() == false || $comment->userHasVoted) :
    case true:
        echo "<div class='vote float-left'>

            <button type='button' class='btn btn-outline-primary btn-sm float-left' disabled aria-label='Left Align'>
                <span class='glyphicon glyphicon-menu-up' aria-hidden='true'>↑</span>
            </button>



            <button type='button' class='btn btn-outline-primary btn-sm float-left' disabled>
                <span class='glyphicon glyphicon-menu-down' aria-hidden='true'>↓</span>
            </button>

            </div>";
        break;
    case false:
        echo "<div class='vote float-left'>
<!-- <a href='#'>Share</a> -->
<a href='" . $route . "/1'>
<button type='button' class='btn btn-outline-primary btn-sm float-left' aria-label='Left Align'>
    <span class='glyphicon glyphicon-menu-up' aria-hidden='true'>↑</span>
</button>
</a>

<a href='" . $route . "/0'>
<button type='button' class='btn btn-outline-primary btn-sm float-left'>
    <span class='glyphicon glyphicon-menu-down' aria-hidden='true'>↓</span>
</button>
</a>
</div>";
        break;
endswitch;?>


</div> <!-- end of .vote-wrap -->
