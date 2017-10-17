<?php
    /**
     * @author: Venkat Raman Pandey
     * Date: 11/10/2017
     * Time: 11:02
     */

    namespace DataTransform;
    use DataTransform\CSVParserWriter;
    header('Content-type: text/plain');
    include_once ('CSVParserWriter.php');
    error_reporting(E_ERROR | E_PARSE);

    /**
     * Class DataTransformer
     */
    class DataTransformer {

        /** some constants to be used during process */

        // target format option
        const JSON_FORMAT = 'json';
        const XML_FORMAT = 'xml';
        const JSON_XML_FORMAT = 'json_xml';

        // upload and result local directory
        const LOCAL_DIR = "uploadAndResults/";

        // some variable definition
        public $result;
        private $sourceFilename;

        /**
         * transformAction method to take care of file upload and CSV processing
         *
         * @param $post
         * @param $file
         * @return bool
         */
        public function transformAction($post, $file)
        {
            $this->sourceFilename = DataTransformer::LOCAL_DIR . $file["upload"]["name"];
            $_SESSION['basename'] = basename($file["upload"]["name"], ".csv");
            $uploadOk = true; // optimistic :)

            // Check if csv file is a valid csv file indeed
            if (isset($post['submit'])) {
                $allowedsMimes = array('application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv');
                if (in_array($file['upload']['type'], $allowedsMimes)) {
                    $uploadOk = true;
                } else {
                    $uploadOk = false;
                }
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == false) {
                $this->processData($post, $uploadOk);
                // if everything is ok, try to upload file
            } else {
                // get it locally for processing
                if (move_uploaded_file($file['upload']['tmp_name'], $this->sourceFilename)) {
                    $status = $this->processData($post, $uploadOk);
                } else {
                    $this->processData($post, $uploadOk);
                }
            }
            $this->result = $status;

            return $uploadOk;
        }

        /**
         * This method gets the array and process it for desired results
         *
         * @param $post
         * @param $bool
         * @return mixed
         */
        private function processData($post, $bool)
        {
            $ResultStatus = '';
            if ($bool) {
                $ArrayData = $this->makeCSVToArray($this->sourceFilename);
                $ParserWriter = new CSVParserWriter\CSVParserWriter($ArrayData, $post);
            } else {
                // something went wrong during upload :(
                $ResultStatus = false;
            }

            // proceed to finish
            if (null != $ParserWriter) {
                // should be true for success
                $ResultStatus = $ParserWriter->getResult();
            } else {
                // something went wrong :(
                $ResultStatus = false;
            }

            return $ResultStatus;
        }

        /**
         * Sinple helper function convert CSV to Associative Array
         *
         * @param $file
         * @return array
         */
        private function makeCSVToArray ($file)
        {
            $allRows = array();
            $header = null;
            // initiate temporary resource file
            $tempResourceFile = fopen($file, 'r');
            while ($row = fgetcsv($tempResourceFile)) { // get all the rows
                if ($header === null) {
                    $header = $row;
                    continue;
                }
                $allRows[] = array_combine($header, $row); // association with header
            }

            return $allRows;
        }

        /**sudp
         * DataTransformer constructor.
         *
         * @param $post
         * @param $file
         */
        function __construct($post, $file) {
            $this->transformAction($post, $file);
            $this->result;
        }

    }