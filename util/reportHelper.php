<?php

if (!function_exists('createReport')) {

    function createReport($title, $content)
    {
        $report_template =
            "<div class='card mb-3'>
                <div class='card-header'>{{title}}</div>
                <div class='card-body'>{{row}}</div>
            </div>";

        $row_template =
            "<div class='row'>
                <div class='col-lg-12 col-md-12 col-sm-12'>{{body}}</div>
            </div>";

        $body_contents = [];
        if (is_array($content))
            $body_contents = $content;
        else
            $body_contents[] = $content;

        $report_string = str_replace("{{title}}", $title, $report_template);
        $row_string = "";
        foreach ($body_contents as $body) {
            $row_string .= str_replace("{{body}}", $body, $row_template);
        }
        $report_string = str_replace("{{row}}", $row_string, $report_string);
        return $report_string;
    }

}