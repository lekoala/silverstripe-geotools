<?php

/**
 * List of utilities to deal with French regions and departments
 *
 * @author Koala
 */
class FrenchGeoUtils
{
    const CODE = 'Code';
    const NAME   = 'Name';
    const REGION = 'Region';

    /**
     * Get all regions as an array
     * 
     * @return array
     */
    public static function getRegions()
    {
        $regions = array_map(function($i) {
            return $i['Region'];
        }, self::getDepartments());
        asort($regions);
        return $regions;
    }

    /**
     * Get all departments as a list with Code,Name and Region
     * @return array
     */
    public static function getDepartments()
    {
        return array(
            array("Code" => "1", "Name" => "Ain", "Region" => "Rhône-Alpes"),
            array("Code" => "2", "Name" => "Aisne", "Region" => "Picardie"),
            array("Code" => "3", "Name" => "Allier", "Region" => "Auvergne"),
            array("Code" => "4", "Name" => "Alpes de Haute-Provence", "Region" => "Provence-Alpes-Côte d'Azur"),
            array("Code" => "6", "Name" => "Alpes-Maritimes", "Region" => "Provence-Alpes-Côte d'Azur"),
            array("Code" => "7", "Name" => "Ardèche", "Region" => "Rhône-Alpes"),
            array("Code" => "8", "Name" => "Ardennes", "Region" => "Champagne"),
            array("Code" => "9", "Name" => "Ariège", "Region" => "Midi-Pyrénées"),
            array("Code" => "10", "Name" => "Aube", "Region" => "Champagne"),
            array("Code" => "11", "Name" => "Aude", "Region" => "Languedoc"),
            array("Code" => "12", "Name" => "Aveyron", "Region" => "Midi-Pyrénées"),
            array("Code" => "67", "Name" => "Bas-Rhin", "Region" => "Alsace"),
            array("Code" => "13", "Name" => "Bouches du Rhône", "Region" => "Provence-Alpes-Côte d'Azur"),
            array("Code" => "14", "Name" => "Calvados", "Region" => "Basse-Normandie"),
            array("Code" => "15", "Name" => "Cantal", "Region" => "Auvergne"),
            array("Code" => "16", "Name" => "Charente", "Region" => "Poitou-Charente"),
            array("Code" => "17", "Name" => "Charente Maritime", "Region" => "Poitou-Charente"),
            array("Code" => "18", "Name" => "Cher", "Region" => "Centre"),
            array("Code" => "19", "Name" => "Corrèze", "Region" => "Limousin"),
            array("Code" => "2A", "Name" => "Corse du Sud", "Region" => "Corse"),
            array("Code" => "21", "Name" => "Côte d'Or", "Region" => "Bourgogne"),
            array("Code" => "22", "Name" => "Côtes d'Armor", "Region" => "Bretagne"),
            array("Code" => "23", "Name" => "Creuse", "Region" => "Limousin"),
            array("Code" => "79", "Name" => "Deux-Sèvres", "Region" => "Poitou-Charente"),
            array("Code" => "24", "Name" => "Dordogne", "Region" => "Aquitaine"),
            array("Code" => "25", "Name" => "Doubs", "Region" => "Franche-Comté"),
            array("Code" => "26", "Name" => "Drôme", "Region" => "Rhône-Alpes"),
            array("Code" => "91", "Name" => "Essonne", "Region" => "Ile-de-France"),
            array("Code" => "27", "Name" => "Eure", "Region" => "Haute-Normandie"),
            array("Code" => "28", "Name" => "Eure-et-Loir", "Region" => "Centre"),
            array("Code" => "29", "Name" => "Finistère", "Region" => "Bretagne"),
            array("Code" => "30", "Name" => "Gard", "Region" => "Languedoc"),
            array("Code" => "32", "Name" => "Gers", "Region" => "Midi-Pyrénées"),
            array("Code" => "33", "Name" => "Gironde", "Region" => "Aquitaine"),
            array("Code" => "2B", "Name" => "Haute-Corse", "Region" => "Corse"),
            array("Code" => "31", "Name" => "Haute-Garonne", "Region" => "Midi-Pyrénées"),
            array("Code" => "43", "Name" => "Haute-Loire", "Region" => "Auvergne"),
            array("Code" => "52", "Name" => "Haute-Marne", "Region" => "Champagne"),
            array("Code" => "5", "Name" => "Hautes-Alpes", "Region" => "Provence-Alpes-Côte d'Azur"),
            array("Code" => "70", "Name" => "Haute-Saône", "Region" => "Franche-Comté"),
            array("Code" => "74", "Name" => "Haute-Savoie", "Region" => "Rhône-Alpes"),
            array("Code" => "65", "Name" => "Hautes-Pyrénées", "Region" => "Midi-Pyrénées"),
            array("Code" => "87", "Name" => "Haute-Vienne", "Region" => "Limousin"),
            array("Code" => "68", "Name" => "Haut-Rhin", "Region" => "Alsace"),
            array("Code" => "92", "Name" => "Hauts-de-Seine", "Region" => "Ile-de-France"),
            array("Code" => "34", "Name" => "Hérault", "Region" => "Languedoc"),
            array("Code" => "35", "Name" => "Ille-et-Vilaine", "Region" => "Bretagne"),
            array("Code" => "36", "Name" => "Indre", "Region" => "Centre"),
            array("Code" => "37", "Name" => "Indre-et-Loire", "Region" => "Centre"),
            array("Code" => "38", "Name" => "Isère", "Region" => "Rhône-Alpes"),
            array("Code" => "39", "Name" => "Jura", "Region" => "Franche-Comté"),
            array("Code" => "40", "Name" => "Landes", "Region" => "Aquitaine"),
            array("Code" => "42", "Name" => "Loire", "Region" => "Rhône-Alpes"),
            array("Code" => "44", "Name" => "Loire-Atlantique", "Region" => "Pays-de-la-Loire"),
            array("Code" => "45", "Name" => "Loiret", "Region" => "Centre"),
            array("Code" => "41", "Name" => "Loir-et-Cher", "Region" => "Centre"),
            array("Code" => "46", "Name" => "Lot", "Region" => "Midi-Pyrénées"),
            array("Code" => "47", "Name" => "Lot-et-Garonne", "Region" => "Aquitaine"),
            array("Code" => "48", "Name" => "Lozère", "Region" => "Languedoc"),
            array("Code" => "49", "Name" => "Maine-et-Loire", "Region" => "Pays-de-la-Loire"),
            array("Code" => "50", "Name" => "Manche", "Region" => "Normandie"),
            array("Code" => "51", "Name" => "Marne", "Region" => "Champagne"),
            array("Code" => "53", "Name" => "Mayenne", "Region" => "Pays-de-la-Loire"),
            array("Code" => "54", "Name" => "Meurthe-et-Moselle", "Region" => "Lorraine"),
            array("Code" => "55", "Name" => "Meuse", "Region" => "Lorraine"),
            array("Code" => "56", "Name" => "Morbihan", "Region" => "Bretagne"),
            array("Code" => "57", "Name" => "Moselle", "Region" => "Lorraine"),
            array("Code" => "58", "Name" => "Nièvre", "Region" => "Bourgogne"),
            array("Code" => "59", "Name" => "Nord", "Region" => "Nord"),
            array("Code" => "60", "Name" => "Oise", "Region" => "Picardie"),
            array("Code" => "61", "Name" => "Orne", "Region" => "Basse-Normandie"),
            array("Code" => "75", "Name" => "Paris", "Region" => "Ile-de-France"),
            array("Code" => "62", "Name" => "Pas-de-Calais", "Region" => "Nord"),
            array("Code" => "63", "Name" => "Puy-de-Dôme", "Region" => "Auvergne"),
            array("Code" => "64", "Name" => "Pyrénées-Atlantiques", "Region" => "Aquitaine"),
            array("Code" => "66", "Name" => "Pyrénées-Orientales", "Region" => "Languedoc"),
            array("Code" => "69", "Name" => "Rhône", "Region" => "Rhône-Alpes"),
            array("Code" => "71", "Name" => "Saône-et-Loire", "Region" => "Bourgogne"),
            array("Code" => "72", "Name" => "Sarthe", "Region" => "Pays-de-la-Loire"),
            array("Code" => "73", "Name" => "Savoie", "Region" => "Rhône-Alpes"),
            array("Code" => "77", "Name" => "Seine-et-Marne", "Region" => "Ile-de-France"),
            array("Code" => "76", "Name" => "Seine-Maritime", "Region" => "Haute-Normandie"),
            array("Code" => "93", "Name" => "Seine-St-Denis", "Region" => "Ile-de-France"),
            array("Code" => "80", "Name" => "Somme", "Region" => "Picardie"),
            array("Code" => "81", "Name" => "Tarn", "Region" => "Midi-Pyrénées"),
            array("Code" => "82", "Name" => "Tarn-et-Garonne", "Region" => "Midi-Pyrénées"),
            array("Code" => "90", "Name" => "Territoire-de-Belfort", "Region" => "Franche-Comté"),
            array("Code" => "94", "Name" => "Val-de-Marne", "Region" => "Ile-de-France"),
            array("Code" => "95", "Name" => "Val-d'Oise", "Region" => "Ile-de-France"),
            array("Code" => "83", "Name" => "Var", "Region" => "Provence-Alpes-Côte d'Azur"),
            array("Code" => "84", "Name" => "Vaucluse", "Region" => "Provence-Alpes-Côte d'Azur"),
            array("Code" => "85", "Name" => "Vendée", "Region" => "Pays-de-la-Loire"),
            array("Code" => "86", "Name" => "Vienne", "Region" => "Poitou-Charente"),
            array("Code" => "88", "Name" => "Vosges", "Region" => "Lorraine"),
            array("Code" => "89", "Name" => "Yonne", "Region" => "Bourgogne"),
            array("Code" => "78", "Name" => "Yvelines", "Region" => "Ile-de-France"),
        );
    }

