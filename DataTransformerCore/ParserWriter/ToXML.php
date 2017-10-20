<?php
/**
 * User: venpan
 * Date: 18/10/2017
 * Time: 19:45
 */

namespace MakeResultClass\ToXML;
use DataTransform\DataTransformer;

header('Content-type: text/plain');
include_once ('MakeResult.php');

/**
 * Class ToXML
 *
 * @package MakeResultClass\ToXML
 */
class ToXML
{
    /**
     * @var
     */
    private $FileLocation;

    /**
     * @var
     */
    private $XMLRes;

    /**
     * @return mixed
     */
    public function getFileLocation()
    {
        return $this->FileLocation;
    }

    /**
     * @param mixed $FileLocation
     */
    public function setFileLocation($FileLocation)
    {
        $this->FileLocation = $FileLocation;
    }

    /**
     * @return mixed
     */
    public function getXMLRes()
    {
        return $this->XMLRes;
    }

    /**
     * @param mixed $XMLRes
     */
    public function setXMLRes($XMLRes)
    {
        $this->XMLRes = $XMLRes;
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
        $this->setFileLocation(DataTransformer::LOCAL_DIR . $Resfilename . DataTransformer::XML_FORMAT);
        $status = $xmlData->asXML($this->getFileLocation()) ? true : false;

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
        $Status = $this->ToXML($Array, $Resfilename);
        $this->setXMLRes($Status);
    }
}