<?php

namespace LVP\Widgets\FormWidget\Fields;

class FileUploadFieldWidget extends FormFieldWidget
{
    protected string $_component = 'file';
    protected string $_accept = '*';
    protected string $_max_size = '5M';
    protected bool $_multiple = false;

    public function multiple(bool $multiple = true)
    {
        $this->_multiple = $multiple;
        return $this;
    }

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


    public function accept(string $accept = '*')
    {
        $this->_accept = $accept;
        $this->_rules[] = 'mimetypes:' . $accept;
        return $this;
    }

    public function maxSize(string $size = '5MB')
    {

        $size = self::convertToBytes($size);
        $this->_rules[] = 'max:' . $size;
        $this->_max_size = $size;
        return $this;
    }

    protected function beforeRender(array $data): array
    {
        $data['accept'] = $this->_accept;
        $data['maxSize'] = $this->_max_size;
        $data['multiple'] = $this->_multiple;
        return $data;
    }
}