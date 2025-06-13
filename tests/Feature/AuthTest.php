<?php

it('display the login page when not logged in', function () {

    $result = $this->get('/')->assertRedirect('/login');
    expect($result->status())->toBe(302);
    expect($this->get('/login')->status())->toBe(200);
    expect($this->get('/login')->content())->toContain('Esqueceu a sua senha?');
});
