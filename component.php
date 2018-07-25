<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

use Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Iblock;


$arParams["CACHE_TIME"] = (isset($arParams["CACHE_TIME"])) ? $arParams["CACHE_TIME"] : 36000000;
$arParams["ELEMENT_ID"] = intval($arParams["~ELEMENT_ID"]);


if ( $this->startResultCache() ){

	if(!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}


	$arFilter = array(
		"IBLOCK_LID" => SITE_ID,
		"IBLOCK_ACTIVE" => "Y",
		"ACTIVE" => "Y",
	);

	if($arParams["ELEMENT_ID"] <= 0){
		$arParams["ELEMENT_ID"] = CIBlockFindTools::GetElementID( $arParams["ELEMENT_ID"],$arParams["~ELEMENT_CODE"],false,false, $arFilter);
	}

	$arFilter['ID'] = $arParams['ELEMENT_ID'];
	
	$arSelect = array(
		'ID',
		'NAME',
		'PREVIEW_TEXT',
		'IBLOCK_SECTION_ID',
	);


	$q = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);

	if ($rsElement = $q->fetch()) {

	    $arResult = array(
	        'ID' => $rsElement["ID"],
	        'IBLOCK_ID' => $rsElement["IBLOCK_ID"],
	        'PREVIEW_TEXT' => $rsElement['PREVIEW_TEXT'],
	        'NAME' => $rsElement["NAME"]
	    );

	    $this->setResultCacheKeys($arSelect);
	    $this->IncludeComponentTemplate();

	} else {
		$this->abortResultCache();
	}


	
	if(isset($arResult["ID"]))
	{
		$arTitleOptions = null;
		if(Loader::includeModule("iblock"))
		{
			CIBlockElement::CounterInc($arResult["ID"]);

			if($USER->IsAuthorized() && $APPLICATION->GetShowIncludeAreas())
			{
				$arReturnUrl = array(
					"add_element" => CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "DETAIL_PAGE_URL"),
					"delete_element" => (
						empty($arResult["SECTION_URL"])?
						$arResult["LIST_PAGE_URL"]:
						$arResult["SECTION_URL"]
					),
				);

				$arButtons = CIBlock::GetPanelButtons(
					$arResult["IBLOCK_ID"],
					$arResult["ID"],
					$arResult["IBLOCK_SECTION_ID"],
					Array(
						"RETURN_URL" => $arReturnUrl,
						"SECTION_BUTTONS" => false,
					)
				);

				if($APPLICATION->GetShowIncludeAreas())
					$this->addIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));

			}
		}
	}
}