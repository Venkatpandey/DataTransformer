<?php
/**
 * @author: Venkat Raman Pandey
 * Date: 18/10/2017
 * Time: 19:15
 */

namespace DataTransformer\MakeResultClass;
use DataTransform\DataTransformer;
use MakeResultClass\ToJSON\ToJSON;
use MakeResultClass\ToXML\ToXML;
use MakeResultClass\Delivery\Delivery;
header('Content-type: text/plain');
include_once ('ToXML.php');
include_once ('ToJSON.php');
include_once ('Delivery.php');

/**
 * Class MakeResult
 * Responsible for making final result data for delivery
 *
 * @package MakeResultClass
 */
class MakeResultClass
{
    /**
     * @var
     */
    private $ResultStatus;

    /**
     * @return mixed
     */
    public function getResultStatus()
    {
        return $this->ResultStatus;
    }

    /**
     * @param mixed $ResultStatus
     */
    public function setResultStatus($ResultStatus)
    {
        $this->ResultStatus = $ResultStatus;
    }

    /**
     * Helper function to make result file for output
     *
     * @param $Array
     * @param $Format
     * @return bool
     */
    private function makeResultFile($Array, $Format)
    {
        // initiate the delivery class
        $Delivery = new Delivery();
        $status = false;
        // result file name
        $Resfilename = $_SESSION['basename'] . "_resultData.";
        switch ($Format) {
            case DataTransformer::XML_FORMAT:
                $Xml = $this->CallToXML($Array, $Resfilename);
                if($Xml != null) {
                    $status = $Delivery->Download($Xml->getFileLocation(), true);}
                break;

            case DataTransformer::JSON_FORMAT:
                $Json = $this->CallToJSON($Array, $Resfilename);
                if($Json != null) {
                    $status = $Delivery->Download($Json->getFileLocation(), true);}
                break;

            case DataTransformer::JSON_XML_FORMAT:
                $Xml = $this->CallToXML($Array, $Resfilename);
                $Json = $this->CallToJSON($Array, $Resfilename);
                if($Xml != null && $Json != null){
                    // special treatment for multiple files :)
                    $status = $Delivery->Download(false, false);
                }
                break;
        }

        return $status;
    }

    /**
     * @param $Array
     * @param $Resfilename
     * @return \MakeResultClass\ToXML\ToXML
     */
    private function CallToXML($Array, $Resfilename)
    {
        // xml class initiation
        $Xml = new ToXML($Array, $Resfilename);

        return $Xml;
    }

    /**
     * @param $Array
     * @param $Resfilename
     * @return \MakeResultClass\ToJSON\ToJSON
     */
    private function CallToJSON($Array, $Resfilename)
    {
        // json class initiation
        $Json = new ToJSON($Array, $Resfilename);

        return $Json;
    }

    /**
     * MakeResultClass constructor.
     *
     * @param $Array
     * @param $Format
     */
    public function __construct($Array, $Format)
    {
        $Status = $this->makeResultFile($Array, $Format);
        $this->setResultStatus($Status);
    }
}