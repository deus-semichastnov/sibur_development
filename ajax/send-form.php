<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(CModule::IncludeModule('iblock')){
    $recIb = "21";
    $arLoadProductArrayData = array(
        "MODIFIED_BY"       => 1,
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID"         => $recIb,
        "ACTIVE"            => "Y",
    );
    $arLoadProductArray = array_merge($arLoadProductArrayData, $_REQUEST["FIELDS"]);
    $arLoadProductArray["PROPERTY_VALUES"] = $_REQUEST["PROPERTY"];
    if(!empty($_FILES)){
        //$arRasFiles = array("doc","pdf","jpg","jpeg","png");
        $arLoadProps = false;
        foreach ($_FILES["file"] as $keyb => $valueb) {
            if(is_array($valueb)){
                foreach ($valueb as $keye => $valuee) {
//                                if($keyd == "name"){
//                                    $ras = getExtension($valuee);
//                                    $arResult["ras"][] = $valuee;
//                                    $ras = strtolower($ras);
//                                    if(!in_array($ras, $arRasFiles)){
//                                        echo json_encode(array("denied_extension_file"=>$ras));
//                                        die();
//                                    }
//                                }
                    $arLoadProductArray["PROPERTY_VALUES"]["FILES"][$keye][$keyb] = $valuee;

                }
            }else{
//                            if($keyd == "name"){
//                                $ras = getExtension($valuec);
//                                $arResult["ras"][] = $valuec;
//                                $ras = strtolower($ras);
//                                if(!in_array($ras, $arRasFiles)){
//                                    echo json_encode(array("denied_extension_file"=>$ras));
//                                    die();
//                                }
//                            }
                $arLoadProductArray["PROPERTY_VALUES"]["FILES"][][$keyb] = $valueb;
            }
        }
    }
    $arEventFields = array_merge($_REQUEST["FIELDS"],$_REQUEST["PROPERTY"]);
    if($_REQUEST["PROPERTY"]["STAGE"]) {
        $property_enums = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID" => $recIb, "CODE" => "STAGE"));
        while ($enum_fields = $property_enums->GetNext()) {
            if (is_array($_REQUEST["PROPERTY"]["STAGE"]) && in_array($enum_fields["ID"], $_REQUEST["PROPERTY"]["STAGE"])) {
                $stageData[] = $enum_fields["VALUE"];
            }
            if(!is_array($_REQUEST["PROPERTY"]["STAGE"]) && $enum_fields["ID"] == $_REQUEST["PROPERTY"]["STAGE"]){
                $stageData[] = $enum_fields["VALUE"];
            }
        }
        $arEventFields["STAGE"] = "";
        if($stageData){
            foreach ($stageData as $item) {
                $arEventFields["STAGE"] .= $item."<br>";
            }
        }
    }
    $arEventFields["EMAIL_TO"] = "e.semichastnov@web-hands.ru";
    $sendTpl = "FEEDBACK";
    if(isset($_GET["sendTpl"]) && $_GET["sendTpl"] != ""){
        $sendTpl = $_GET["sendTpl"];
    }
    if ( CEvent::Send( $sendTpl, "s1", $arEventFields ) ) {
        $el = new CIBlockElement;
        if ( $el->Add( $arLoadProductArray ) ) {
            echo "send";
        }
    }
//    echo "<pre>"; print_r($arLoadProductArray); echo "</pre>";
//    echo "<pre>"; print_r($arEventFields); echo "</pre>";
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>