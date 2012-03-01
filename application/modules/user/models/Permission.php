<?php

class User_Model_Permission
{
    /**
     * Get the permissions form
     * 
     * @param void
     * 
     * @return User_Model_Permissions
     */
    public function getPermissionsForm()
    {
        $form = new User_Form_Permissions();
        $form->setRowHeader(array('One', 'Two', 'Three', 'Four', 'Five'));
        
        for($i = 1; $i < 5; $i++) {
            $form->addRow(
                'Test ' . $i,
                $i,
                array(1, 2, 3, 4, 5)
            );
        }
        
        return $form;
    }

}

