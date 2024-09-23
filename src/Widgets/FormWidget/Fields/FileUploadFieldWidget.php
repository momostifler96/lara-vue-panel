<?php

namespace LVP\Widgets\FormWidget\Fields;
use LVP\Widgets\FormWidget\FormWidget;

class FileUploadFieldWidget extends FormFieldWidget
{
    protected string $_component = 'file';
    protected string $_accept = '*';
    protected string $_max_size = '5M';
    protected int $_aspect_ratio = 1;
    protected int $_max_upload = 1;
    protected bool $_can_change_ratio = false;
    protected bool $_multiple = false;
    protected bool $_preview_grid = false;

    public function multiple()
    {
        $this->_multiple = true;
        return $this;
    }
    public function previewGrid()
    {
        $this->_preview_grid = true;
        return $this;
    }
    public function copperDefaultRatio(int $ratio)
    {
        $this->_aspect_ratio = $ratio;
        return $this;
    }
    public function maxUpload(int $max_upload)
    {
        $this->_max_upload = $max_upload;
        return $this;
    }
    public function canChangeCropperRatio()
    {
        $this->_can_change_ratio = true;
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
        $data['preview_grid'] = $this->_preview_grid;
        $data['can_change_ratio'] = $this->_can_change_ratio;
        $data['aspect_ratio'] = $this->_aspect_ratio;
        $data['max_upload'] = $this->_max_upload;
        return $data;
    }
}