<?php

namespace App\Utils;

class CsvParser
{

    private $row;

    // Mapping des colonnes du CSV
    private $map;

    // Separateur pour parser CSV
    private $separator;
    private $csv;

    public function __construct(string $path, string $separator = ';')
    {
        $this->csv = fopen($path, 'r');
        $this->separator = $separator;

        $this->parseHeader();
    }

// Parse le header pour remplir le Mapping
    private function parseHeader()
    {
        $header = fgetcsv($this->csv, 0, $this->separator);

        $this->map = [];
        foreach ($header as $i => $h) {
            $this->map[$h] = $i;
        }
    }

// Récupéère la valeur d'une colonne par le nom du header
    public function get(string $col, $defaultValue = null)
    {
        $col = $this->map[$col] ?? '';
        return $this->row[$col] ?? $defaultValue;
    }

// Récupère la ligne suivante tu CSV
    public function nextRow()
    {
        return $this->row = fgetcsv($this->csv, 0, $this->separator);
    }
}