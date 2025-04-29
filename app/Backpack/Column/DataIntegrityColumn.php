<?php

namespace App\Backpack\Columns;

use Backpack\CRUD\app\Library\CrudPanel\CrudColumn;

class DataIntegrityColumn extends CrudColumn
{
    protected $viewNamespace = 'columns';

    public function getViewWithNamespace()
    {
        return 'columns.data_integrity';
    }
}