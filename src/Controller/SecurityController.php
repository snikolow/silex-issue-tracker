<?php

namespace Tracker\Controller;

use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseController {

    public function authAction(Request $request) {
        $lastError = $this->get('security.last_error');

        return $this->render('security/login.twig',
            array(
                'error'         => $lastError($request),
                'last_username' => $this->get('session')->get('_security.last_username')
            )
        );
    }

}
