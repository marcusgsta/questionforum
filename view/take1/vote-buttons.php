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
