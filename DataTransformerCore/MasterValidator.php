<?php
    /**
     * @author: Venkat Raman Pandey
     * Date: 12/10/2017
     * Time: 17:24
     */

    namespace DataTransform\MasterValidator;
    header('Content-type: text/plain');
    /**
     * Class MasterValidator
     * Responsible for All data validations
     *
     * @package MasterValidator
     */
    class MasterValidator
    {
        /**
         * Holder variable for Validated Array
         *
         * @var
         */
        private $validatedArray;

        /**
         * Getter function for Validated Array
         *
         * @return mixed
         */
        public function getValidatedArray() : array
        {
            return $this->validatedArray;
        }

        /**
         * Setter function for Validated Array
         *
         * @param mixed $validatedArray
         */
        public function setValidatedArray(array $validatedArray)
        {
            $this->validatedArray = $validatedArray;
        }

        /**
         * Validate Array action method
         *
         * @param $array
         * @return array
         */
        private function ValidateArrayAction($array)
        {
            // pass the array one by one and get rid of insane data :)
            $asciiValidated = $this->validateForAscii($array);
            $nonNegartiveValidated = $this->validateForNonNegative($asciiValidated);
            $urlValidated = $this->validateForUrl($nonNegartiveValidated);

            return $urlValidated;
        }

        /**
         * MasterValidator constructor.
         *
         * @param $array
         */
        function __construct($array)
        {
            $validatedArray = $this->ValidateArrayAction($array);
            $this->setValidatedArray($validatedArray);
        }

        /**
         * Helper function to validate array for ascii chars in Hotel Names
         *
         * @param $array
         * @return array
         */
        private function validateForAscii($array) : array
        {
            foreach ($array as $aHotels => $aHotel) {
                if(false == mb_detect_encoding($aHotel['name'], 'ASCII', true)) {
                    unset($array[$aHotels]);
                }
            }

            return $array;
        }

        /**
         * Keeeping this simple, just check for illegar charecters and validate format using filter_var
         *
         * Subject to extend this to where it makes calls to each url and check, check headers etc.
         * @param $array
         * @return array
         */
        private function validateForUrl($array) : array
        {
            foreach ($array as $aHotels => $aHotel) {
                // Remove all illegal characters from a url
                $cleanUrl = filter_var($aHotel['uri'], FILTER_SANITIZE_URL);

                // Validate url
                if (filter_var($cleanUrl, FILTER_VALIDATE_URL) === false) {
                    unset($array[$aHotels]);
                }
            }

            return $array;
        }

        /**
         * Helper Function to validate hotel star ratings
         * allowed values should be between 0-5
         *
         * @param $array
         * @return array
         */
        private function validateForNonNegative($array) : array
        {
            foreach ($array as $aHotels => $aHotel) {
                $intStar = (int) $aHotel['stars'];
                $stars = filter_var($intStar, FILTER_VALIDATE_INT, array('options' => array(
                            'min_range' => 0,
                            'max_range' => 5
                        )
                    )
                );
                if(!$stars) {
                    unset($array[$aHotels]);
                }
            }

            return $array;
        }
    }