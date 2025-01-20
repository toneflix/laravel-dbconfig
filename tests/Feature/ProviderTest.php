<?php

test('can publish config', function () {
    $this->artisan('vendor:publish --tag="dbconfig"')
        ->assertExitCode(0);
});
