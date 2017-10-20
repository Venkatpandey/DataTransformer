<?php
/**
 * Created by PhpStorm.
 * User: venpan
 * Date: 14/10/2017
 * Time: 17:35
 */

namespace TestDataTransformer;

use DataTransform\MultiSort\MultiSort;
use \PHPUnit_Framework_TestCase;

class MultisortTest extends PHPUnit_Framework_TestCase
{
    public function testsetSortedArrayData() : void {
        $testArray = array (
                    0 =>
                        array (
                            'name' => 'Apartment Drr',
                            'address' => 'Bolzmannweg 451, 05116 Hannover',
                            'stars' => '1',
                            'contact' => 'Scarlet Kusch-Linke',
                            'phone' => '8177354570',
                            'uri' => 'http://www.garden.com/list/home.html',
                        ),
                    1 =>
                        array (
                            'name' => 'Apartment Ruggiero Giordano',
                            'address' => 'Contrada Tazio 704, Rosaria laziale, 71084 Bari (SS)',
                            'stars' => '5',
                            'contact' => 'Benedetta Caputo',
                            'phone' => '+39 88 34207250',
                            'uri' => 'http://the.biz/',
                        ),
                    2 =>
                        array (
                            'name' => 'Comfort Inn Reichel',
                            'address' => '0119 Lisette Rue Apt. 585, Koepptown, DE 55466',
                            'stars' => '5',
                            'contact' => 'Ms. Bonnie Rogahn',
                            'phone' => '(533)040-1428x28811',
                            'uri' => 'http://ondricka.com/search/',
                        ),
                    3 =>
                        array (
                            'name' => 'Diaz',
                            'address' => '41, avenue de Marin, 91 255 Morvan',
                            'stars' => '2',
                            'contact' => 'ClǸmence Hoarau',
                            'phone' => '602634745',
                            'uri' => 'http://vaillant.com/list/app/faq/',
                        ),);
        $this->assertInstanceOf(
            MultiSort::class,
            MultiSort::setSortedArrayData($testArray)
        );
    }
}
