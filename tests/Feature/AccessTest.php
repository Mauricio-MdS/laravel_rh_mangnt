<?php

it('tests if an admin user can see the RH users page', function () {
    addAdminUser();
    auth()->loginUsingId(1);
    expect($this->get('/rh-users')->status())->toBe(200);
});

it('tests if is not possible to access the home page without being logged in', function () {
    expect($this->get('/home')->status())->toBe(302);
});

it('tests if user logged in can access the login page', function () {
    addAdminUser();
    auth()->loginUsingId(1);
    expect($this->get('/login')->status())->not()->toBe(200);
});
