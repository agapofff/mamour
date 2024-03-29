#!/usr/bin/env php
<?php
require_once(__DIR__ . '/../init.php');

if($argc < 3) {
    echo "Usage " . $argv[0] . " MORPH_DATA_FILE OUT_DIR [case - UPPER or LOWER]";
    exit;
}

$file = $argv[1];
$out_dir = $argv[2];

if(isset($argv[3])) {
    $new_case = strtolower($argv[3]) == 'upper' ? 'upper' : 'lower';
} else {
    $new_case = null;
}

try {
    extract_gramtab($file, $out_dir, true, $new_case);
    extract_gramtab($file, $out_dir, false, $new_case);
} catch (Exception $e) {
    echo $e;
    exit(1);
}

function replace_keys_with_name($map) {
    $result = array();
    
    foreach($map as $item) {
        $result[$item['name']] = $item;
    }
    
    if(count($map) != count($result)) {
        throw new Exception("Map contains non unique names");
    }
    
    return $result;
}

abstract class GrammemsProcessorAbstract {
    abstract function process($partOfSpeech, $grammems);

    static function create($locale) {
        $locale=  self::getNormalizedLocale($locale);

        $class = "GrammemsProcessor_$locale";

        if(!class_exists($class)) {
            return new GrammemsProcessor_Common();
        } else {
            return new $class();
        }
    }

    static protected function getNormalizedLocale($locale) {
        return $locale;
    }
}

class GrammemsProcessor_Common extends GrammemsProcessorAbstract {
    function process($partOfSpeech, $grammems) {
        return $grammems;
    }
}

class GrammemsProcessor_ru_RU extends GrammemsProcessorAbstract {
    function process($partOfSpeech, $grammems) {
        if(in_array(phpMorphy_GramTab_Constants::PMY_RG_INDECLINABLE, $grammems)) {
            // ������������ ����� ��� ����� ����������� ���� �������
            if($partOfSpeech !== phpMorphy_GramTab_Constants::PMY_RP_PREDK) {
               $grammems = array_merge($grammems, $this->getAllCases());   

               // ����� '������' �� ���������� �� ������, ������� �����
               // ���� ������������ � ����� ������
               if(!in_array(phpMorphy_GramTab_Constants::PMY_RG_SINGULAR, $grammems)) {
                   $grammems[] = phpMorphy_GramTab_Constants::PMY_RG_PLURAL;
                   $grammems[] = phpMorphy_GramTab_Constants::PMY_RG_SINGULAR;
               }
            }
            
            if($partOfSpeech === phpMorphy_GramTab_Constants::PMY_RP_PRONOUN_P) {
                $grammems = array_merge($grammems, $this->getAllGenders());
                $grammems = array_merge($grammems, $this->getAllNumbers());
            }
        }


        // ����� ������ ���� ('������') �����  ������������ ��� 
        // ����� �.�., ��� � ��� ����� �.�.
        if(in_array(phpMorphy_GramTab_Constants::PMY_RG_MASC_FEM, $grammems)) {
            $grammems[] = phpMorphy_GramTab_Constants::PMY_RG_MASCULINUM;
            $grammems[] = phpMorphy_GramTab_Constants::PMY_RG_FEMINUM;
        }

        return array_unique($grammems);
    }

    protected function getAllCases() {
        return array(
            phpMorphy_GramTab_Constants::PMY_RG_NOMINATIV,
            phpMorphy_GramTab_Constants::PMY_RG_GENITIV,
            phpMorphy_GramTab_Constants::PMY_RG_DATIV,
            phpMorphy_GramTab_Constants::PMY_RG_ACCUSATIV,
            phpMorphy_GramTab_Constants::PMY_RG_INSTRUMENTALIS,
            phpMorphy_GramTab_Constants::PMY_RG_LOCATIV,
            phpMorphy_GramTab_Constants::PMY_RG_VOCATIV,
        );
    }

