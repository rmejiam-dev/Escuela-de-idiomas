<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('validate_rut', function ($attribute, $value, $parameters, $validator) {
            // Limpiar el RUT
            $rut = preg_replace('/[^k0-9]/i', '', $value);
            $rut = strtoupper($rut);

            if (strlen($rut) < 2) {
                return false;
            }

            $numero = substr($rut, 0, -1);
            $dv_ingresado = substr($rut, -1);

            if (!is_numeric($numero)) {
                return false;
            }

            $suma = 0;
            $multiplo = 2;

            for ($i = strlen($numero) - 1; $i >= 0; $i--) {
                $suma += $numero[$i] * $multiplo;
                $multiplo = $multiplo == 7 ? 2 : $multiplo + 1;
            }

            $dv_esperado = 11 - ($suma % 11);
            if ($dv_esperado == 11) $dv_esperado = 0;
            if ($dv_esperado == 10) $dv_esperado = 'K';

            return $dv_esperado == $dv_ingresado;
        });
    }
}