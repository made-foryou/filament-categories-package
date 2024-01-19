<?php

use MadeForYou\Categories\Resources\CategoryResource\RelationManagers\ChildrenRelationManager;

return [

    /**
     * The relationship managers that the category resource will
     * use to present the category's relationships in Filament.
     *
     * @param array<class-string>
     */
    'category_relations' => [],

    'database' => [
        'prefix' => 'made',
    ],

];
