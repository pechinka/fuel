<?php

class Fuel
{
    private $url;
    private $page;
    private $line;

    const TANK_ONO = 'http://www.tank-ono.cz/cz/index.php?page=cenik';
    const GLOBUS_CAKOVICE = 'http://www.globus.cz/globus-cakovice/cerpaci-stanice.html';
    const GLOBUS_CERNY_MOST = 'http://www.globus.cz/globus-cerny-most/cerpaci-stanice.html';

    public function getPage($url)
    {
        $this->page = file_get_contents($url);
        return $this->page;
    }

    public function fetchPrices()
    {
        $page = $this->getPage(self::TANK_ONO);
        $this->line = $this->parseTankOno($page);
        $this->line .= " Kč Tank Ono";

        $page = $this->getPage(self::GLOBUS_CERNY_MOST);
        $this->line .= $this->parseGlobus($page);
        $this->line .= " Kč Globus Černý most";

        $page = $this->getPage(self::GLOBUS_CAKOVICE);
        $this->line .= $this->parseGlobus($page);
        $this->line .= " Kč Globus Čakovice";
        return $this->line;
    }

    public function parseTankOno($page)
    {
        $lineNumber = strpos($page, 'Trutnov');
        $lines = substr($page, $lineNumber, 100);
        $stringNumber = strpos($lines, ',');
        $line = substr(substr($lines, $stringNumber-2), 0, 5);
        return $line;
    }

    public function parseGlobus($page)
    {
        $lineNumber = strpos($page, 'Natural 95');
        $lines = substr($page, $lineNumber, 30);
        $stringNumber = strpos($lines, '.');
        $line = substr(substr($lines, $stringNumber-2), 0, 5);
        $line = str_replace('.', ',', $line);
        return $line;
    }
}
