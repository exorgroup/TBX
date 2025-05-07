<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

class MyCrudController extends CrudController
{
    protected $actionsColumnWidth = null;
    protected $actionsHeaderAlign = null;
    protected $actionsCellAlign = null;

    /**
     * Set the width of the actions column
     * 
     * @param string $width The width of the column (e.g. '150px', '10%')
     * @return $this
     */
    public function setActionsColumnWidth($width)
    {
        $this->crud->set('actionsColumnWidth', $width);

        return $this;
    }

    /**
     * Set the alignment of the actions column header
     * 
     * @param string $align The alignment ('left', 'center', 'right')
     * @return $this
     */
    public function setActionsHeaderAlign($align)
    {
        $this->crud->set('actionsHeaderAlign', $align);

        return $this;
    }

    /**
     * Set the alignment of the action buttons in the cell
     * 
     * @param string $align The alignment ('left', 'center', 'right')
     * @return $this
     */
    public function setActionsCellAlign($align)
    {
        $this->crud->set('actionsCellAlign', $align);

        return $this;
    }
}
