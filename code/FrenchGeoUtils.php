<?php

/**
 * List of utilities to deal with French regions and departments
 *
 * @author Koala
 */
class FrenchGeoUtils
{
    const NUMBER = 'Number';
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
     * Get all departments as a list with Number,Name and Region
     * @return array
     */
    public static function getDepartments()
    {
        return array(
            array("Number" => "1", "Name" => "Ain", "Region" => "Rhône-Alpes"),
            array("Number" => "2", "Name" => "Aisne", "Region" => "Picardie"),
            array("Number" => "3", "Name" => "Allier", "Region" => "Auvergne"),
            array("Number" => "4", "Name" => "Alpes de Haute-Provence", "Region" => "Provence-Alpes-Côte d'Azur"),
            array("Number" => "6", "Name" => "Alpes-Maritimes", "Region" => "Provence-Alpes-Côte d'Azur"),
            array("Number" => "7", "Name" => "Ardèche", "Region" => "Rhône-Alpes"),
            array("Number" => "8", "Name" => "Ardennes", "Region" => "Champagne"),
            array("Number" => "9", "Name" => "Ariège", "Region" => "Midi-Pyrénées"),
            array("Number" => "10", "Name" => "Aube", "Region" => "Champagne"),
            array("Number" => "11", "Name" => "Aude", "Region" => "Languedoc"),
            array("Number" => "12", "Name" => "Aveyron", "Region" => "Midi-Pyrénées"),
            array("Number" => "67", "Name" => "Bas-Rhin", "Region" => "Alsace"),
            array("Number" => "13", "Name" => "Bouches du Rhône", "Region" => "Provence-Alpes-Côte d'Azur"),
            array("Number" => "14", "Name" => "Calvados", "Region" => "Basse-Normandie"),
            array("Number" => "15", "Name" => "Cantal", "Region" => "Auvergne"),
            array("Number" => "16", "Name" => "Charente", "Region" => "Poitou-Charente"),
            array("Number" => "17", "Name" => "Charente Maritime", "Region" => "Poitou-Charente"),
            array("Number" => "18", "Name" => "Cher", "Region" => "Centre"),
            array("Number" => "19", "Name" => "Corrèze", "Region" => "Limousin"),
            array("Number" => "2A", "Name" => "Corse du Sud", "Region" => "Corse"),
            array("Number" => "21", "Name" => "Côte d'Or", "Region" => "Bourgogne"),
            array("Number" => "22", "Name" => "Côtes d'Armor", "Region" => "Bretagne"),
            array("Number" => "23", "Name" => "Creuse", "Region" => "Limousin"),
            array("Number" => "79", "Name" => "Deux-Sèvres", "Region" => "Poitou-Charente"),
            array("Number" => "24", "Name" => "Dordogne", "Region" => "Aquitaine"),
            array("Number" => "25", "Name" => "Doubs", "Region" => "Franche-Comté"),
            array("Number" => "26", "Name" => "Drôme", "Region" => "Rhône-Alpes"),
            array("Number" => "91", "Name" => "Essonne", "Region" => "Ile-de-France"),
            array("Number" => "27", "Name" => "Eure", "Region" => "Haute-Normandie"),
            array("Number" => "28", "Name" => "Eure-et-Loir", "Region" => "Centre"),
            array("Number" => "29", "Name" => "Finistère", "Region" => "Bretagne"),
            array("Number" => "30", "Name" => "Gard", "Region" => "Languedoc"),
            array("Number" => "32", "Name" => "Gers", "Region" => "Midi-Pyrénées"),
            array("Number" => "33", "Name" => "Gironde", "Region" => "Aquitaine"),
            array("Number" => "2B", "Name" => "Haute-Corse", "Region" => "Corse"),
            array("Number" => "31", "Name" => "Haute-Garonne", "Region" => "Midi-Pyrénées"),
            array("Number" => "43", "Name" => "Haute-Loire", "Region" => "Auvergne"),
            array("Number" => "52", "Name" => "Haute-Marne", "Region" => "Champagne"),
            array("Number" => "5", "Name" => "Hautes-Alpes", "Region" => "Provence-Alpes-Côte d'Azur"),
            array("Number" => "70", "Name" => "Haute-Saône", "Region" => "Franche-Comté"),
            array("Number" => "74", "Name" => "Haute-Savoie", "Region" => "Rhône-Alpes"),
            array("Number" => "65", "Name" => "Hautes-Pyrénées", "Region" => "Midi-Pyrénées"),
            array("Number" => "87", "Name" => "Haute-Vienne", "Region" => "Limousin"),
            array("Number" => "68", "Name" => "Haut-Rhin", "Region" => "Alsace"),
            array("Number" => "92", "Name" => "Hauts-de-Seine", "Region" => "Ile-de-France"),
            array("Number" => "34", "Name" => "Hérault", "Region" => "Languedoc"),
            array("Number" => "35", "Name" => "Ille-et-Vilaine", "Region" => "Bretagne"),
            array("Number" => "36", "Name" => "Indre", "Region" => "Centre"),
            array("Number" => "37", "Name" => "Indre-et-Loire", "Region" => "Centre"),
            array("Number" => "38", "Name" => "Isère", "Region" => "Rhône-Alpes"),
            array("Number" => "39", "Name" => "Jura", "Region" => "Franche-Comté"),
            array("Number" => "40", "Name" => "Landes", "Region" => "Aquitaine"),
            array("Number" => "42", "Name" => "Loire", "Region" => "Rhône-Alpes"),
            array("Number" => "44", "Name" => "Loire-Atlantique", "Region" => "Pays-de-la-Loire"),
            array("Number" => "45", "Name" => "Loiret", "Region" => "Centre"),
            array("Number" => "41", "Name" => "Loir-et-Cher", "Region" => "Centre"),
            array("Number" => "46", "Name" => "Lot", "Region" => "Midi-Pyrénées"),
            array("Number" => "47", "Name" => "Lot-et-Garonne", "Region" => "Aquitaine"),
            array("Number" => "48", "Name" => "Lozère", "Region" => "Languedoc"),
            array("Number" => "49", "Name" => "Maine-et-Loire", "Region" => "Pays-de-la-Loire"),
            array("Number" => "50", "Name" => "Manche", "Region" => "Normandie"),
            array("Number" => "51", "Name" => "Marne", "Region" => "Champagne"),
            array("Number" => "53", "Name" => "Mayenne", "Region" => "Pays-de-la-Loire"),
            array("Number" => "54", "Name" => "Meurthe-et-Moselle", "Region" => "Lorraine"),
            array("Number" => "55", "Name" => "Meuse", "Region" => "Lorraine"),
            array("Number" => "56", "Name" => "Morbihan", "Region" => "Bretagne"),
            array("Number" => "57", "Name" => "Moselle", "Region" => "Lorraine"),
            array("Number" => "58", "Name" => "Nièvre", "Region" => "Bourgogne"),
            array("Number" => "59", "Name" => "Nord", "Region" => "Nord"),
            array("Number" => "60", "Name" => "Oise", "Region" => "Picardie"),
            array("Number" => "61", "Name" => "Orne", "Region" => "Basse-Normandie"),
            array("Number" => "75", "Name" => "Paris", "Region" => "Ile-de-France"),
            array("Number" => "62", "Name" => "Pas-de-Calais", "Region" => "Nord"),
            array("Number" => "63", "Name" => "Puy-de-Dôme", "Region" => "Auvergne"),
            array("Number" => "64", "Name" => "Pyrénées-Atlantiques", "Region" => "Aquitaine"),
            array("Number" => "66", "Name" => "Pyrénées-Orientales", "Region" => "Languedoc"),
            array("Number" => "69", "Name" => "Rhône", "Region" => "Rhône-Alpes"),
            array("Number" => "71", "Name" => "Saône-et-Loire", "Region" => "Bourgogne"),
            array("Number" => "72", "Name" => "Sarthe", "Region" => "Pays-de-la-Loire"),
            array("Number" => "73", "Name" => "Savoie", "Region" => "Rhône-Alpes"),
            array("Number" => "77", "Name" => "Seine-et-Marne", "Region" => "Ile-de-France"),
            array("Number" => "76", "Name" => "Seine-Maritime", "Region" => "Haute-Normandie"),
            array("Number" => "93", "Name" => "Seine-St-Denis", "Region" => "Ile-de-France"),
            array("Number" => "80", "Name" => "Somme", "Region" => "Picardie"),
            array("Number" => "81", "Name" => "Tarn", "Region" => "Midi-Pyrénées"),
            array("Number" => "82", "Name" => "Tarn-et-Garonne", "Region" => "Midi-Pyrénées"),
            array("Number" => "90", "Name" => "Territoire-de-Belfort", "Region" => "Franche-Comté"),
            array("Number" => "94", "Name" => "Val-de-Marne", "Region" => "Ile-de-France"),
            array("Number" => "95", "Name" => "Val-d'Oise", "Region" => "Ile-de-France"),
            array("Number" => "83", "Name" => "Var", "Region" => "Provence-Alpes-Côte d'Azur"),
            array("Number" => "84", "Name" => "Vaucluse", "Region" => "Provence-Alpes-Côte d'Azur"),
            array("Number" => "85", "Name" => "Vendée", "Region" => "Pays-de-la-Loire"),
            array("Number" => "86", "Name" => "Vienne", "Region" => "Poitou-Charente"),
            array("Number" => "88", "Name" => "Vosges", "Region" => "Lorraine"),
            array("Number" => "89", "Name" => "Yonne", "Region" => "Bourgogne"),
            array("Number" => "78", "Name" => "Yvelines", "Region" => "Ile-de-France"),
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
            if($department == $dep['Number'] || $department == $dep['Name']) {
                return $dep['Region'];
            }
        }
    }

    /**
     * Get french departments by region (ready to use for GroupedDropdownField)
     *
     * @param string $key Name or Number
     * @return array
     */
    public static function getDepartmentsByRegion($key = 'Name')
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
     * @param bool $byNumber Sory by department number instead of name
     * @param string $key Name or Number
     * @return array
     */
    public static function getDepartmentsMap($byNumber = false, $key = 'Name')
    {
        $map = array();
        foreach (self::getDepartments() as $dep) {
            $map[$dep[$key]] = $dep['Name'];
        }
        if ($byNumber) {
            ksort($map);
        } else {
            asort($map);
        }
        return $map;
    }
}