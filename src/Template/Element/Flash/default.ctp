<?php
$class = 'alert alert-info';
/*if (!empty($params['class'])) {
    $class .= ' alert-' . $params['class'];
}*/
?>
<div class="<?= h($class) ?>"><?= h($message) ?></div>
