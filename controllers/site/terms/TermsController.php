<?php

/**
 *
 * Controller do site.
 *
 * @author Cristina Stanck
 *
 **/
class TermsController extends Controller
{

    public function index()
    {
        $this->setLayout(
            'site/shared/layout.php',
            'Termos de Uso - C&G',
            array(
                'assets/libs/fontawesome-6.0/css/all.min.css',
                'assets/css/site/terms/style.css'
            ),
            array()
        );
        $this->view('site/terms/index.php', array(
        ));
    }
    public function condut()
    {

        $this->setLayout(
            'site/shared/layout.php',
            'Termos de Uso - C&G',
            array(
                'assets/libs/fontawesome-6.0/css/all.min.css',
                'assets/css/site/terms/style.css'
            ),
            array()
        );
        $this->view('site/terms/condut.php', array(
        ));
    }
}
