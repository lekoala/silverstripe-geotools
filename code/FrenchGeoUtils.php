<?php

/**
 * List of utilities to deal with French regions and departments
 *
 * @author Koala
 */
class FrenchGeoUtils
{

    const CODE = 'Code';
    const NAME = 'Name';
    const REGION = 'Region';
    const NUTS = 'Nuts';

    /**
     * Get all regions as an array
     * 
     * @return array
     */
    public static function getRegions()
    {
        $regions = array_map(function ($i) {
            return $i['Region'];
        }, self::getDepartments());
        asort($regions);
        return $regions;
    }

    /**
     * Get all departments as a list with Code,Name and Region (new French Region since 2019.11.24)
     * @return array
     */
    public static function getDepartments()
    {
        return array(
            array("Code" => "1", "Name" => "Ain", "Region" => "Auvergne-Rhône-Alpes",
                "Nuts" => "FR711"),
            array("Code" => "2", "Name" => "Aisne", "Region" => "Hauts-de-France",
                "Nuts" => "FR221"),
            array("Code" => "3", "Name" => "Allier", "Region" => "Auvergne-Rhône-Alpes",
                "Nuts" => "FR721"),
            array("Code" => "4", "Name" => "Alpes de Haute-Provence", "Region" => "Provence-Alpes-Côte d'Azur",
                "Nuts" => "FR821"),
            array("Code" => "6", "Name" => "Alpes-Maritimes", "Region" => "Provence-Alpes-Côte d'Azur",
                "Nuts" => "FR823"),
            array("Code" => "7", "Name" => "Ardèche", "Region" => "Auvergne-Rhône-Alpes",
                "Nuts" => "FR712"),
            array("Code" => "8", "Name" => "Ardennes", "Region" => "Grand-Est",
                "Nuts" => "FR211"),
            array("Code" => "9", "Name" => "Ariège", "Region" => "Occitanie",
                "Nuts" => "FR621"),
            array("Code" => "10", "Name" => "Aube", "Region" => "Grand-Est",
                "Nuts" => "FR212"),
            array("Code" => "11", "Name" => "Aude", "Region" => "Occitanie",
                "Nuts" => "FR811"),
            array("Code" => "12", "Name" => "Aveyron", "Region" => "Occitanie",
                "Nuts" => "FR622"),
            array("Code" => "67", "Name" => "Bas-Rhin", "Region" => "Grand-Est",
                "Nuts" => "FR421"),
            array("Code" => "13", "Name" => "Bouches du Rhône", "Region" => "Provence-Alpes-Côte d'Azur",
                "Nuts" => "FR824"),
            array("Code" => "14", "Name" => "Calvados", "Region" => "Normandie",
                "Nuts" => "FR251"),
            array("Code" => "15", "Name" => "Cantal", "Region" => "Auvergne-Rhône-Alpes",
                "Nuts" => "FR722"),
            array("Code" => "16", "Name" => "Charente", "Region" => "Nouvelle-Aquitaine",
                "Nuts" => "FR531"),
            array("Code" => "17", "Name" => "Charente Maritime", "Region" => "Nouvelle-Aquitaine",
                "Nuts" => "FR532"),
            array("Code" => "18", "Name" => "Cher", "Region" => "Centre-Val de Loire",
                "Nuts" => "FR241"),
            array("Code" => "19", "Name" => "Corrèze", "Region" => "Nouvelle-Aquitaine",
                "Nuts" => "FR631"),
            array("Code" => "2A", "Name" => "Corse du Sud", "Region" => "Corse",
                "Nuts" => "FR831"),
            array("Code" => "21", "Name" => "Côte d'Or", "Region" => "Bourgogne-Franche-Comté",
                "Nuts" => "FR261"),
            array("Code" => "22", "Name" => "Côtes d'Armor", "Region" => "Bretagne",
                "Nuts" => "FR521"),
            array("Code" => "23", "Name" => "Creuse", "Region" => "Nouvelle-Aquitaine",
                "Nuts" => "FR623"),
            array("Code" => "79", "Name" => "Deux-Sèvres", "Region" => "Nouvelle-Aquitaine",
                "Nuts" => "FR533"),
            array("Code" => "24", "Name" => "Dordogne", "Region" => "Nouvelle-Aquitaine",
                "Nuts" => "FR611"),
            array("Code" => "25", "Name" => "Doubs", "Region" => "Bourgogne-Franche-Comté",
                "Nuts" => "FR431"),
            array("Code" => "26", "Name" => "Drôme", "Region" => "Auvergne-Rhône-Alpes",
                "Nuts" => "FR713"),
            array("Code" => "91", "Name" => "Essonne", "Region" => "Ile-de-France",
                "Nuts" => "FR104"),
            array("Code" => "27", "Name" => "Eure", "Region" => "Normandie",
                "Nuts" => "FR231"),
            array("Code" => "28", "Name" => "Eure-et-Loir", "Region" => "Centre-Val de Loire",
                "Nuts" => "FR242"),
            array("Code" => "29", "Name" => "Finistère", "Region" => "Bretagne",
                "Nuts" => "FR522"),
            array("Code" => "30", "Name" => "Gard", "Region" => "Occitanie",
                "Nuts" => "FR812"),
            array("Code" => "32", "Name" => "Gers", "Region" => "Occitanie",
                "Nuts" => "FR624"),
            array("Code" => "33", "Name" => "Gironde", "Region" => "Nouvelle-Aquitaine",
                "Nuts" => "FR612"),
            array("Code" => "68", "Name" => "Haut-Rhin", "Region" => "Grand-Est",
                "Nuts" => "FR422"),
            array("Code" => "2B", "Name" => "Haute-Corse", "Region" => "Corse",
                "Nuts" => "FR832"),
            array("Code" => "31", "Name" => "Haute-Garonne", "Region" => "Occitanie",
                "Nuts" => "FR623"),
            array("Code" => "43", "Name" => "Haute-Loire", "Region" => "Auvergne-Rhône-Alpes",
                "Nuts" => "FR723"),
            array("Code" => "52", "Name" => "Haute-Marne", "Region" => "Grand-Est",
                "Nuts" => "FR214"),
            array("Code" => "70", "Name" => "Haute-Saône", "Region" => "Bourgogne-Franche-Comté",
                "Nuts" => "FR433"),
            array("Code" => "74", "Name" => "Haute-Savoie", "Region" => "Auvergne-Rhône-Alpes",
                "Nuts" => "FR718"),
            array("Code" => "87", "Name" => "Haute-Vienne", "Region" => "Nouvelle-Aquitaine",
                "Nuts" => "FR633"),
            array("Code" => "5", "Name" => "Hautes-Alpes", "Region" => "Provence-Alpes-Côte d'Azur",
                "Nuts" => "FR822"),
            array("Code" => "65", "Name" => "Hautes-Pyrénées", "Region" => "Occitanie",
                "Nuts" => "FR626"),
            array("Code" => "92", "Name" => "Hauts-de-Seine", "Region" => "Ile-de-France",
                "Nuts" => "FR105"),
            array("Code" => "34", "Name" => "Hérault", "Region" => "Occitanie",
                "Nuts" => "FR813"),
            array("Code" => "35", "Name" => "Ille-et-Vilaine", "Region" => "Bretagne",
                "Nuts" => "FR523"),
            array("Code" => "36", "Name" => "Indre", "Region" => "Centre-Val de Loire",
                "Nuts" => "FR243"),
            array("Code" => "37", "Name" => "Indre-et-Loire", "Region" => "Centre-Val de Loire",
                "Nuts" => "FR244"),
            array("Code" => "38", "Name" => "Isère", "Region" => "Auvergne-Rhône-Alpes",
                "Nuts" => "FR714"),
            array("Code" => "39", "Name" => "Jura", "Region" => "Bourgogne-Franche-Comté",
                "Nuts" => "FR432"),
            array("Code" => "40", "Name" => "Landes", "Region" => "Nouvelle-Aquitaine",
                "Nuts" => "FR613"),
            array("Code" => "42", "Name" => "Loire", "Region" => "Auvergne-Rhône-Alpes",
                "Nuts" => "FR715"),
            array("Code" => "44", "Name" => "Loire-Atlantique", "Region" => "Pays-de-la-Loire",
                "Nuts" => "FR511"),
            array("Code" => "45", "Name" => "Loiret", "Region" => "Centre-Val de Loire",
                "Nuts" => "FR246"),
            array("Code" => "41", "Name" => "Loir-et-Cher", "Region" => "Centre-Val de Loire",
                "Nuts" => "FR245"),
            array("Code" => "46", "Name" => "Lot", "Region" => "Occitanie",
                "Nuts" => "FR625"),
            array("Code" => "47", "Name" => "Lot-et-Garonne", "Region" => "Nouvelle-Aquitaine",
                "Nuts" => "FR614"),
            array("Code" => "48", "Name" => "Lozère", "Region" => "Occitanie",
                "Nuts" => "FR814"),
            array("Code" => "49", "Name" => "Maine-et-Loire", "Region" => "Pays-de-la-Loire",
                "Nuts" => "FR512"),
            array("Code" => "50", "Name" => "Manche", "Region" => "Normandie",
                "Nuts" => "FR252"),
            array("Code" => "51", "Name" => "Marne", "Region" => "Grand-Est",
                "Nuts" => "FR213"),
            array("Code" => "53", "Name" => "Mayenne", "Region" => "Pays-de-la-Loire",
                "Nuts" => "FR513"),
            array("Code" => "54", "Name" => "Meurthe-et-Moselle", "Region" => "Grand-Est",
                "Nuts" => "FR411"),
            array("Code" => "55", "Name" => "Meuse", "Region" => "Grand-Est",
                "Nuts" => "FR412"),
            array("Code" => "56", "Name" => "Morbihan", "Region" => "Bretagne",
                "Nuts" => "FR524"),
            array("Code" => "57", "Name" => "Moselle", "Region" => "Grand-Est",
                "Nuts" => "FR413"),
            array("Code" => "58", "Name" => "Nièvre", "Region" => "Bourgogne-Franche-Comté",
                "Nuts" => "FR262"),
            array("Code" => "59", "Name" => "Nord", "Region" => "Hauts-de-France",
                "Nuts" => "FR301"),
            array("Code" => "60", "Name" => "Oise", "Region" => "Hauts-de-France",
                "Nuts" => "FR105"),
            array("Code" => "61", "Name" => "Orne", "Region" => "Normandie",
                "Nuts" => "FR253"),
            array("Code" => "75", "Name" => "Paris", "Region" => "Ile-de-France",
                "Nuts" => "FR101"),
            array("Code" => "62", "Name" => "Pas-de-Calais", "Region" => "Hauts-de-France",
                "Nuts" => "FR302"),
            array("Code" => "63", "Name" => "Puy-de-Dôme", "Region" => "Auvergne-Rhône-Alpes",
                "Nuts" => "FR724"),
            array("Code" => "64", "Name" => "Pyrénées-Atlantiques", "Region" => "Nouvelle-Aquitaine",
                "Nuts" => "FR615"),
            array("Code" => "66", "Name" => "Pyrénées-Orientales", "Region" => "Occitanie",
                "Nuts" => "FR815"),
            array("Code" => "69", "Name" => "Rhône", "Region" => "Auvergne-Rhône-Alpes",
                "Nuts" => "FR716"),
            array("Code" => "71", "Name" => "Saône-et-Loire", "Region" => "Bourgogne-Franche-Comté",
                "Nuts" => "FR263"),
            array("Code" => "72", "Name" => "Sarthe", "Region" => "Pays-de-la-Loire",
                "Nuts" => "FR514"),
            array("Code" => "73", "Name" => "Savoie", "Region" => "Auvergne-Rhône-Alpes",
                "Nuts" => "FR717"),
            array("Code" => "77", "Name" => "Seine-et-Marne", "Region" => "Ile-de-France",
                "Nuts" => "FR102"),
            array("Code" => "76", "Name" => "Seine-Maritime", "Region" => "Normandie",
                "Nuts" => "FR232"),
            array("Code" => "93", "Name" => "Seine-St-Denis", "Region" => "Ile-de-France",
                "Nuts" => "FR106"),
            array("Code" => "80", "Name" => "Somme", "Region" => "Hauts-de-France",
                "Nuts" => "FR223"),
            array("Code" => "81", "Name" => "Tarn", "Region" => "Occitanie",
                "Nuts" => "FR627"),
            array("Code" => "82", "Name" => "Tarn-et-Garonne", "Region" => "Occitanie",
                "Nuts" => "FR628"),
            array("Code" => "90", "Name" => "Territoire-de-Belfort", "Region" => "Bourgogne-Franche-Comté",
                "Nuts" => "FR434"),
            array("Code" => "94", "Name" => "Val-de-Marne", "Region" => "Ile-de-France",
                "Nuts" => "FR107"),
            array("Code" => "95", "Name" => "Val-d'Oise", "Region" => "Ile-de-France",
                "Nuts" => "FR108"),
            array("Code" => "83", "Name" => "Var", "Region" => "Provence-Alpes-Côte d'Azur",
                "Nuts" => "FR825"),
            array("Code" => "84", "Name" => "Vaucluse", "Region" => "Provence-Alpes-Côte d'Azur",
                "Nuts" => "FR826"),
            array("Code" => "85", "Name" => "Vendée", "Region" => "Pays-de-la-Loire",
                "Nuts" => "FR515"),
            array("Code" => "86", "Name" => "Vienne", "Region" => "Nouvelle-Aquitaine",
                "Nuts" => "FR534"),
            array("Code" => "88", "Name" => "Vosges", "Region" => "Grand-Est",
                "Nuts" => "FR414"),
            array("Code" => "89", "Name" => "Yonne", "Region" => "Bourgogne-Franche-Comté",
                "Nuts" => "FR264"),
            array("Code" => "78", "Name" => "Yvelines", "Region" => "Ile-de-France",
                "Nuts" => "FR103"),
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
            if ($department == $dep['Code'] || $department == $dep['Name']) {
                return $dep['Region'];
            }
        }
    }

    /**
     * Get the nuts code of the given department
     *
     * @param string $department
     * @return string
     */
    public static function getDepartmentNuts($department, $level = 3)
    {
        foreach (self::getDepartments() as $dep) {
            if ($department == $dep['Code'] || $department == $dep['Name']) {
                return substr($dep['Nuts'], 0, 2 + $level);
            }
        }
    }

    /**
     * Get the name of the given department
     *
     * @param string $department
     * @return string
     */
    public static function getDepartementName($department)
    {
        foreach (self::getDepartments() as $dep) {
            if ($department == $dep['Code'] || $department == $dep['Name']) {
                return $dep['Name'];
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
