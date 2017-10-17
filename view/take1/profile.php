<div class="profile-page">

<?php

    $title = $data['title'];
    $email = $data['email'];
    $acronym = $data['acronym'];
    $role = $data['role'];

if (isset($data['gravatar'])) {
    $gravatar = $data['gravatar'];
    echo "<img class='gravatar float-right' src='$gravatar' alt='Gravatar'>";
}
?>

<h1><?=$title?></h1>

<p>Välkommen <?=$acronym?>. Detta är din profil.</p>
<p>Här kan du ändra ditt användarnamn eller lösenord,
gravatar och e-postadress.</p>
<p>Om du har skapat en <a href="http://gravatar.com">gravatar</a> så genereras den från din e-postadress.</p>

<?php $route = $this->url("comment/delete");
$edit_users = $this->url("user/edit-all");
?>

<p><a href="<?=$route;?>">Ta bort kommentarer</a></p>

<?php if (isset($role) && $role == 10) : ?>
<p><a href="<?=$edit_users;?>">Administratör : Redigera användare</a></p>
<?php endif; ?>

</div>
