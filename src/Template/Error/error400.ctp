<?php
use Cake\Core\Configure;

/*if (Configure::read('debug')):
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error400.ctp');

    $this->start('file');
?>
<?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
<?php if (!empty($error->params)) : ?>
        <strong>SQL Query Params: </strong>
        <?= Debugger::dump($error->params) ?>
<?php endif; ?>
<?= $this->element('auto_table_warning') ?>
<?php
    if (extension_loaded('xdebug')):
        xdebug_print_function_stack();
    endif;

    $this->end();
endif;*/
?>
<h4><?= h($message) ?></h4>
<!--p class="error">
    <strong><?= __d('cake', 'Error') ?>: </strong>
    <?= sprintf(
        __d('cake', 'The requested address %s was not found on this server.'),
        "<strong>'{$url}'</strong>"
    ) ?>
</p-->
<p>You have probably got to this page because your session has timed out, or because you tried to access the iCase directly, rather than coming through WebLearn.</p>
<p>To access the iCase, please go back to the <a href="https://weblearn.ox.ac.uk/x/GNY01y">Viral Outbreak page</a> on the <a href="https://weblearn.ox.ac.uk/portal/hierarchy/medsci/med/1bm2">1st BM Part II WebLearn page</a>.</p> 
<p>If you do that, and still end up back here, or have any other problems accessing or using the Viral Oubtreak iCase, please <a href="mailto:msdlt@medsci.ox.ac.uk">contact MSDLT (msdlt@medsci.ox.ac.uk)</a>.</p>