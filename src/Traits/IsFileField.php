<?php

namespace LVP\Traits;

use InvalidArgumentException;

trait IsFileField
{
    protected static function convertToBytes($sizeStr)
    {
        // Convertir la chaîne en minuscule et supprimer les espaces blancs
        $sizeStr = strtolower(trim($sizeStr));

        // Analyser la chaîne pour extraire la valeur et l'unité
        if (preg_match('/^(\d+)(kb|mb|gb)$/', $sizeStr, $matches)) {
            $value = (int) $matches[1];
            $unit = $matches[2];
            // Convertir la valeur en octets selon l'unité
            switch ($unit) {
                case 'kb':
                    return $value;
                case 'mb':
                    return $value * 1024;
                case 'gb':
                    return $value * 1024 * 1024;
                default:
                    return $value;
            }
        }

        throw new \ErrorException("Invalid file size: $sizeStr");
    }


    public function maxSize(string $size = '5MB')
    {

        $size = self::convertToBytes($size);
        // dd($size);
        $this->_rules[] = 'max:' . $size;
        $this->_max_file_size = $size;
        return $this;
    }
    public function multiple(bool $multiple)
    {
        $this->_multiple = $multiple;
        return $this;
    }
}
