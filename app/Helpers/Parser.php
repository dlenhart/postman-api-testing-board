<?php

namespace App\Helpers;

/* Static methods for parsing Newman json into html */

class Parser
{
    /* buildUrl - concatnates array items into string
    *   
    *  @return string
    */
    public static function buildUrl(array $arr): string
    {
        $host = '';
        $path = '';
        $lastHost = end($arr['host']);
        $lastPath = end($arr['path']);

        foreach ($arr['host'] as $v) {
            if ($v == $lastHost) {
                $host .= $v;
            } else {
                $host .= $v . ".";
            }
        }

        foreach ($arr['path'] as $v) {
            if ($v == $lastPath) {
                $path .= $v;
            } else {
                $path .= $v . "/";
            }
        }

        return $arr['protocol'] . "://" . $host . "/" . $path;
    }

    /* buildAssertions - concatenates array items into string
    *   
    *  @return string
    */
    public static function buildAssertions(array $assertions): string
    {
        $ass = '';
        foreach ($assertions as $assertion) {
            if (isset($assertion['error'])) {
                $ass .= " -<strong>" . $assertion['assertion'] . 
                    " <span style='color: red'>(failed)</span></strong><br />";
                $ass .= " --<strong style='color:red'>" . 
                    $assertion['error']['message'] . "</strong><br />";
            } else {
                $ass .= " -" . $assertion['assertion'] . 
                    " <span style='color: green'>(pass)</span><br />";
            }
        }

        return $ass;
    }

    /* buildResults - builds final result string
    *  arrays are fun
    *  @return string
    */
    public static function buildResults(array $items, array $encode): string
    {
        $pos = 0;
        $resultsMessage = '';

        foreach ($items as $item) {
            $statusCode = $encode['run']['executions'][$pos]['response'];

            $url = Parser::buildUrl($item['request']['url']);
            $assertions = Parser::buildAssertions($encode['run']['executions'][$pos]['assertions']);

            $resultsMessage .= "<strong><u>" . $item['name'] . "</u></strong><br />";
            $resultsMessage .= $item['request']['method'] . " - <a class='terminalLink' href='" . $url . "' target='_blank'>" . $url . "</a>";
            $resultsMessage .= " [ " . $statusCode['code'] . " " . $statusCode['status'] . " ]<br />";
            $resultsMessage .= $assertions;
            $resultsMessage .= "<br />";

            $pos += 1;
        }

        return $resultsMessage;
    }
}
