<?php
    $this->registerJs("
        window.parent.$('.modal').modal('hide');
        window.parent.$.pjax.reload('#product-modifications');
    ");
?>