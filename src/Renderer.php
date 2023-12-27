<?php

namespace Serjoagronov\JsonToHtml;

class Renderer {

    private static function mappedImplode($glue, $array, $symbol = '='): string {
        return implode(
            $glue,
            array_map(
                function ($k, $v) use ($symbol) {
                    return $k . $symbol . $v;
                },
                array_keys($array),
                array_values($array)
            )
        );
    }

    public static function renderJson($jsonData, $data): string {
        $html = '';

        foreach ($jsonData as $jsonDatum) {
            $html .= '<' . $jsonDatum['elementType'] . ' ';

            if (!empty($jsonDatum['description'])) {
                $html .= 'title="' . $jsonDatum['description'] . '" ';
            }

            if (!empty($jsonDatum['elementStyles'])) {
                $html .= 'style="' . self::mappedImplode('; ', $jsonDatum['elementStyles'], ': ') . '" ';
            }

            if (!empty($jsonDatum['attributes']['id'])) {
                $html .= 'id="' . $jsonDatum['attributes']['id'] . '" ';
            }

            if (!empty($jsonDatum['attributes']['classes'])) {
                $html .= 'class="' . implode(' ', $jsonDatum['attributes']['classes']) . '" ';
            }

            if (isset($jsonDatum['attributes']['data'])) {
                foreach ($jsonDatum['attributes']['data'] as $datum) {
                    if (isset($datum['value']) && isset($data[$datum['value']])) {
                        $html .= $datum['name'] . '="' . $data[$datum['value']] . '" ';
                    } else {
                        $html .= $datum['name'] . '="' . $datum['value'] . '" ';
                    }
                }
            }

            $html .= '>';

            if (!empty($jsonDatum['text'])) {
                $html .= $jsonDatum['text'];
            }

            if (!empty($jsonDatum['children'])) {
                $html .= self::renderJson($jsonDatum['children'], $data);
            }

            $html .= '</' . $jsonDatum['elementType'] . '> ';
        }

        return $html;
    }

}
