<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(

		"ELEMENT_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("CP_BND_ELEMENT_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["ELEMENT_ID"]}',
		),
		"ELEMENT_CODE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("CP_BND_ELEMENT_CODE"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		
		"CACHE_TIME"  =>  array("DEFAULT"=>36000000),

		"DISPLAY_NAME" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_NAME"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	),
);
