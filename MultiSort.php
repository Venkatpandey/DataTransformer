<?php
    /**
     * @author: Venkat Raman Pandey
     * Date: 12/10/2017
     * Time: 10:06
     */

    namespace DataTransform\MultiSort;
    header('Content-type: text/plain');
    include_once ('MasterValidator.php');

    class MultiSort
    {
        /**
         * Holder variable for sorted array
         *
         * @var
         */
        private $sortedArrayData;

        /**
         * Getter function for sorted array
         *
         * @return mixed
         */
        public function getSortedArrayData() : array
        {
            return $this->sortedArrayData;
        }

        /**
         * Setter function for sorted array
         *
         * @param mixed $sortedArrayData
         */
        public function setSortedArrayData(array $sortedArrayData)
        {
            $this->sortedArrayData = $sortedArrayData;
        }

        /**
         * Sorting direction mapper array
         *
         * Tip:- can be added to html directly as value
         * @var array
         */
         private static $sortMapper = [
            1 => SORT_ASC,
            2 => SORT_DESC,
         ];

        /**
         * Function to sort array as per user request
         *
         * @param $myArray
         * @param $sortType
         * @return bool
         */
        private function sortArray ($myArray, $sortType) {
            $sortOrder = (int) $sortType[0]; // sort order
            $sortThis = (string) $sortType[1]; // sort column

            $sortingMethod = self::$sortMapper[$sortOrder];
            foreach ($myArray as $key => $row) {
                isset($row[$sortThis]) ? $yourArray[$key] = $row[$sortThis] : $yourArray[$key]  = "";
            }

            //sort the yourarray in requested order
            array_multisort($yourArray, $sortingMethod, $myArray);

            // reindex
            // echo var_export($myArray);

            return array_values($myArray);
        }

        /**
         * MultiSort constructor.
         *
         * @param $ArrayData
         * @param $postSort
         */
        function __construct($ArrayData, $postSort) {
            $sorterType = explode(', ', $postSort["sort"]);
            $sortedArray = $this->sortArray($ArrayData, $sorterType);
            $this->setSortedArrayData($sortedArray);
        }
    }