    protected function getAllGenders() {
        return array(
            phpMorphy_GramTab_Constants::PMY_RG_MASCULINUM,
            phpMorphy_GramTab_Constants::PMY_RG_FEMINUM,
            phpMorphy_GramTab_Constants::PMY_RG_NEUTRUM,
        );
    }

    protected function getAllNumbers() {
        return array(
            phpMorphy_GramTab_Constants::PMY_RG_PLURAL,
            phpMorphy_GramTab_Constants::PMY_RG_SINGULAR,
        );
    }
}

abstract class CaseConverterAbstract {
    protected $encoding;

    protected function __construct($encoding) {
        $this->encoding = $encoding;

        if(false === ($value = @mb_strtolower('a', $encoding))) {
            throw new Exception("Invalid encoding '$encoding'");
        }
    }

    static function create($encoding, $to) {
        if(!isset($to)) {
            $class = 'CaseConverter_AsIs';
        } else {
            $class = $to == 'lower' ? 'CaseConverter_Lower' : 'CaseConverter_Upper';
        }

        return new $class($encoding);
    }

    abstract function convert($str);
}

class CaseConverter_AsIs extends CaseConverterAbstract {
    function convert($str) {
        return $str;
    }
}

class CaseConverter_Upper extends CaseConverterAbstract {
    function convert($str) {
        return mb_strtoupper($str, $this->encoding);
    }
}

class CaseConverter_Lower extends CaseConverterAbstract {
    function convert($str) {
        return mb_strtolower($str, $this->encoding);
    }
}

function extract_gramtab($graminfoFile, $outDir, $asText, $case) {
    $factory = new phpMorphy_Storage_Factory();
    $graminfo = phpMorphy_GramInfo_GramInfoAbstract::create(
        $factory->create(phpMorphy::STORAGE_FILE, $graminfoFile, false),
        false
    );
    $grammems_processor = GrammemsProcessorAbstract::create($graminfo->getLocale());

    $pos_case_converter = CaseConverterAbstract::create($graminfo->getEncoding(), 'upper');
    $grammems_case_converter = CaseConverterAbstract::create($graminfo->getEncoding(), $case);
    
    $poses = $graminfo->readAllPartOfSpeech();
    $grammems = $graminfo->readAllGrammems(); 
    $ancodes = $graminfo->readAllAncodes();

    foreach($poses as &$pos) {
        $pos['name'] = $pos_case_converter->convert($pos['name']);
    }
    unset($pos);

    foreach($grammems as &$grammem) {
        $grammem['name'] = $grammems_case_converter->convert($grammem['name']);
    }
    unset($grammem);

    foreach($ancodes as &$ancode) {
        $ancode['grammem_ids'] = $grammems_processor->process($ancode['pos_id'], $ancode['grammem_ids']);
    }
    unset($ancode);
    
    if($asText) {
        foreach($ancodes as &$ancode) {
            $pos_id = $ancode['pos_id'];
            
            if(!isset($poses[$pos_id])) {
                throw new Exception("Unknown pos_id '$pos_id' found");
            }
            
            $ancode['pos_id'] = $pos_case_converter->convert($poses[$pos_id]['name']);
            
            foreach($ancode['grammem_ids'] as &$grammem_id) {
                if(!isset($grammems[$grammem_id])) {
                    throw new Exception("Unknown grammem_id '$grammem_id' found");
                }
                
                $grammem_id = $grammems_case_converter->convert($grammems[$grammem_id]['name']);
            }
        }
        unset($ancode);
        
        //$poses = replace_keys_with_name($poses);
        //$grammems = replace_keys_with_name($grammems);
    }
    
    $result = array(
        'poses' => $poses,
        'grammems' => $grammems,
        'ancodes' => $ancodes
    );
    
    $type = $asText ? '_txt' : '';
    $out_file = 'gramtab' . $type . '.' . strtolower($graminfo->getLocale()) . '.bin';
    $out_file = $outDir . '/' . $out_file;
    
    if(false === file_put_contents($out_file, serialize($result))) {
        throw new Exception("Can`t write '$out_file'");
    }
}
