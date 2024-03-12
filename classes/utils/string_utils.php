<?php
/**
 * String utils
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta\utils;

class StringUtils {
    /**
     * Truncate a string to a given length
     *
     * @param string $string
     * @param int $maxLength
     */
    public static function truncateHtmlText($htmlContent, $maxLength = 300) {
        $truncated = '';
        $length = 0;
        $openTags = [];
        
        // Dividir el contenido en partes de etiquetas y texto
        $parts = preg_split('/(<[^>]*>)/', $htmlContent, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    
        foreach ($parts as $part) {
            if ($part[0] == '<') {
                // Manejar inicio/final de etiquetas
                if ($part[1] != '/' && substr($part, -2) != '/>') {
                    // Etiqueta de inicio
                    preg_match('/<(\w+)/', $part, $matches);
                    $openTags[] = $matches[1];
                } elseif (strpos($part, '</') === 0) {
                    // Etiqueta de cierre
                    array_pop($openTags);
                }
                $truncated .= $part;
            } else {
                $neededLength = $maxLength - $length;
                $partLength = mb_strlen($part);
    
                if ($length + $partLength > $maxLength) {
                    // Si la parte excede la longitud máxima, encontrar el último espacio
                    $endPosition = mb_strrpos(mb_substr($part, 0, $neededLength), ' ');
                    if ($endPosition !== false) {
                        $truncated .= mb_substr($part, 0, $endPosition) . '...';
                    } else {
                        // Si no hay espacio, añadir puntos suspensivos directamente
                        $truncated .= mb_substr($part, 0, $neededLength) . '...';
                    }
                    break;
                } else {
                    $truncated .= $part;
                    $length += $partLength;
                }
            }
        }
    
        // Cerrar cualquier etiqueta HTML abierta
        while (!empty($openTags)) {
            $truncated .= '</' . array_pop($openTags) . '>';
        }
    
        return $truncated;
    }
    
    
    
}