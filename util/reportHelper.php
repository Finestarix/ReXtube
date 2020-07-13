<?php

require_once(dirname(__FILE__) . '/uriHelper.php');

checkURI(realpath(__FILE__));

if (!function_exists('createReport')) {

    function createReport($title, $content)
    {
        $reportTemplate =
            "<div class='card mb-3'>
                <div class='card-header'>{{title}}</div>
                <div class='card-body'>{{row}}</div>
            </div>";

        $rowTemplate =
            "<div class='row'>
                <div class='col-lg-12 col-md-12 col-sm-12'>{{body}}</div>
            </div>";

        $bodyContents = [];
        if (is_array($content))
            $bodyContents = $content;
        else
            $bodyContents[] = $content;

        $rowString = "";
        foreach ($bodyContents as $body)
            $rowString .= str_replace("{{body}}", $body, $rowTemplate);

        $reportString = str_replace("{{title}}", $title, $reportTemplate);
        $reportString = str_replace("{{row}}", $rowString, $reportString);

        return $reportString;
    }

}