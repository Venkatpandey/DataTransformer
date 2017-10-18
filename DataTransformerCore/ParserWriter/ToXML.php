<?php
    /**
     * Created by PhpStorm.
     * User: venpan
     * Date: 18/10/2017
     * Time: 19:45
     */

    namespace MakeResultClass;
    header('Content-type: text/plain');


    class ToXML
    {
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
         * ToXML constructor.
         *
         * @param $Array
         * @param $Resfilename
         */
        public function __construct($Array, $Resfilename)
        {
            $this->ToXML($Array, $Resfilename);
        }
    }