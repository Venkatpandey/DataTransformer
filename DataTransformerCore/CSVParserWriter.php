<?php
    /**
     * User: venpan
     * Date: 14/10/2017
     * Time: 09:14
     */

    namespace DataTransform\CSVParserWriter;
    use DataTransform\MultiSort;
    use DataTransform\MasterValidator;
    use DataTransformer\MakeResultClass\MakeResultClass;
    header('Content-type: text/plain');
    include_once('MultiSort.php');
    include_once ('ParserWriter/MakeResult.php');
    //error_reporting(E_ERROR | E_PARSE);

    /**
     * Class CSVParserWriter
     * Responsible for preparing data as per user request like sorting and validation
     *
     * @package DataTransform\CSVParserWriter
     */
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
                // validate and sort
                $this->ValidateThisArray($ArrayData, $Post);
            } else {
                // just sort
                $this->sortThisArray($ArrayData, $Post);
            }
            // all requirements met, now we can proceed to prepare files
            $Status = $this->makeResultFile($this->getFinalArrayData(), $Post['target']);

            return $Status;
        }

        /**
         * @param $Array
         * @param $Format
         * @return mixed
         */
        private function makeResultFile($Array, $Format)
        {
            // initiate make result and deliver process
            $ResultData = new MakeResultClass($Array, $Format);

            return $ResultData->getResultStatus();
        }

        /**
         * @param $ArrayData
         * @param $Post
         */
        private function ValidateThisArray($ArrayData, $Post)
        {
            // initiate validate class, validate and proceed with sorting
            $ValidatedData = new MasterValidator\MasterValidator($ArrayData);
            $ValidatedArray = $ValidatedData->getValidatedArray();
            $this->sortThisArray($ValidatedArray, $Post);
        }

        /**
         * Mini function to sort array
         *
         * @param $ArrayToSort
         * @param $Post
         */
        private function sortThisArray ($ArrayToSort, $Post)
        {
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
        function __construct($arrayData, $post)
        {
            $Status = $this->ParserWriterAction($arrayData, $post);
            $this->setResult($Status);
        }
    }