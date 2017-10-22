<?php
$route = $this->url("question/edit");
$questionid = $question->id;
?>
<div class="edit-button">
    <a href="<?=$route . "/" . $questionid; ?>">Redigera fråga</a>
</div>
