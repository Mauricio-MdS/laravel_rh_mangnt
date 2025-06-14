<?php

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
