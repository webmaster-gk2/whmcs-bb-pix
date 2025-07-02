<?php

use WHMCS\Database\Capsule;

function lknbbpix_create_custom_fields_select(): array
{
    $fields = Capsule::table('tblcustomfields')
        ->where('type', 'client')
        ->get(['id', 'fieldname']);

    $selectData = ['' => 'Selecionar opção'];

    foreach ($fields as $field) {
        $selectData[$field->id] = $field->fieldname;
    }

    return $selectData;
}
