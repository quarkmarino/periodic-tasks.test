<?php

namespace App\Presenter\Contracts;

use App\Presenter\Presenter;

interface Presentable
{
    public function present(): Presenter;

    public function presentAs(Presenter $presenter): Presenter;
}
