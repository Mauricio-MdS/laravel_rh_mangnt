<?php

use App\Models\Department;
use App\Models\User;

it('tests if an admin can insert a new RH user', function () {
    addAdminUser();
    addDepartment('Administração');
    addDepartment('Recursos Humanos');

    $result = $this->post('/login', [
        'email' => 'admin@rhmangnt.com',
        'password' => 'Aa123456',
    ]);

    expect($result->status())->toBe(302);
    expect($result->assertRedirect('/home'));

    $result = $this->post('/rh-users/create-colaborator', [
        'name' => 'RH user 1',
        'email' => 'rhuser@gmail.com',
        'select_department' => '2',
        'address' => 'Rua 1',
        'zip_code' => '1234-123',
        'city' => '1234-City 1',
        'phone' => '123456789',
        'salary' => '1000.00',
        'admission_date' => '2021-01-10',
        'role' => 'rh',
        'permissions' => '["rh"]',
    ]);

    $this->assertDatabaseHas('users', [
        'name' => 'RH user 1',
        'email' => 'rhuser@gmail.com',
        'role' => 'rh',
        'permissions' => '["rh"]',
    ]);
});

it('tests if a RH user can insert a new colaborator user', function () {

    addRhUser();
    addDepartment('Administração');
    addDepartment('Recursos Humanos');
    addDepartment('Armazem');

    $result = $this->post('/login', [
        'email' => 'rh1@rhmangnt.com',
        'password' => 'Aa123456',
    ]);

    expect(auth()->user()->role)->toBe('rh');

    $this->post('/rh-users/management/create-colaborator', [
        'name' => 'Colaborator 1',
        'email' => 'colaborator1@gmail.com',
        'select_department' => 3,
        'address' => 'Rua 2',
        'zip_code' => '1234-000',
        'city' => '1234-City 2',
        'phone' => '123456789',
        'salary' => '1000.00',
        'admission_date' => '2021-01-10',
        'role' => 'colaborator',
        'permissions' => '["colaborator"]',
    ]);

    // $this->assertDatabaseHas('users', [
    //     'email' => 'colaborator1@gmail.com',
    // ]);

    expect(User::where('email', 'colaborator1@gmail.com')->exists())->toBeTrue();
});

function addDepartment($name)
{
    Department::insert([
        'name' => $name,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

