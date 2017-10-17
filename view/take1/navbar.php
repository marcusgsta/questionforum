<!-- <navbar class="navbar"> -->

<?php

$items = $data;

$page = basename($_SERVER['REQUEST_URI']);
$values = $items['items'];
$navbarClass = $items['config']['navbar-class'];
?>

<nav class='<?=$navbarClass?>' style='background-color: #e3f2fd;'>
    <a class='navbar-brand' href="<?=$this->url('')?>">Anax - ramverk med moduler</a>
        <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent'aria-expanded='false' aria-label='Toggle navigation'>
            <span class='navbar-toggler-icon'></span>
        </button>
  <div class='navbar-collapse collapse' id='navbarSupportedContent'>

    <ul class='navbar-nav mr-auto'>

<?php
foreach ($values as $value) :
    $val = $this->url($value['route']);

    if ($value['route'] == "") {
        $value['route'] = "htdocs";
    }

    if ($page == $value['route']) {
        $select = "selected nav-item active";
    } else {
        $select = "nav-item";
    }
    $text = $value['text'];

?>


    <li class="<?=$select?>">
    <a class="nav-link" href="<?=$val?>"><?=$text?></a></li>

<?php
endforeach;
?>

 </ul></div></nav>
