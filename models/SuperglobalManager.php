<?php

namespace Models;



class SuperglobalManager
{
    public function __toString()
    {
        return $this->sessionError;
    }

    public function sessionError($id)
    {
        switch ($id):
                //MANAGE COMMENT ERROR
            case '1':
                return $this->sessionError = 'Your comment was add.';

            case '2':
                return $this->sessionError = 'Your comment was identique or invalid. ';

            case '3':
                return $this->sessionError = 'Your comment was delete.';

            case '4':
                return $this->sessionError = 'Your comment must first be validated by the administrator before being visible. 
                    Find all your comments awaiting validation <a href="index.php?action=userComments">here</a>.';

                //MANAGE POST ERROR
            case '5':
                return $this->sessionError = 'Your post was add.';

            case '6':
                return $this->sessionError = 'Your post was identique or invalid.';

            case '7':
                return $this->sessionError = 'Your post was delete.';

            case '8':
                return $this->sessionError = 'Your post was modify.';

                //MANAGE USER ERROR
            case '9':
                return $this->sessionError = 'Your details have been saved.';

            case '10':
                return $this->sessionError = 'You are connected';

        endswitch;
    }
}
