<div class="users">

<?php

$userObjects = $data;

$title = "Alla användare";

?>

<h1><?=$title?></h1>

<div class="users-wrap">
<?php
foreach ($userObjects as $user) :
    $userid = $user->id;
    $route = $this->url("user/show/$userid"); ?>
<div class="user-wrap clearfix">
    <a href='<?=$route?>' class='acronym float-right'><?=$user->acronym;?></a>
<?php
if (isset($user->gravatar)) {
    $gravatar = $user->gravatar;
    echo "<div class='gravatar float-left'><img src='$gravatar' alt='Gravatar'></div>";
}
?>
</div> <!-- end of .user-wrap -->
<?php endforeach; ?>

</div> <!-- end of .users-wrap -->
</div> <!-- end of .users -->
