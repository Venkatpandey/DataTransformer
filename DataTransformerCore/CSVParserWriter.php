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
         * @param $Array
         * @param $Format
         */
        private function makeResultFile($Array, $Format)
        {

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