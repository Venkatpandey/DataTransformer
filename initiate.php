<?php
    /**
     * User: venpan
     * Date: 11/10/2017
     * Time: 15:07
     */

    use DataTransform\DataTransformer;
    header('Content-type: text/plain');
    include_once ("uploadAndProcess.php");

    $bool = new DataTransformer($_POST, $_FILES);
    // this needs some improvements :( lets settle for this hack for now
    $sbool = $bool->result;

    // delete temp files
    //array_map('unlink', glob("uploadAndResults/*"));
    //array_map('unlink', glob("*.zip"));

    header("Location: index.php?action=".$sbool);