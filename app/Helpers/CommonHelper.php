<?php

/**
 * Remove file extension from a given string.
 * @param string $string
 * @return array
 */
function removeFileExtension($string)
{
    $removedExtension = explode('.', $string);
    
    unset($removedExtension[sizeof($removedExtension) - 1]);
    
    $removedExtension = implode('.', $removedExtension);
    
    return $removedExtension;
}