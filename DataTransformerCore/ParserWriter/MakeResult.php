<?php
    /**
     * User: venpan
     * Date: 18/10/2017
     * Time: 19:15
     */

    namespace DataTransformer\MakeResultClass;
    use MakeResultClass\ToXML;
    header('Content-type: text/plain');
    //include_once ('ToXML.php');

    /**
     * Class MakeResult
     *
     * @package MakeResultClass
     */
    class MakeResult
    {
        /**
         * Helper function to make result file for output
         *
         * @param $Array
         * @param $Format
         * @return bool
         */
        private function makeResultFile($Array, $Format)
        {
            $status = false;
            $Resfilename = $_SESSION['basename'] . "_resultData.";
            switch ($Format) {
                case DataTransformer::XML_FORMAT:
                    $ifFile = $this->ToXML($Array, $Resfilename);
                    if($ifFile) {
                        $status = $this->DownloadToClient(DataTransformer::LOCAL_DIR . $Resfilename . DataTransformer::XML_FORMAT, true);}
                    break;

                case DataTransformer::JSON_FORMAT:
                    $ifFile = $this->ToJSON($Array, $Resfilename);
                    if($ifFile) {
                        $status = $this->DownloadToClient(DataTransformer::LOCAL_DIR . $Resfilename . DataTransformer::JSON_FORMAT, true);}
                    break;

                case DataTransformer::JSON_XML_FORMAT:
                    $fileX = $this->ToXML($Array, $Resfilename);
                    $fileJ = $this->ToJSON($Array, $Resfilename);
                    if($fileX && $fileJ){
                        // special treatment for multiple files :)
                        $status = $this->DownloadToClient(false, false);
                    }
                    break;
            }

            return $status;
        }

        private function CallToXML($Array, $Resfilename)
        {

            $this->ToXML($Array, $Resfilename);
        }

        public function __construct($Array, $Format)
        {
            $this->makeResultFile($Array, $Format);
        }
    }