<?php
    /**
     * Created by PhpStorm.
     * User: venpan
     * Date: 18/10/2017
     * Time: 19:46
     */

    namespace DataTransformer\ToJSON;
    use MakeResultClass;
    header('Content-type: text/plain');


    class ToJSON
    {

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

        public function __construct()
        {
        }
    }