    /**
     * Get the region of the given department
     * 
     * @param string $department
     * @return string
     */
    public static function getDepartementRegion($department)
    {
        foreach (self::getDepartments() as $dep) {
            if($department == $dep['Code'] || $department == $dep['Name']) {
                return $dep['Region'];
            }
        }
    }

    /**
     * Get french departments by region (ready to use for GroupedDropdownField)
     *
     * @param string $key Name or Code
     * @return array
     */
    public static function getDepartmentsByRegion($key = 'Code')
    {
        $dep = self::getDepartments();
        $reg = self::getRegions();

        $depByReg = array();
        foreach ($reg as $r) {
            $depByReg[$r] = array();
        }

        foreach ($dep as $d) {
            $depByReg[$d['Region']][$d[$key]] = $d['Name'];
        }

        return $depByReg;
    }

    /**
     * Get a list of french departments
     *
     * @param bool $byCode Sory by department number instead of name
     * @param string $key Name or Code
     * @return array
     */
    public static function getDepartmentsMap($byCode = false, $key = 'Name')
    {
        $map = array();
        foreach (self::getDepartments() as $dep) {
            $map[$dep[$key]] = $dep['Name'];
        }
        if ($byCode) {
            ksort($map);
        } else {
            asort($map);
        }
        return $map;
    }
}