<?php

it('tests if an admin user can see the RH users page', function () {
    addAdminUser();
    auth()->loginUsingId(1);
    expect($this->get('/rh-users')->status())->toBe(200);
});
