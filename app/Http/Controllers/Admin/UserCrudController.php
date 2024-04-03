<?php

namespace App\Http\Controllers\Admin;

use Hash;
use function PHPSTORM_META\map;
use App\Http\Requests\UserRequest;

use Backpack\CRUD\Tests\Config\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('email');
        CRUD::column('name');
        CRUD::column('photo');

    }

    protected function setupCreateOperation()
    {
        CRUD::field('name')->validationRules('required|min:5');
        CRUD::field('photo')->type('upload')->withFiles([
            'disk' => 'public',
            'path' => 'uploads',
        ]);
        CRUD::field('email')->validationRules('required|email|unique:users,email');
        CRUD::field('password')->validationRules('required');
         \App\Models\User::creating(function ($entry) {
            $entry->password = Hash::make($entry->password);
        });
    }

    protected function setupUpdateOperation()
    {
       $this->setupCreateOperation();
    }
}
