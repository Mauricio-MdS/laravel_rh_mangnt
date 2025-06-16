<?php

use App\Models\User;



it('display the login page when not logged in', function () {

    $result = $this->get('/')->assertRedirect('/login');
    expect($result->status())->toBe(302);
    expect($this->get('/login')->status())->toBe(200);
    expect($this->get('/login')->content())->toContain('Esqueceu a sua senha?');
});

it('display the recover password page correctly', function () {
    expect($this->get('/forgot-password')->status())->toBe(200);
    expect($this->get('/forgot-password')->content())->toContain('Já sei a minha senha?');
});

it('test if an admin user can login with success', function () {

    addAdminUser();

    $result = $this->post('/login', [
        'email' => 'admin@rhmangnt.com',
        'password' => 'Aa123456',
    ]);

    expect($result->status())->toBe(302);
    expect($result->assertRedirect('/home'));
});

it('test if a rh user can login with success', function () {
    addRhUser();
    $result = $this->post('/login', [
        'email' => 'rh1@rhmangnt.com',
        'password' => 'Aa123456',
    ]);

    expect($result->status())->toBe(302);
    expect($result->assertRedirect('/home'));
    expect($this->get('/rh-users/management/home')->status())->toBe(200);
});

function addAdminUser()
{
    User::insert([
        'department_id' => 1,   // Administração
        'name' => 'Administrador',
        'email' => 'admin@rhmangnt.com',
        'email_verified_at' => now(),
        'password' => bcrypt('Aa123456'),
        'role' => 'admin',
        'permissions' => '["admin"]',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

function addRhUser()
{
    User::insert([
        'department_id' => 2,
        'name' => 'Colaborador de RH',
        'email' => 'rh1@rhmangnt.com',
        'email_verified_at' => now(),
        'password' => bcrypt('Aa123456'),
        'role' => 'rh',
        'permissions' => '["rh"]',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
