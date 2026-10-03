<?php
namespace App\Support;

/** Convierte importes a letras para la leyenda "SON: ... CON xx/100 SOLES". */
class NumeroALetras
{
    private const U = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE',
        'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE',
        'VEINTE', 'VEINTIUNO', 'VEINTIDOS', 'VEINTITRES', 'VEINTICUATRO', 'VEINTICINCO', 'VEINTISEIS',
        'VEINTISIETE', 'VEINTIOCHO', 'VEINTINUEVE'];

    private const D = [3 => 'TREINTA', 4 => 'CUARENTA', 5 => 'CINCUENTA', 6 => 'SESENTA',
        7 => 'SETENTA', 8 => 'OCHENTA', 9 => 'NOVENTA'];

    private const C = [1 => 'CIENTO', 2 => 'DOSCIENTOS', 3 => 'TRESCIENTOS', 4 => 'CUATROCIENTOS',
        5 => 'QUINIENTOS', 6 => 'SEISCIENTOS', 7 => 'SETECIENTOS', 8 => 'OCHOCIENTOS', 9 => 'NOVECIENTOS'];

    private static function menorMil(int $n): string
    {
        if ($n === 100) {
            return 'CIEN';
        }
        $c = intdiv($n, 100);
        $r = $n % 100;
        $texto = $c ? self::C[$c] : '';

        if ($r) {
            $texto .= $texto ? ' ' : '';
            if ($r < 30) {
                $texto .= self::U[$r];
            } else {
                $u = $r % 10;
                $texto .= self::D[intdiv($r, 10)] . ($u ? ' Y ' . self::U[$u] : '');
            }
        }
        return $texto;
    }

    /** "UNO" -> "UN" cuando va antes de MIL / MILLÓN (ej. VEINTIUN MIL). */
    private static function apocopar(string $texto): string
    {
        return preg_replace('/UNO$/', 'UN', $texto);
    }

    public static function entero(int $n): string
    {
        if ($n === 0) {
            return 'CERO';
        }
        $partes = [];
        $millones = intdiv($n, 1000000);
        $miles = intdiv($n % 1000000, 1000);
        $resto = $n % 1000;

        if ($millones) {
            $partes[] = $millones === 1 ? 'UN MILLON' : self::apocopar(self::menorMil($millones)) . ' MILLONES';
        }
        if ($miles) {
            $partes[] = $miles === 1 ? 'MIL' : self::apocopar(self::menorMil($miles)) . ' MIL';
        }
        if ($resto) {
            $partes[] = self::menorMil($resto);
        }
        return implode(' ', $partes);
    }

    public static function soles(float $monto): string
    {
        $centimos = (int) round($monto * 100);
        $enteros = intdiv($centimos, 100);
        $cent = $centimos % 100;

        return 'SON: ' . self::entero($enteros) . ' CON ' . str_pad((string) $cent, 2, '0', STR_PAD_LEFT) . '/100 SOLES';
    }
}
