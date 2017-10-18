<?php
    /**
     * User: venpan
     * Date: 14/10/2017
     * Time: 09:14
     */

    namespace DataTransform\CSVParserWriter;
    use DataTransform\DataTransformer;
    use DataTransform\MultiSort;
    use DataTransform\MasterValidator;
    use \ZipArchive;
    header('Content-type: text/plain');
    include_once('MultiSort.php');

    error_reporting(E_ERROR | E_PARSE);

    class CSVParserWriter
    {
        /**
         * Holder variable for Final processed Array
         *
         * @var
         */
        private $finalArrayData;

        /**
         * Holder vatiable to store final result
         *
         * @var
         */
        private $finalResult;

        /**
         * Getter function for Final processed Array
         *
         * @return mixed
         */
        public function getFinalArrayData()
        {
            return $this->finalArrayData;
        }

        /**
         * Setter function for Final processed Array
         *
         * @param mixed $finalArrayData
         */
        public function setFinalArrayData(array $finalArrayData)
        {
            $this->finalArrayData = $finalArrayData;
        }

        /**
         * @return mixed
         */
        public function getResult()
        {
            return $this->finalResult;
        }

        /**
         * @param mixed $finalResult
         */
        public function setResult($finalResult)
        {
            $this->finalResult = $finalResult;
        }

        /**
         * Main action method to initiate all sorting and validation
         *
         * @param $ArrayData
         * @param $Post
         * @return bool
         */
        public function ParserWriterAction($ArrayData, $Post)
        {
            //proceed with validation now if user requests
            if (isset($Post['validation']) && 1 == $Post['validation']) {
                $ValidatedData = new MasterValidator\MasterValidator($ArrayData);
                $ValidatedArray = $ValidatedData->getValidatedArray();
                $this->sortThisArray($ValidatedArray, $Post);
            } else {
                $this->sortThisArray($ArrayData, $Post);
            }
            // all requirements met, now we can proceed to prepare files
            $Status = $this->makeResultFile($this->getFinalArrayData(), $Post['target']);

            return $Status;
        }

        /**
         * Mini function to sort array
         *
         * @param $ArrayToSort
         * @param $Post
         */
        private function sortThisArray ($ArrayToSort, $Post) {
            $SortedData = new MultiSort\MultiSort($ArrayToSort, $Post);
            $SortedArray = $SortedData->getSortedArrayData();
            $this->setFinalArrayData($SortedArray);
        }



        /**
         * helper function to make XML data file
         * outputs in DataTransformer::TARGET_DIR
         *
         * @param $Array
         * @param $Resfilename
         * @return bool
         */
        private function ToXML ($Array, $Resfilename)
        {
            $xmlData = new \SimpleXMLElement("<?xml version=\"1.0\"?><hotel_data></hotel_data>");
            $this->arrayToXML($Array, $xmlData);
            $status = $xmlData->asXML(DataTransformer::LOCAL_DIR . $Resfilename . DataTransformer::XML_FORMAT) ? true : false;

            return $status;
        }

        /**
         * helper function to make XML data file
         * outputs in DataTransformer::TARGET_DIR
         *
         * @param $Array
         * @param $Resfilename
         * @return bool
         */
        private function ToJSON ($Array, $Resfilename)
        {
            // special case for json, we need to make sure to aviod any non utf-8 char encoding
            $utfEncodedArray = $this->encodeToUtf8($Array);
            $jsonData = $this->jsonEncode($utfEncodedArray);
            // need some validation here to check if file actially got written before returning true
            $jsonfile = file_put_contents(DataTransformer::LOCAL_DIR . $Resfilename . DataTransformer::JSON_FORMAT, $jsonData);

            return $jsonfile;
        }

        /**
         * @param $filePath
         * @param $fileOnly
         * @return bool
         */
        private function DownloadToClient($filePath, $fileOnly)
        {
            // simple header accumalation to stream download to client
            if ($fileOnly && file_exists($filePath)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($filePath));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filePath));
                ob_clean();
                flush();

                // proceed with download and return status
                return readfile($filePath) ? true : false;

            } elseif (false == $fileOnly) {
                // prepare zip download
                $zipPackageName = DataTransformer::LOCAL_DIR . $_SESSION['basename'] . 'ResultPackage.zip';
                // zip all files and make a package to deliver to client
                $zip = new \ZipArchive();
                $zip->open($zipPackageName, ZipArchive::CREATE);
                foreach (glob(DataTransformer::LOCAL_DIR."*") as $file) { /* Add appropriate path to read content of zip */
                    $zip->addFile($file);
                }
                $zip->close();
                header('Content-Type: application/zip');
                header("Content-Disposition: attachment; filename = $zipPackageName");
                header('Content-Length: ' . filesize($zipPackageName));
                header("Location: $zipPackageName");

                return readfile($zipPackageName) ? true : false;
            } else{

                // file didnt created, return false for now.
                return false;
            }
        }

        /**
         * helper function to convert array to xml data
         *
         * @param $array
         * @param $xmlData
         */
        private function arrayToXML($array, &$xmlData)
        {
            // loop through each element and add to xmldata
            foreach($array as $key => $value) {
                if(is_array($value)) {
                    if(!is_numeric($key)){
                        $subnode = $xmlData->addChild("$key");
                        $this->arrayToXML($value, $subnode);
                    }else{
                        $subnode = $xmlData->addChild("hotel$key");
                        $this->arrayToXML($value, $subnode);
                    }
                }else {
                    $xmlData->addChild("$key",htmlspecialchars("$value"));
                }
            }
        }

        /**
         * For json, this function encodes data to utf8
         *
         * @param $array
         * @return mixed
         */
        private function encodeToUtf8($array)
        {
            // loop through each element and make them utf8 encoded
            foreach($array as $key => $value) {
                if(is_array($value)) {
                    $array[$key] = $this->encodeToUtf8($value);
                }
                else {
                    $array[$key] = mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                }
            }

            return $array;
        }

        /**
         * Alternative to json_encode() to handle big arrays
         * Regular json_encode would return NULL due to memory issues.
         * Found this function from stack Overflow
         * @param $arr
         * @return string
         */
        private function jsonEncode($arr) {
            $str = '{';
            $count = count($arr);
            $current = 0;

            foreach ($arr as $key => $value) {
                $str .= sprintf('"%s":', $this->sanitizeForJSON($key));

                if (is_array($value)) {
                    $str .= '[';
                    foreach ($value as &$val) {
                        $val = $this->sanitizeForJSON($val);
                    }
                    $str .= '"' . implode('","', $value) . '"';
                    $str .= ']';
                } else {
                    $str .= sprintf('"%s"', $this->sanitizeForJSON($value));
                }

                $current ++;
                if ($current < $count) {
                    $str .= ',';
                }
            }

            $str.= '}';

            return $str;
        }

        /**
         * Part of jsonEncode()
         *
         * @param string $str
         * @return string
         */
        private function sanitizeForJSON($str)
        {
            // Strip all slashes:
            $str = stripslashes($str);

            // Only escape backslashes:
            $str = str_replace('"', '\"', $str);

            return $str;
        }

        /**
         * CSVParserWriter constructor.
         *
         * @param $arrayData
         * @param $post
         */
        function __construct($arrayData, $post) {
            $Status = $this->ParserWriterAction($arrayData, $post);
            $this->setResult($Status);
        }
    }