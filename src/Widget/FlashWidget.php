<?php

namespace Tracker\Widget;

class FlashWidget extends AbstractWidget {

    public function getContent() {
        return $this->render('widget/flash.twig');
    }

}
