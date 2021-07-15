<?php

namespace App;

trait RedirectMain
{
    public function redirectToIndex(){

        return $this->redirect($this->generateUrl('index'));
        
    }
}