<?php
// app/Services/DiceBearService.php

namespace App\Services;

class DiceBearService
{
    protected $baseUrl = 'https://api.dicebear.com/9.x';
    protected $style = 'adventurer-neutral';
    protected $seed;
    protected $size = 128;
    protected $options = [];

    public function style($style)
    {
        $this->style = $style;
        return $this;
    }

    public function seed($seed)
    {
        $this->seed = urlencode($seed);
        return $this;
    }

    public function size($size)
    {
        $this->size = $size;
        return $this;
    }

    public function options(array $options)
    {
        $this->options = $options;
        return $this;
    }

    public function getUrl()
    {
        $url = "{$this->baseUrl}/{$this->style}/svg?seed={$this->seed}&size={$this->size}";
        
        foreach ($this->options as $key => $value) {
            $url .= "&{$key}=" . urlencode($value);
        }
        
        return $url;
    }

    public function saveTo($path, $filename, $disk = 'public')
    {
        $url = $this->getUrl();
        $contents = file_get_contents($url);
        
        $fullPath = $path . '/' . $filename . '.svg';
        \Storage::disk($disk)->put($fullPath, $contents);
        
        return $fullPath;
    }
}