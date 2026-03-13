<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extends(Tests\TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

pest()->extends(Tests\TestCase::class)
    ->in('Unit');
