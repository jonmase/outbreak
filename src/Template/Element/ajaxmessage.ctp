<?php //Wrap message up as object so angular $resource does not return string split into arrays ?>
{"message": "<?= $message ?>"}