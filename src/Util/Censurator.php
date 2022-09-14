<?php
namespace App\Util;
class Censurator
{
    private $censoredWords = ["Arthur", "Carotte"];

    public function purify(string $string):string{
        foreach ($this->censoredWords as $word) {
            $string = preg_replace('/'.$word.'/i',str_repeat('*', strlen($word)),$string);
        }
        return $string;
    }
}
