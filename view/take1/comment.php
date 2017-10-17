<div class="comment">
<?php
    //if (isset($app->session->))
    // $path = $app->request->getRoute();
    // $current_comments = 'comments' . $path;
    // $comments = $app->session->get($current_comments);

    $comments = $data['comments'];
    $user = $data['user'];
    $role = $data['role'];


?>

<table class="table-bordered">

<?php

$i = 0;
if (isset($comments)) :
    if (!empty($comments)) :
        foreach ($comments as $comment) :
            // use htmlentites and then textfilter markdown
            $textfilter = $this->di->get("textfilter");
            $acronym = htmlentities($comment->acronym);
            $commenttext = htmlentities($comment->commenttext);
            $commenttext = $textfilter->parse($commenttext, ["markdown"]);
?>
    <tr><td>

    </td></tr>

        <tr><td>
        Kommentar: <?=$commenttext->text;?>
        </td></tr>

        <tr><td>
        Akronym: <?=$acronym;?>
        </td>
        <?php //$id = $comment['id']; ?>
    </tr>

    <?php if ($user == $acronym || $role == 10) : ?>
    <tr>
    <td><a href="comment/edit/<?=$comment->page . '/' . $comment->id;?>">Redigera</a></td>
    </tr>

<?php     endif;

        $i++;
        endforeach;
    endif;
endif; ?>
        <?php //$this->app->session->destroy(); ?>
        <?php
        //echo '<pre>' . var_export($_SESSION, true) . '</pre>';?>

</table>


<?php

$route = $this->di->request->getRoute();
// $validRoutes = $this->di->commentRoutes("config");
$commentableRoutes = $data['commentableRoutes'];

if ($route == "") {
    $route = "index";
}

if (in_array($route, $commentableRoutes)) {
    $previous = $this->di->request->getRoute();
    if ($previous == "") {
        $previous = "index";
    }
    // $previous = $this->di->get("route")->getRoute();
    $val = $this->url("user/login");
    $comment = $this->url("comment/create");
    $this->di->session->set("previous", $previous);

    $output = isset($user) ? "<a href='$comment'>Kommentera</a> som användare '" . $user . "':": "<a href='$val'>Logga in</a> för att kommentera";

    echo "<p>" . $output . "</p>";
    $val = $this->url("user/create");
    echo "<p><a href='$val'>Registrera ny användare</a></p>";
}
?>

    <!-- <form class="comment" action="comment/post" method="post">

        <div>
        <textarea name="text" rows="4" cols="30"></textarea>
    </div>
    <div>
        <label for="email">E-mail:</label>
        <input type="text" name="email" value="">
    </div>
    <div>
        <label for="gravatar">Gravatar:</label>
        <input type="text" name="gravatar" value="">
    </div>
        <input type="hidden" name="" value="">
        <button type="submit" name="button">Skicka</button>
    </form> -->

</div>
