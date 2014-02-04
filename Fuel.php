<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Fuel prices crawler
 * 
 * Fetches fuel prices from Globus and Tank-Ono websites.   
 * 
 * PHP version 5
 *
 * @category  CategoryName
 * @package   PackageName       
 * @author    Petr Pohl <peca.pohl@gmail.com>
 * @copyright 2014 Petr Pohl
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://pear.php.net/package/PackageName 
 */

/**
 * Fuel
 *
 * @category CategoryName
 * @package  PackageName
 * @author   Petr Pohl <peca.pohl@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: @package_version@* 
 * @link     http://pear.php.net/package/PackageName 
 */ 
class Fuel
{
    /**
     * @var string	 	 	 	     
     * @access private
     */
    private $_url;

    /**
     * @var string	 	 	 	     
     * @access private	      
     */
    private $_page;

    /**
     * @var string	 	 	 	     
     * @access private	      
     */    
    private $_line;

    const TANK_ONO = 'http://www.tank-ono.cz/cz/index.php?page=cenik';
    const GLOBUS_CAKOVICE = 'http://www.globus.cz/globus-cakovice/cerpaci-stanice.html';
    const GLOBUS_CERNY_MOST = 'http://www.globus.cz/globus-cerny-most/cerpaci-stanice.html';
    
    /**
     * getPage
     * 
     * @param string $_url the string to quote	      
     * 
     * @return (string)
     */
    public function getPage($_url)
    {
        $this->page = file_get_contents($_url);
        return $this->page;
    }

    /**
     * fetchPrices
     * 
     * @return (string)
     */
    public function fetchPrices()
    {
        $_page = $this->getPage(self::TANK_ONO);
        $this->line = $this->parseTankOno($_page);
        $this->line .= " Kč Tank Ono";
        $this->line .= "<br />";

        $_page = $this->getPage(self::GLOBUS_CERNY_MOST);
        $this->line .= $this->parseGlobus($_page);
        $this->line .= " Kč Globus Černý most";
        $this->line .= "<br />";

        $_page = $this->getPage(self::GLOBUS_CAKOVICE);
        $this->line .= $this->parseGlobus($_page);
        $this->line .= " Kč Globus Čakovice";
        $this->line .= "<br />";
        
        return $this->line;
    }

    /**
     * parseTankOno
     * 
     * @param string $_page the string to parse	      
     *     
     * @return string
     */
    public function parseTankOno($_page)
    {
        $lineNumber = strpos($_page, 'Trutnov');
        $lines = substr($_page, $lineNumber, 100);
        $stringNumber = strpos($lines, ',');
        $_line = substr(substr($lines, $stringNumber-2), 0, 5);
        return $_line;
    }

    /**
     * parseGlobus
     *
     * @param string $_page the string to parse
     * 	 	 	      
     * @return string 
     */
    public function parseGlobus($_page)
    {
        $lineNumber = strpos($_page, 'Natural 95');
        $lines = substr($_page, $lineNumber, 30);
        $stringNumber = strpos($lines, '.');
        $_line = substr(substr($lines, $stringNumber-2), 0, 5);
        $_line = str_replace('.', ',', $_line);
        
        return $_line;
    }
}
