<?php

namespace App\Services;

class GeneralResourceService
{
    public function getBanks(){
        $banks = 
        [
            [
                "id"=> 10001,
                "code"=> "044",
                "name"=> "Access Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10002,
                "code"=> "023",
                "name"=> "Citi Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10004,
                "code"=> "050",
                "name"=> "EcoBank PLC",
                "country_id"=> 154
            ],
            [
                "id"=> 10005,
                "code"=> "011",
                "name"=> "First Bank PLC",
                "country_id"=> 154
            ],
            [
                "id"=> 10006,
                "code"=> "214",
                "name"=> "First City Monument Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10007,
                "code"=> "070",
                "name"=> "Fidelity Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10008,
                "code"=> "058",
                "name"=> "Guaranty Trust Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10009,
                "code"=> "076",
                "name"=> "Polaris bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10010,
                "code"=> "221",
                "name"=> "Stanbic IBTC Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10011,
                "code"=> "068",
                "name"=> "Standard Chartered bank PLC",
                "country_id"=> 154
            ],
            [
                "id"=> 10012,
                "code"=> "232",
                "name"=> "Sterling Bank PLC",
                "country_id"=> 154
            ],
            [
                "id"=> 10013,
                "code"=> "033",
                "name"=> "United Bank for Africa",
                "country_id"=> 154
            ],
            [
                "id"=> 10014,
                "code"=> "032",
                "name"=> "Union Bank PLC",
                "country_id"=> 154
            ],
            [
                "id"=> 10015,
                "code"=> "035",
                "name"=> "Wema Bank PLC",
                "country_id"=> 154
            ],
            [
                "id"=> 10016,
                "code"=> "057",
                "name"=> "Zenith bank PLC",
                "country_id"=> 154
            ],
            [
                "id"=> 10017,
                "code"=> "215",
                "name"=> "Unity Bank PLC",
                "country_id"=> 154
            ],
            [
                "id"=> 10018,
                "code"=> "101",
                "name"=> "ProvidusBank PLC",
                "country_id"=> 154
            ],
            [
                "id"=> 10183,
                "code"=> "082",
                "name"=> "Keystone Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10184,
                "code"=> "301",
                "name"=> "Jaiz Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10186,
                "code"=> "030",
                "name"=> "Heritage Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10231,
                "code"=> "100",
                "name"=> "Suntrust Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10252,
                "code"=> "608",
                "name"=> "FINATRUST MICROFINANCE BANK",
                "country_id"=> 154
            ],
            [
                "id"=> 10253,
                "code"=> "090175",
                "name"=> "Rubies Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10254,
                "code"=> "090267",
                "name"=> "Kuda",
                "country_id"=> 154
            ],
            [
                "id"=> 10258,
                "code"=> "090115",
                "name"=> "TCF MFB",
                "country_id"=> 154
            ],
            [
                "id"=> 10259,
                "code"=> "400001",
                "name"=> "FSDH Merchant Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10260,
                "code"=> "502",
                "name"=> "Rand merchant Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10301,
                "code"=> "103",
                "name"=> "Globus Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10389,
                "code"=> "327",
                "name"=> "Paga",
                "country_id"=> 154
            ],
            [
                "id"=> 10395,
                "code"=> "000026",
                "name"=> "Taj Bank Limited",
                "country_id"=> 154
            ],
            [
                "id"=> 10596,
                "code"=> "100022",
                "name"=> "GoMoney",
                "country_id"=> 154
            ],
            [
                "id"=> 10597,
                "code"=> "090180",
                "name"=> "AMJU Unique Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10638,
                "code"=> "090393",
                "name"=> "BRIDGEWAY MICROFINANCE BANK",
                "country_id"=> 154
            ],
            [
                "id"=> 10639,
                "code"=> "090328",
                "name"=> "Eyowo MFB",
                "country_id"=> 154
            ],
            [
                "id"=> 10640,
                "code"=> "090281",
                "name"=> "Mint-Finex MICROFINANCE BANK",
                "country_id"=> 154
            ],
            [
                "id"=> 10659,
                "code"=> "070006",
                "name"=> "Covenant Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10660,
                "code"=> "090110",
                "name"=> "VFD Micro Finance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10661,
                "code"=> "090317",
                "name"=> "PatrickGold Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10728,
                "code"=> "090325",
                "name"=> "Sparkle",
                "country_id"=> 154
            ],
            [
                "id"=> 10784,
                "code"=> "305",
                "name"=> "Paycom",
                "country_id"=> 154
            ],
            [
                "id"=> 10811,
                "code"=> "070001",
                "name"=> "NPF MicroFinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10812,
                "code"=> "110001",
                "name"=> "PayAttitude Online",
                "country_id"=> 154
            ],
            [
                "id"=> 10813,
                "code"=> "100027",
                "name"=> "Intellifin",
                "country_id"=> 154
            ],
            [
                "id"=> 10814,
                "code"=> "100032",
                "name"=> "Contec Global Infotech Limited (NowNow)",
                "country_id"=> 154
            ],
            [
                "id"=> 10815,
                "code"=> "100031",
                "name"=> "FCMB Easy Account",
                "country_id"=> 154
            ],
            [
                "id"=> 10816,
                "code"=> "100030",
                "name"=> "EcoMobile",
                "country_id"=> 154
            ],
            [
                "id"=> 10817,
                "code"=> "100029",
                "name"=> "Innovectives Kesh",
                "country_id"=> 154
            ],
            [
                "id"=> 10818,
                "code"=> "100026",
                "name"=> "One Finance",
                "country_id"=> 154
            ],
            [
                "id"=> 10819,
                "code"=> "100025",
                "name"=> "Zinternet Nigera Limited",
                "country_id"=> 154
            ],
            [
                "id"=> 10820,
                "code"=> "100023",
                "name"=> "TagPay",
                "country_id"=> 154
            ],
            [
                "id"=> 10821,
                "code"=> "100021",
                "name"=> "Eartholeum",
                "country_id"=> 154
            ],
            [
                "id"=> 10822,
                "code"=> "100020",
                "name"=> "MoneyBox",
                "country_id"=> 154
            ],
            [
                "id"=> 10824,
                "code"=> "100019",
                "name"=> "Fidelity Mobile",
                "country_id"=> 154
            ],
            [
                "id"=> 10825,
                "code"=> "000019",
                "name"=> "Enterprise Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10826,
                "code"=> "559",
                "name"=> "Coronation Merchant Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10827,
                "code"=> "060002",
                "name"=> "FBNQUEST Merchant Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10828,
                "code"=> "060003",
                "name"=> "Nova Merchant Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10829,
                "code"=> "070007",
                "name"=> "Omoluabi savings and loans",
                "country_id"=> 154
            ],
            [
                "id"=> 10830,
                "code"=> "090001",
                "name"=> "ASOSavings & Loans",
                "country_id"=> 154
            ],
            [
                "id"=> 10831,
                "code"=> "090005",
                "name"=> "Trustbond Mortgage Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10832,
                "code"=> "090006",
                "name"=> "SafeTrust ",
                "country_id"=> 154
            ],
            [
                "id"=> 10833,
                "code"=> "090107",
                "name"=> "FBN Mortgages Limited",
                "country_id"=> 154
            ],
            [
                "id"=> 10834,
                "code"=> "100024",
                "name"=> "Imperial Homes Mortgage Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10835,
                "code"=> "100028",
                "name"=> "AG Mortgage Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10836,
                "code"=> "070009",
                "name"=> "Gateway Mortgage Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10837,
                "code"=> "070010",
                "name"=> "Abbey Mortgage Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10838,
                "code"=> "070011",
                "name"=> "Refuge Mortgage Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10839,
                "code"=> "070012",
                "name"=> "Lagos Building Investment Company",
                "country_id"=> 154
            ],
            [
                "id"=> 10840,
                "code"=> "070013",
                "name"=> "Platinum Mortgage Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10841,
                "code"=> "070014",
                "name"=> "First Generation Mortgage Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10842,
                "code"=> "070015",
                "name"=> "Brent Mortgage Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10843,
                "code"=> "070016",
                "name"=> "Infinity Trust Mortgage Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10844,
                "code"=> "090003",
                "name"=> "Jubilee-Life Mortgage  Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10845,
                "code"=> "070017",
                "name"=> "Haggai Mortgage Bank Limited",
                "country_id"=> 154
            ],
            [
                "id"=> 10846,
                "code"=> "090108",
                "name"=> "New Prudential Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10847,
                "code"=> "070002",
                "name"=> "Fortis Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10848,
                "code"=> "070008",
                "name"=> "Page Financials",
                "country_id"=> 154
            ],
            [
                "id"=> 10849,
                "code"=> "090004",
                "name"=> "Parralex Microfinance bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10850,
                "code"=> "090097",
                "name"=> "Ekondo MFB",
                "country_id"=> 154
            ],
            [
                "id"=> 10851,
                "code"=> "090112",
                "name"=> "Seed Capital Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10852,
                "code"=> "090114",
                "name"=> "Empire trust MFB",
                "country_id"=> 154
            ],
            [
                "id"=> 10853,
                "code"=> "090116",
                "name"=> "AMML MFB",
                "country_id"=> 154
            ],
            [
                "id"=> 10854,
                "code"=> "090117",
                "name"=> "Boctrust Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10855,
                "code"=> "090118",
                "name"=> "IBILE Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10856,
                "code"=> "090119",
                "name"=> "Ohafia Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10857,
                "code"=> "090120",
                "name"=> "Wetland Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10858,
                "code"=> "090121",
                "name"=> "Hasal Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10859,
                "code"=> "090122",
                "name"=> "Gowans Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10860,
                "code"=> "090123",
                "name"=> "Verite Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10861,
                "code"=> "090124",
                "name"=> "Xslnce Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10862,
                "code"=> "090125",
                "name"=> "Regent Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10863,
                "code"=> "090126",
                "name"=> "Fidfund Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10864,
                "code"=> "090127",
                "name"=> "BC Kash Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10865,
                "code"=> "090128",
                "name"=> "Ndiorah Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10866,
                "code"=> "090129",
                "name"=> "Money Trust Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10867,
                "code"=> "090130",
                "name"=> "Consumer Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10868,
                "code"=> "090131",
                "name"=> "Allworkers Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10869,
                "code"=> "090132",
                "name"=> "Richway Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10870,
                "code"=> "090133",
                "name"=> " AL-Barakah Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10871,
                "code"=> "090134",
                "name"=> "Accion Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10872,
                "code"=> "090135",
                "name"=> "Personal Trust Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10873,
                "code"=> "090136",
                "name"=> "Baobab Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10874,
                "code"=> "090137",
                "name"=> "PecanTrust Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10875,
                "code"=> "090138",
                "name"=> "Royal Exchange Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10876,
                "code"=> "090139",
                "name"=> "Visa Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10877,
                "code"=> "090140",
                "name"=> "Sagamu Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10878,
                "code"=> "090141",
                "name"=> "Chikum Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10879,
                "code"=> "090142",
                "name"=> "Yes Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10880,
                "code"=> "090143",
                "name"=> "Apeks Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10881,
                "code"=> "090144",
                "name"=> "CIT Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10882,
                "code"=> "090145",
                "name"=> "Fullrange Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10883,
                "code"=> "090146",
                "name"=> "Trident Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10884,
                "code"=> "090147",
                "name"=> "Hackman Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10885,
                "code"=> "090148",
                "name"=> "Bowen Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10886,
                "code"=> "090149",
                "name"=> "IRL Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10887,
                "code"=> "090150",
                "name"=> "Virtue Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10888,
                "code"=> "090151",
                "name"=> "Mutual Trust Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10889,
                "code"=> "090152",
                "name"=> "Nagarta Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10890,
                "code"=> "090153",
                "name"=> "FFS Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10891,
                "code"=> "090154",
                "name"=> "CEMCS Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10892,
                "code"=> "090155",
                "name"=> "La  Fayette Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10893,
                "code"=> "090156",
                "name"=> "e-Barcs Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10894,
                "code"=> "090157",
                "name"=> "Infinity Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10895,
                "code"=> "090158",
                "name"=> "Futo Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10896,
                "code"=> "090159",
                "name"=> "Credit Afrique Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10897,
                "code"=> "090160",
                "name"=> "Addosser Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10898,
                "code"=> "090161",
                "name"=> "Okpoga Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10899,
                "code"=> "090162",
                "name"=> "Stanford Microfinance Bak",
                "country_id"=> 154
            ],
            [
                "id"=> 10900,
                "code"=> "090164",
                "name"=> "First Royal Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10901,
                "code"=> "090165",
                "name"=> "Petra Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10902,
                "code"=> "090166",
                "name"=> "Eso-E Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10903,
                "code"=> "090167",
                "name"=> "Daylight Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10904,
                "code"=> "090168",
                "name"=> "Gashua Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10905,
                "code"=> "090169",
                "name"=> "Alpha Kapital Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10906,
                "code"=> "090171",
                "name"=> "Mainstreet Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10907,
                "code"=> "090172",
                "name"=> "Astrapolaris Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10908,
                "code"=> "090173",
                "name"=> "Reliance Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10909,
                "code"=> "090174",
                "name"=> "Malachy Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10910,
                "code"=> "090175",
                "name"=> "HighStreet Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10911,
                "code"=> "090176",
                "name"=> "Bosak Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10912,
                "code"=> "090177",
                "name"=> "Lapo Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10913,
                "code"=> "090178",
                "name"=> "GreenBank Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10914,
                "code"=> "090179",
                "name"=> "FAST Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10915,
                "code"=> "090188",
                "name"=> "Baines Credit Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10916,
                "code"=> "090189",
                "name"=> "Esan Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10917,
                "code"=> "090190",
                "name"=> "Mutual Benefits Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10918,
                "code"=> "090191",
                "name"=> "KCMB Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10919,
                "code"=> "090192",
                "name"=> "Midland Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10920,
                "code"=> "090193",
                "name"=> "Unical Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10921,
                "code"=> "090194",
                "name"=> "NIRSAL Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10922,
                "code"=> "090195",
                "name"=> "Grooming Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10923,
                "code"=> "090196",
                "name"=> "Pennywise Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10924,
                "code"=> "090197",
                "name"=> "ABU Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10925,
                "code"=> "090198",
                "name"=> "RenMoney Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10926,
                "code"=> "090205",
                "name"=> "New Dawn Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10927,
                "code"=> "090251",
                "name"=> "UNN MFB",
                "country_id"=> 154
            ],
            [
                "id"=> 10928,
                "code"=> "090258",
                "name"=> "Imo State Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10929,
                "code"=> "090259",
                "name"=> "Alekun Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10930,
                "code"=> "090260",
                "name"=> "Above Only Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10931,
                "code"=> "090261",
                "name"=> "Quickfund Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10932,
                "code"=> "090262",
                "name"=> "Stellas Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10933,
                "code"=> "090263",
                "name"=> "Navy Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10934,
                "code"=> "090264",
                "name"=> "Auchi Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10935,
                "code"=> "090265",
                "name"=> "Lovonus Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10936,
                "code"=> "090266",
                "name"=> "Uniben Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10937,
                "code"=> "090268",
                "name"=> "Adeyemi College Staff Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10938,
                "code"=> "090269",
                "name"=> "Greenville Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10939,
                "code"=> "090270",
                "name"=> "AB Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10940,
                "code"=> "090271",
                "name"=> "Lavender Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10941,
                "code"=> "090272",
                "name"=> "Olabisi Onabanjo University Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10942,
                "code"=> "090273",
                "name"=> "Emeralds Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10943,
                "code"=> "090276",
                "name"=> "Trustfund Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10944,
                "code"=> "090277",
                "name"=> "Al-Hayat Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10945,
                "code"=> "100001",
                "name"=> "FET",
                "country_id"=> 154
            ],
            [
                "id"=> 10946,
                "code"=> "100003",
                "name"=> "Parkway-ReadyCash",
                "country_id"=> 154
            ],
            [
                "id"=> 10947,
                "code"=> "100005",
                "name"=> "Cellulant",
                "country_id"=> 154
            ],
            [
                "id"=> 10948,
                "code"=> "100006",
                "name"=> "eTranzact",
                "country_id"=> 154
            ],
            [
                "id"=> 10949,
                "code"=> "100007",
                "name"=> "Stanbic IBTC @ease wallet",
                "country_id"=> 154
            ],
            [
                "id"=> 10950,
                "code"=> "100008",
                "name"=> "Ecobank Xpress Account",
                "country_id"=> 154
            ],
            [
                "id"=> 10951,
                "code"=> "100009",
                "name"=> "GTMobile",
                "country_id"=> 154
            ],
            [
                "id"=> 10952,
                "code"=> "100010",
                "name"=> "TeasyMobile",
                "country_id"=> 154
            ],
            [
                "id"=> 10953,
                "code"=> "100011",
                "name"=> "Mkudi",
                "country_id"=> 154
            ],
            [
                "id"=> 10954,
                "code"=> "100012",
                "name"=> "VTNetworks",
                "country_id"=> 154
            ],
            [
                "id"=> 10955,
                "code"=> "100013",
                "name"=> "AccessMobile",
                "country_id"=> 154
            ],
            [
                "id"=> 10956,
                "code"=> "100014",
                "name"=> "FBNMobile",
                "country_id"=> 154
            ],
            [
                "id"=> 10957,
                "code"=> "100015",
                "name"=> "Kegow",
                "country_id"=> 154
            ],
            [
                "id"=> 10958,
                "code"=> "100016",
                "name"=> "FortisMobile",
                "country_id"=> 154
            ],
            [
                "id"=> 10959,
                "code"=> "100017",
                "name"=> "Hedonmark",
                "country_id"=> 154
            ],
            [
                "id"=> 10960,
                "code"=> "100018",
                "name"=> "ZenithMobile",
                "country_id"=> 154
            ],
            [
                "id"=> 10961,
                "code"=> "110002",
                "name"=> "Flutterwave Technology Solutions Limited",
                "country_id"=> 154
            ],
            [
                "id"=> 10962,
                "code"=> "999999",
                "name"=> "NIP Virtual Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10964,
                "code"=> "000025",
                "name"=> "Titan Trust Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10965,
                "code"=> "303",
                "name"=> "ChamsMobile",
                "country_id"=> 154
            ],
            [
                "id"=> 10986,
                "code"=> "090423",
                "name"=> "MAUTECH Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10987,
                "code"=> "060004",
                "name"=> "Greenwich Merchant Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10988,
                "code"=> "000030",
                "name"=> "Parallex Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 10989,
                "code"=> "090366",
                "name"=> "Firmus MFB",
                "country_id"=> 154
            ],
            [
                "id"=> 10990,
                "code"=> "100033",
                "name"=> "PALMPAY",
                "country_id"=> 154
            ],
            [
                "id"=> 10991,
                "code"=> "090286",
                "name"=> "Safe Haven MFB",
                "country_id"=> 154
            ],
            [
                "id"=> 10993,
                "code"=> "MPS",
                "name"=> "Mobile Money",
                "country_id"=> 181
            ],
            [
                "id"=> 10994,
                "code"=> "11128722",
                "name"=> "ZIGAMA CSS",
                "country_id"=> 181
            ],
            [
                "id"=> 10995,
                "code"=> "11128726",
                "name"=> "Unguka Bank Ltd",
                "country_id"=> 181
            ],
            [
                "id"=> 10996,
                "code"=> "11128730",
                "name"=> "Bank of Africa Rwanda PLC",
                "country_id"=> 181
            ],
            [
                "id"=> 10997,
                "code"=> "11128734",
                "name"=> "Urwego Opportunity Bank of Rwanda SA",
                "country_id"=> 181
            ],
            [
                "id"=> 10998,
                "code"=> "11222892",
                "name"=> "AB Bank Rwanda Limited",
                "country_id"=> 181
            ],
            [
                "id"=> 10999,
                "code"=> "2006018AN0",
                "name"=> "UMWALIMU SACCO",
                "country_id"=> 181
            ],
            [
                "id"=> 11000,
                "code"=> "20543100",
                "name"=> "I and M Bank (Rwanda) Limited",
                "country_id"=> 181
            ],
            [
                "id"=> 11001,
                "code"=> "20543200",
                "name"=> "Guaranty Trust Bank Rwanda Limited",
                "country_id"=> 181
            ],
            [
                "id"=> 11002,
                "code"=> "20543300",
                "name"=> "Bank of Kigali Limited",
                "country_id"=> 181
            ],
            [
                "id"=> 11003,
                "code"=> "20543400",
                "name"=> "Banque Nationale du Rwanda",
                "country_id"=> 181
            ],
            [
                "id"=> 11004,
                "code"=> "20543500",
                "name"=> "Banque Rwandaise de Developpement SA (BRD)",
                "country_id"=> 181
            ],
            [
                "id"=> 11005,
                "code"=> "20543600",
                "name"=> "Banque Populaire du Rwanda",
                "country_id"=> 181
            ],
            [
                "id"=> 11006,
                "code"=> "26145468",
                "name"=> "KCB Bank Rwanda Limited",
                "country_id"=> 181
            ],
            [
                "id"=> 11007,
                "code"=> "26693730",
                "name"=> "Equity Bank Limited - Rwanda",
                "country_id"=> 181
            ],
            [
                "id"=> 11008,
                "code"=> "27201842",
                "name"=> "Crane Bank Rwanda Ltd",
                "country_id"=> 181
            ],
            [
                "id"=> 11009,
                "code"=> "97449864",
                "name"=> "Access Bank (Rwanda) Limited",
                "country_id"=> 181
            ],
            [
                "id"=> 11010,
                "code"=> "97449866",
                "name"=> "Compagnie Generale de Banque",
                "country_id"=> 181
            ],
            [
                "id"=> 11011,
                "code"=> "RW100000",
                "name"=> "Ecobank Rwanda",
                "country_id"=> 181
            ],
            [
                "id"=> 11012,
                "code"=> "MTN",
                "name"=> "MTN Mobile Money",
                "country_id"=> 76
            ],
            [
                "id"=> 11013,
                "code"=> "TIGO",
                "name"=> "TIGO Mobile Money",
                "country_id"=> 76
            ],
            [
                "id"=> 11014,
                "code"=> "VODAFONE",
                "name"=> "Vodafone Mobile Money",
                "country_id"=> 76
            ],
            [
                "id"=> 11015,
                "code"=> "AIRTEL",
                "name"=> "Airtel Mobile Money",
                "country_id"=> 76
            ],
            [
                "id"=> 11016,
                "code"=> "10550214",
                "name"=> "The Hongkong and Shanghai Banking Corporation Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11017,
                "code"=> "11088200",
                "name"=> "Giro Elszamolasforgalmi Zrt.",
                "country_id"=> 76
            ],
            [
                "id"=> 11018,
                "code"=> "11276282",
                "name"=> "First National Bank Ghana Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11019,
                "code"=> "11299458",
                "name"=> "OMNIBANK GHANA LIMITED",
                "country_id"=> 76
            ],
            [
                "id"=> 11020,
                "code"=> "11319374",
                "name"=> "GHL Bank Ltd",
                "country_id"=> 76
            ],
            [
                "id"=> 11021,
                "code"=> "11350352",
                "name"=> "Consolidated Bank Ghana Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11022,
                "code"=> "1812142W72",
                "name"=> "GH PREPAID",
                "country_id"=> 76
            ],
            [
                "id"=> 11023,
                "code"=> "190815670S",
                "name"=> "VISA",
                "country_id"=> 76
            ],
            [
                "id"=> 11024,
                "code"=> "2006168ICS",
                "name"=> "Standard Chartered Bk Ghana Int",
                "country_id"=> 76
            ],
            [
                "id"=> 11025,
                "code"=> "2006228LG0",
                "name"=> "UNITED BANK FOR AFRICA (GHANA) LTD",
                "country_id"=> 76
            ],
            [
                "id"=> 11026,
                "code"=> "20112200",
                "name"=> "Partner Banka d.d.",
                "country_id"=> 76
            ],
            [
                "id"=> 11027,
                "code"=> "20313200",
                "name"=> "Agricultural Development Bank Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11028,
                "code"=> "20313400",
                "name"=> "Bank of Ghana",
                "country_id"=> 76
            ],
            [
                "id"=> 11029,
                "code"=> "20313500",
                "name"=> "Barclays Bank of Ghana Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11030,
                "code"=> "20313600",
                "name"=> "GCB Bank Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11031,
                "code"=> "20313800",
                "name"=> "Universal Merchant Bank",
                "country_id"=> 76
            ],
            [
                "id"=> 11032,
                "code"=> "20313900",
                "name"=> "National Investment Bank Ltd",
                "country_id"=> 76
            ],
            [
                "id"=> 11033,
                "code"=> "20314100",
                "name"=> "Societe Generale Ghana Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11034,
                "code"=> "20314200",
                "name"=> "Standard Chartered Bank Ghana Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11035,
                "code"=> "20321900",
                "name"=> "Societe Generale Haitienne de Banque S.A. (Sogebank)",
                "country_id"=> 76
            ],
            [
                "id"=> 11036,
                "code"=> "20497300",
                "name"=> "BNG Bank N V",
                "country_id"=> 76
            ],
            [
                "id"=> 11037,
                "code"=> "22031960",
                "name"=> "Cal Bank Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11038,
                "code"=> "25449292",
                "name"=> "DBS Group Holdings Ltd",
                "country_id"=> 76
            ],
            [
                "id"=> 11039,
                "code"=> "25528546",
                "name"=> "Financial Brokerage Group (Fbg)",
                "country_id"=> 76
            ],
            [
                "id"=> 11040,
                "code"=> "25544088",
                "name"=> "Arb Apex Bank Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11041,
                "code"=> "25549518",
                "name"=> "Stock Exchange Of Hong Kong Ltd, The",
                "country_id"=> 76
            ],
            [
                "id"=> 11042,
                "code"=> "25562284",
                "name"=> "Guaranty Trust Bank (Ghana) Ltd",
                "country_id"=> 76
            ],
            [
                "id"=> 11043,
                "code"=> "25570554",
                "name"=> "Fidelity Bank Ghana Ltd.",
                "country_id"=> 76
            ],
            [
                "id"=> 11044,
                "code"=> "25785380",
                "name"=> "Banque Sahelo-Saharienne Pour LInvestissment et le Commerce (Ghana) Lt",
                "country_id"=> 76
            ],
            [
                "id"=> 11045,
                "code"=> "25817360",
                "name"=> "Guaranty Trust Bank (UK) Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11046,
                "code"=> "25892776",
                "name"=> "Bank of Baroda (Ghana) Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11047,
                "code"=> "25897096",
                "name"=> "Gatehouse Bank Plc",
                "country_id"=> 76
            ],
            [
                "id"=> 11048,
                "code"=> "26153374",
                "name"=> "Access Bank (Ghana) PLC",
                "country_id"=> 76
            ],
            [
                "id"=> 11049,
                "code"=> "26153460",
                "name"=> "Noble Group Ltd",
                "country_id"=> 76
            ],
            [
                "id"=> 11050,
                "code"=> "26156818",
                "name"=> "Ghazanfar Bank",
                "country_id"=> 76
            ],
            [
                "id"=> 11051,
                "code"=> "26545578",
                "name"=> "Social Security And National Insurance Trust",
                "country_id"=> 76
            ],
            [
                "id"=> 11052,
                "code"=> "26675842",
                "name"=> "Energy Bank Ghana Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11053,
                "code"=> "26699422",
                "name"=> "Ghana Revenue Authority",
                "country_id"=> 76
            ],
            [
                "id"=> 11054,
                "code"=> "26935088",
                "name"=> "Controller And Accountant-GeneralS Department",
                "country_id"=> 76
            ],
            [
                "id"=> 11055,
                "code"=> "26708398",
                "name"=> "Pacific Eagle Asset Management Ltd",
                "country_id"=> 76
            ],
            [
                "id"=> 11056,
                "code"=> "26852136",
                "name"=> "Independent Petroleum Group",
                "country_id"=> 76
            ],
            [
                "id"=> 11057,
                "code"=> "26935170",
                "name"=> "Partners Group AG",
                "country_id"=> 76
            ],
            [
                "id"=> 11058,
                "code"=> "26955918",
                "name"=> "Compass Global Holdings Pty Ltd",
                "country_id"=> 76
            ],
            [
                "id"=> 11059,
                "code"=> "26699422",
                "name"=> "Global Exchange Centre Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11060,
                "code"=> "27108818",
                "name"=> "Volta River Authority",
                "country_id"=> 76
            ],
            [
                "id"=> 11061,
                "code"=> "27127004",
                "name"=> "Norma Group Holding",
                "country_id"=> 76
            ],
            [
                "id"=> 11062,
                "code"=> "27208826",
                "name"=> "Tronox Global Holdings Pty Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11063,
                "code"=> "27209142",
                "name"=> "Cqlt Saargummi Technologies SAR.l",
                "country_id"=> 76
            ],
            [
                "id"=> 11064,
                "code"=> "27257744",
                "name"=> "Pigeon Corporation",
                "country_id"=> 76
            ],
            [
                "id"=> 11065,
                "code"=> "27349456",
                "name"=> "Ping An Of China Asset Management (Hong Kong) Company Ltd",
                "country_id"=> 76
            ],
            [
                "id"=> 11066,
                "code"=> "27352014",
                "name"=> "Gebr. Heinemann Se And Co.KG",
                "country_id"=> 76
            ],
            [
                "id"=> 11067,
                "code"=> "27404306",
                "name"=> "Cgnpc Huasheng Investment Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11068,
                "code"=> "27451510",
                "name"=> "Premium Bank Ghana Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11069,
                "code"=> "27452668",
                "name"=> "G.H. Financials Ltd",
                "country_id"=> 76
            ],
            [
                "id"=> 11070,
                "code"=> "27457008",
                "name"=> "Asahi Group Holdings, Ltd.",
                "country_id"=> 76
            ],
            [
                "id"=> 11071,
                "code"=> "27462484",
                "name"=> "Heritage Bank Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11072,
                "code"=> "27464082",
                "name"=> "Grabtaxi Holdings PTE LTD",
                "country_id"=> 76
            ],
            [
                "id"=> 11073,
                "code"=> "27466360",
                "name"=> "Al Ghurair International Exchange",
                "country_id"=> 76
            ],
            [
                "id"=> 11074,
                "code"=> "27547278",
                "name"=> "Dogan Sirketler Grubu Holding AS",
                "country_id"=> 76
            ],
            [
                "id"=> 11075,
                "code"=> "50480908",
                "name"=> "GN Bank Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11076,
                "code"=> "60002121",
                "name"=> "Banque Populaire de Rabat-Kenitra",
                "country_id"=> 76
            ],
            [
                "id"=> 11077,
                "code"=> "60003190",
                "name"=> "Prudential Bank Ltd",
                "country_id"=> 76
            ],
            [
                "id"=> 11078,
                "code"=> "60003191",
                "name"=> "First Atlantic Bank Ltd",
                "country_id"=> 76
            ],
            [
                "id"=> 11079,
                "code"=> "60003193",
                "name"=> "FBN Bank (Ghana) Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11080,
                "code"=> "96683220",
                "name"=> "Privredna banka Zagreb d.d.",
                "country_id"=> 76
            ],
            [
                "id"=> 11081,
                "code"=> "96788653",
                "name"=> "Ghana International Bank plc",
                "country_id"=> 76
            ],
            [
                "id"=> 11082,
                "code"=> "96793657",
                "name"=> "Republic Bank (Ghana) Ltd",
                "country_id"=> 76
            ],
            [
                "id"=> 11083,
                "code"=> "96855152",
                "name"=> "Guaranty Trust Bank Kenya Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11084,
                "code"=> "97151844",
                "name"=> "Stanbic Bank Ghana Limited",
                "country_id"=> 76
            ],
            [
                "id"=> 11085,
                "code"=> "97155860",
                "name"=> "Bank of Africa - Ghana",
                "country_id"=> 76
            ],
            [
                "id"=> 11086,
                "code"=> "98357706",
                "name"=> "ZENITH BANK (GHANA) LIMITED",
                "country_id"=> 76
            ],
            [
                "id"=> 11087,
                "code"=> "99730556",
                "name"=> "FINANCIJSKA AGENCIJA",
                "country_id"=> 76
            ],
            [
                "id"=> 11088,
                "code"=> "GH130100",
                "name"=> "Ecobank Ghana (GH130100)",
                "country_id"=> 76
            ],
            [
                "id"=> 11089,
                "code"=> "01",
                "name"=> "Kenya Commercial Bank Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11090,
                "code"=> "11",
                "name"=> "Co-operative Bank of Kenya Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11091,
                "code"=> "31",
                "name"=> "CFC Stanbic Bank Kenya Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11092,
                "code"=> "51",
                "name"=> "Kingdom Bank(Jamii Bora Bank)",
                "country_id"=> 106
            ],
            [
                "id"=> 11093,
                "code"=> "61",
                "name"=> "Housing Finance Bank",
                "country_id"=> 106
            ],
            [
                "id"=> 11094,
                "code"=> "14",
                "name"=> "M-Oriental Commercial Bank",
                "country_id"=> 106
            ],
            [
                "id"=> 11095,
                "code"=> "54",
                "name"=> "Victoria Commercial Bank Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11096,
                "code"=> "49",
                "name"=> "Spire Bank(Equatorial Commercial Bank Limited)",
                "country_id"=> 106
            ],
            [
                "id"=> 11097,
                "code"=> "57",
                "name"=> "Investments n Mortgages Bank Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11098,
                "code"=> "12",
                "name"=> "National Bank of Kenya Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11099,
                "code"=> "72",
                "name"=> "Gulf African Bank Ltd",
                "country_id"=> 106
            ],
            [
                "id"=> 11100,
                "code"=> "25",
                "name"=> "Credit Bank Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11101,
                "code"=> "35",
                "name"=> "African Banking Corp. Bank Ltd",
                "country_id"=> 106
            ],
            [
                "id"=> 11102,
                "code"=> "74",
                "name"=> "First Community Bank",
                "country_id"=> 106
            ],
            [
                "id"=> 11103,
                "code"=> "55",
                "name"=> "Guardian Bank Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11104,
                "code"=> "10",
                "name"=> "Prime Bank Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11105,
                "code"=> "20",
                "name"=> "Dubai Bank Kenya Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11106,
                "code"=> "50",
                "name"=> "Paramount Universal Bank Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11107,
                "code"=> "70",
                "name"=> "Family Bank Ltd",
                "country_id"=> 106
            ],
            [
                "id"=> 11108,
                "code"=> "23",
                "name"=> "Consolidated Bank of Kenya Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11109,
                "code"=> "18",
                "name"=> "Middle East Bank Kenya Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11110,
                "code"=> "63",
                "name"=> "Diamond Trust Bank Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11111,
                "code"=> "16",
                "name"=> "Citibank N.A.",
                "country_id"=> 106
            ],
            [
                "id"=> 11112,
                "code"=> "26",
                "name"=> "Access Bank(Trans-National Bank Limited)",
                "country_id"=> 106
            ],
            [
                "id"=> 11113,
                "code"=> "68",
                "name"=> "Equity Bank Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11114,
                "code"=> "66",
                "name"=> "Sidian Bank(K-Rep Bank Limited)",
                "country_id"=> 106
            ],
            [
                "id"=> 11115,
                "code"=> "76",
                "name"=> "UBA Kenya Bank Ltd",
                "country_id"=> 106
            ],
            [
                "id"=> 11116,
                "code"=> "19",
                "name"=> "Bank of  Africa Kenya Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11117,
                "code"=> "MPS",
                "name"=> "MPESA",
                "country_id"=> 106
            ],
            [
                "id"=> 11118,
                "code"=> "03",
                "name"=> "Absa Bank Kenya Plc",
                "country_id"=> 106
            ],
            [
                "id"=> 11119,
                "code"=> "53",
                "name"=> "Guaranty Trust Bank",
                "country_id"=> 106
            ],
            [
                "id"=> 11120,
                "code"=> "43",
                "name"=> "EcoBank PLC",
                "country_id"=> 106
            ],
            [
                "id"=> 11121,
                "code"=> "02",
                "name"=> "Standard Chartered Bank Limited",
                "country_id"=> 106
            ],
            [
                "id"=> 11122,
                "code"=> "07",
                "name"=> "NCBA Bank Kenya PLC",
                "country_id"=> 106
            ],
            [
                "id"=> 11123,
                "code"=> "MPX",
                "name"=> "Airtel Kenya",
                "country_id"=> 106
            ],
            [
                "id"=> 11124,
                "code"=> "090482",
                "name"=> "Fedeth MFB",
                "country_id"=> 154
            ],
            [
                "id"=> 11125,
                "code"=> "100026",
                "name"=> "Carbon",
                "country_id"=> 154
            ],
            [
                "id"=> 11126,
                "code"=> "090470",
                "name"=> "CHANGHAN RTS MICROFINANCE BANK",
                "country_id"=> 154
            ],
            [
                "id"=> 11127,
                "code"=> "090435",
                "name"=> "Links Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 11128,
                "code"=> "110007",
                "name"=> "Moniepoint MFB",
                "country_id"=> 154
            ],
            [
                "id"=> 11129,
                "code"=> "000036",
                "name"=> "Optimus Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 11130,
                "code"=> "090480",
                "name"=> "Cintrust Microfinance Bank",
                "country_id"=> 154
            ],
            [
                "id"=> 11131,
                "code"=> "303-150013",
                "name"=> "Lotus Bank",
                "country_id"=> 154
            ]
        ];

        return $banks;
    }

    public static function getCurrencies(){
        $currencies =
        [
            "USD"=> [
                "symbol"=> "$",
                "name"=> "US Dollar",
                "symbol_native"=> "$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "USD",
                "name_plural"=> "US dollars"
            ],
            "CAD"=> [
                "symbol"=> "CA$",
                "name"=> "Canadian Dollar",
                "symbol_native"=> "$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "CAD",
                "name_plural"=> "Canadian dollars"
            ],
            "EUR"=> [
                "symbol"=> "\u20ac",
                "name"=> "Euro",
                "symbol_native"=> "\u20ac",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "EUR",
                "name_plural"=> "euros"
            ],
            "AED"=> [
                "symbol"=> "AED",
                "name"=> "United Arab Emirates Dirham",
                "symbol_native"=> "\u062f.\u0625.\u200f",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "AED",
                "name_plural"=> "UAE dirhams"
            ],
            "AFN"=> [
                "symbol"=> "Af",
                "name"=> "Afghan Afghani",
                "symbol_native"=> "\u060b",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "AFN",
                "name_plural"=> "Afghan Afghanis"
            ],
            "ALL"=> [
                "symbol"=> "ALL",
                "name"=> "Albanian Lek",
                "symbol_native"=> "Lek",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "ALL",
                "name_plural"=> "Albanian lek\u00eb"
            ],
            "AMD"=> [
                "symbol"=> "AMD",
                "name"=> "Armenian Dram",
                "symbol_native"=> "\u0564\u0580.",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "AMD",
                "name_plural"=> "Armenian drams"
            ],
            "ARS"=> [
                "symbol"=> "AR$",
                "name"=> "Argentine Peso",
                "symbol_native"=> "$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "ARS",
                "name_plural"=> "Argentine pesos"
            ],
            "AUD"=> [
                "symbol"=> "AU$",
                "name"=> "Australian Dollar",
                "symbol_native"=> "$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "AUD",
                "name_plural"=> "Australian dollars"
            ],
            "AZN"=> [
                "symbol"=> "man.",
                "name"=> "Azerbaijani Manat",
                "symbol_native"=> "\u043c\u0430\u043d.",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "AZN",
                "name_plural"=> "Azerbaijani manats"
            ],
            "BAM"=> [
                "symbol"=> "KM",
                "name"=> "Bosnia-Herzegovina Convertible Mark",
                "symbol_native"=> "KM",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "BAM",
                "name_plural"=> "Bosnia-Herzegovina convertible marks"
            ],
            "BDT"=> [
                "symbol"=> "Tk",
                "name"=> "Bangladeshi Taka",
                "symbol_native"=> "\u09f3",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "BDT",
                "name_plural"=> "Bangladeshi takas"
            ],
            "BGN"=> [
                "symbol"=> "BGN",
                "name"=> "Bulgarian Lev",
                "symbol_native"=> "\u043b\u0432.",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "BGN",
                "name_plural"=> "Bulgarian leva"
            ],
            "BHD"=> [
                "symbol"=> "BD",
                "name"=> "Bahraini Dinar",
                "symbol_native"=> "\u062f.\u0628.\u200f",
                "decimal_digits"=> 3,
                "rounding"=> 0,
                "code"=> "BHD",
                "name_plural"=> "Bahraini dinars"
            ],
            "BIF"=> [
                "symbol"=> "FBu",
                "name"=> "Burundian Franc",
                "symbol_native"=> "FBu",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "BIF",
                "name_plural"=> "Burundian francs"
            ],
            "BND"=> [
                "symbol"=> "BN$",
                "name"=> "Brunei Dollar",
                "symbol_native"=> "$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "BND",
                "name_plural"=> "Brunei dollars"
            ],
            "BOB"=> [
                "symbol"=> "Bs",
                "name"=> "Bolivian Boliviano",
                "symbol_native"=> "Bs",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "BOB",
                "name_plural"=> "Bolivian bolivianos"
            ],
            "BRL"=> [
                "symbol"=> "R$",
                "name"=> "Brazilian Real",
                "symbol_native"=> "R$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "BRL",
                "name_plural"=> "Brazilian reals"
            ],
            "BWP"=> [
                "symbol"=> "P",
                "name"=> "Botswanan Pula",
                "symbol_native"=> "P",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "BWP",
                "name_plural"=> "Botswanan pulas"
            ],
            "BYN"=> [
                "symbol"=> "Br",
                "name"=> "Belarusian Ruble",
                "symbol_native"=> "\u0440\u0443\u0431.",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "BYN",
                "name_plural"=> "Belarusian rubles"
            ],
            "BZD"=> [
                "symbol"=> "BZ$",
                "name"=> "Belize Dollar",
                "symbol_native"=> "$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "BZD",
                "name_plural"=> "Belize dollars"
            ],
            "CDF"=> [
                "symbol"=> "CDF",
                "name"=> "Congolese Franc",
                "symbol_native"=> "FrCD",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "CDF",
                "name_plural"=> "Congolese francs"
            ],
            "CHF"=> [
                "symbol"=> "CHF",
                "name"=> "Swiss Franc",
                "symbol_native"=> "CHF",
                "decimal_digits"=> 2,
                "rounding"=> 0.05,
                "code"=> "CHF",
                "name_plural"=> "Swiss francs"
            ],
            "CLP"=> [
                "symbol"=> "CL$",
                "name"=> "Chilean Peso",
                "symbol_native"=> "$",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "CLP",
                "name_plural"=> "Chilean pesos"
            ],
            "CNY"=> [
                "symbol"=> "CN\u00a5",
                "name"=> "Chinese Yuan",
                "symbol_native"=> "CN\u00a5",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "CNY",
                "name_plural"=> "Chinese yuan"
            ],
            "COP"=> [
                "symbol"=> "CO$",
                "name"=> "Colombian Peso",
                "symbol_native"=> "$",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "COP",
                "name_plural"=> "Colombian pesos"
            ],
            "CRC"=> [
                "symbol"=> "\u20a1",
                "name"=> "Costa Rican Col\u00f3n",
                "symbol_native"=> "\u20a1",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "CRC",
                "name_plural"=> "Costa Rican col\u00f3ns"
            ],
            "CVE"=> [
                "symbol"=> "CV$",
                "name"=> "Cape Verdean Escudo",
                "symbol_native"=> "CV$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "CVE",
                "name_plural"=> "Cape Verdean escudos"
            ],
            "CZK"=> [
                "symbol"=> "K\u010d",
                "name"=> "Czech Republic Koruna",
                "symbol_native"=> "K\u010d",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "CZK",
                "name_plural"=> "Czech Republic korunas"
            ],
            "DJF"=> [
                "symbol"=> "Fdj",
                "name"=> "Djiboutian Franc",
                "symbol_native"=> "Fdj",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "DJF",
                "name_plural"=> "Djiboutian francs"
            ],
            "DKK"=> [
                "symbol"=> "Dkr",
                "name"=> "Danish Krone",
                "symbol_native"=> "kr",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "DKK",
                "name_plural"=> "Danish kroner"
            ],
            "DOP"=> [
                "symbol"=> "RD$",
                "name"=> "Dominican Peso",
                "symbol_native"=> "RD$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "DOP",
                "name_plural"=> "Dominican pesos"
            ],
            "DZD"=> [
                "symbol"=> "DA",
                "name"=> "Algerian Dinar",
                "symbol_native"=> "\u062f.\u062c.\u200f",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "DZD",
                "name_plural"=> "Algerian dinars"
            ],
            "EEK"=> [
                "symbol"=> "Ekr",
                "name"=> "Estonian Kroon",
                "symbol_native"=> "kr",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "EEK",
                "name_plural"=> "Estonian kroons"
            ],
            "EGP"=> [
                "symbol"=> "EGP",
                "name"=> "Egyptian Pound",
                "symbol_native"=> "\u062c.\u0645.\u200f",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "EGP",
                "name_plural"=> "Egyptian pounds"
            ],
            "ERN"=> [
                "symbol"=> "Nfk",
                "name"=> "Eritrean Nakfa",
                "symbol_native"=> "Nfk",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "ERN",
                "name_plural"=> "Eritrean nakfas"
            ],
            "ETB"=> [
                "symbol"=> "Br",
                "name"=> "Ethiopian Birr",
                "symbol_native"=> "Br",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "ETB",
                "name_plural"=> "Ethiopian birrs"
            ],
            "GBP"=> [
                "symbol"=> "\u00a3",
                "name"=> "British Pound Sterling",
                "symbol_native"=> "\u00a3",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "GBP",
                "name_plural"=> "British pounds sterling"
            ],
            "GEL"=> [
                "symbol"=> "GEL",
                "name"=> "Georgian Lari",
                "symbol_native"=> "GEL",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "GEL",
                "name_plural"=> "Georgian laris"
            ],
            "GHS"=> [
                "symbol"=> "GH\u20b5",
                "name"=> "Ghanaian Cedi",
                "symbol_native"=> "GH\u20b5",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "GHS",
                "name_plural"=> "Ghanaian cedis"
            ],
            "GNF"=> [
                "symbol"=> "FG",
                "name"=> "Guinean Franc",
                "symbol_native"=> "FG",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "GNF",
                "name_plural"=> "Guinean francs"
            ],
            "GTQ"=> [
                "symbol"=> "GTQ",
                "name"=> "Guatemalan Quetzal",
                "symbol_native"=> "Q",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "GTQ",
                "name_plural"=> "Guatemalan quetzals"
            ],
            "HKD"=> [
                "symbol"=> "HK$",
                "name"=> "Hong Kong Dollar",
                "symbol_native"=> "$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "HKD",
                "name_plural"=> "Hong Kong dollars"
            ],
            "HNL"=> [
                "symbol"=> "HNL",
                "name"=> "Honduran Lempira",
                "symbol_native"=> "L",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "HNL",
                "name_plural"=> "Honduran lempiras"
            ],
            "HRK"=> [
                "symbol"=> "kn",
                "name"=> "Croatian Kuna",
                "symbol_native"=> "kn",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "HRK",
                "name_plural"=> "Croatian kunas"
            ],
            "HUF"=> [
                "symbol"=> "Ft",
                "name"=> "Hungarian Forint",
                "symbol_native"=> "Ft",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "HUF",
                "name_plural"=> "Hungarian forints"
            ],
            "IDR"=> [
                "symbol"=> "Rp",
                "name"=> "Indonesian Rupiah",
                "symbol_native"=> "Rp",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "IDR",
                "name_plural"=> "Indonesian rupiahs"
            ],
            "ILS"=> [
                "symbol"=> "\u20aa",
                "name"=> "Israeli New Sheqel",
                "symbol_native"=> "\u20aa",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "ILS",
                "name_plural"=> "Israeli new sheqels"
            ],
            "INR"=> [
                "symbol"=> "Rs",
                "name"=> "Indian Rupee",
                "symbol_native"=> "\u20b9",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "INR",
                "name_plural"=> "Indian rupees"
            ],
            "IQD"=> [
                "symbol"=> "IQD",
                "name"=> "Iraqi Dinar",
                "symbol_native"=> "\u062f.\u0639.\u200f",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "IQD",
                "name_plural"=> "Iraqi dinars"
            ],
            "IRR"=> [
                "symbol"=> "IRR",
                "name"=> "Iranian Rial",
                "symbol_native"=> "\ufdfc",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "IRR",
                "name_plural"=> "Iranian rials"
            ],
            "ISK"=> [
                "symbol"=> "Ikr",
                "name"=> "Icelandic Kr\u00f3na",
                "symbol_native"=> "kr",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "ISK",
                "name_plural"=> "Icelandic kr\u00f3nur"
            ],
            "JMD"=> [
                "symbol"=> "J$",
                "name"=> "Jamaican Dollar",
                "symbol_native"=> "$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "JMD",
                "name_plural"=> "Jamaican dollars"
            ],
            "JOD"=> [
                "symbol"=> "JD",
                "name"=> "Jordanian Dinar",
                "symbol_native"=> "\u062f.\u0623.\u200f",
                "decimal_digits"=> 3,
                "rounding"=> 0,
                "code"=> "JOD",
                "name_plural"=> "Jordanian dinars"
            ],
            "JPY"=> [
                "symbol"=> "\u00a5",
                "name"=> "Japanese Yen",
                "symbol_native"=> "\uffe5",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "JPY",
                "name_plural"=> "Japanese yen"
            ],
            "KES"=> [
                "symbol"=> "KSh",
                "name"=> "Kenyan Shilling",
                "symbol_native"=> "KSh",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "KES",
                "name_plural"=> "Kenyan shillings"
            ],
            "KHR"=> [
                "symbol"=> "KHR",
                "name"=> "Cambodian Riel",
                "symbol_native"=> "\u17db",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "KHR",
                "name_plural"=> "Cambodian riels"
            ],
            "KMF"=> [
                "symbol"=> "CF",
                "name"=> "Comorian Franc",
                "symbol_native"=> "FC",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "KMF",
                "name_plural"=> "Comorian francs"
            ],
            "KRW"=> [
                "symbol"=> "\u20a9",
                "name"=> "South Korean Won",
                "symbol_native"=> "\u20a9",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "KRW",
                "name_plural"=> "South Korean won"
            ],
            "KWD"=> [
                "symbol"=> "KD",
                "name"=> "Kuwaiti Dinar",
                "symbol_native"=> "\u062f.\u0643.\u200f",
                "decimal_digits"=> 3,
                "rounding"=> 0,
                "code"=> "KWD",
                "name_plural"=> "Kuwaiti dinars"
            ],
            "KZT"=> [
                "symbol"=> "KZT",
                "name"=> "Kazakhstani Tenge",
                "symbol_native"=> "\u0442\u04a3\u0433.",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "KZT",
                "name_plural"=> "Kazakhstani tenges"
            ],
            "LBP"=> [
                "symbol"=> "L.L.",
                "name"=> "Lebanese Pound",
                "symbol_native"=> "\u0644.\u0644.\u200f",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "LBP",
                "name_plural"=> "Lebanese pounds"
            ],
            "LKR"=> [
                "symbol"=> "SLRs",
                "name"=> "Sri Lankan Rupee",
                "symbol_native"=> "SL Re",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "LKR",
                "name_plural"=> "Sri Lankan rupees"
            ],
            "LTL"=> [
                "symbol"=> "Lt",
                "name"=> "Lithuanian Litas",
                "symbol_native"=> "Lt",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "LTL",
                "name_plural"=> "Lithuanian litai"
            ],
            "LVL"=> [
                "symbol"=> "Ls",
                "name"=> "Latvian Lats",
                "symbol_native"=> "Ls",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "LVL",
                "name_plural"=> "Latvian lati"
            ],
            "LYD"=> [
                "symbol"=> "LD",
                "name"=> "Libyan Dinar",
                "symbol_native"=> "\u062f.\u0644.\u200f",
                "decimal_digits"=> 3,
                "rounding"=> 0,
                "code"=> "LYD",
                "name_plural"=> "Libyan dinars"
            ],
            "MAD"=> [
                "symbol"=> "MAD",
                "name"=> "Moroccan Dirham",
                "symbol_native"=> "\u062f.\u0645.\u200f",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "MAD",
                "name_plural"=> "Moroccan dirhams"
            ],
            "MDL"=> [
                "symbol"=> "MDL",
                "name"=> "Moldovan Leu",
                "symbol_native"=> "MDL",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "MDL",
                "name_plural"=> "Moldovan lei"
            ],
            "MGA"=> [
                "symbol"=> "MGA",
                "name"=> "Malagasy Ariary",
                "symbol_native"=> "MGA",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "MGA",
                "name_plural"=> "Malagasy Ariaries"
            ],
            "MKD"=> [
                "symbol"=> "MKD",
                "name"=> "Macedonian Denar",
                "symbol_native"=> "MKD",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "MKD",
                "name_plural"=> "Macedonian denari"
            ],
            "MMK"=> [
                "symbol"=> "MMK",
                "name"=> "Myanma Kyat",
                "symbol_native"=> "K",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "MMK",
                "name_plural"=> "Myanma kyats"
            ],
            "MOP"=> [
                "symbol"=> "MOP$",
                "name"=> "Macanese Pataca",
                "symbol_native"=> "MOP$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "MOP",
                "name_plural"=> "Macanese patacas"
            ],
            "MUR"=> [
                "symbol"=> "MURs",
                "name"=> "Mauritian Rupee",
                "symbol_native"=> "MURs",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "MUR",
                "name_plural"=> "Mauritian rupees"
            ],
            "MXN"=> [
                "symbol"=> "MX$",
                "name"=> "Mexican Peso",
                "symbol_native"=> "$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "MXN",
                "name_plural"=> "Mexican pesos"
            ],
            "MYR"=> [
                "symbol"=> "RM",
                "name"=> "Malaysian Ringgit",
                "symbol_native"=> "RM",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "MYR",
                "name_plural"=> "Malaysian ringgits"
            ],
            "MZN"=> [
                "symbol"=> "MTn",
                "name"=> "Mozambican Metical",
                "symbol_native"=> "MTn",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "MZN",
                "name_plural"=> "Mozambican meticals"
            ],
            "NAD"=> [
                "symbol"=> "N$",
                "name"=> "Namibian Dollar",
                "symbol_native"=> "N$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "NAD",
                "name_plural"=> "Namibian dollars"
            ],
            "NGN"=> [
                "symbol"=> "\u20a6",
                "name"=> "Nigerian Naira",
                "symbol_native"=> "\u20a6",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "name_plural"=> "Nigerian nairas",
                "code"=> "NGN"
            ],
            "NIO"=> [
                "symbol"=> "C$",
                "name"=> "Nicaraguan C\u00f3rdoba",
                "symbol_native"=> "C$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "NIO",
                "name_plural"=> "Nicaraguan c\u00f3rdobas"
            ],
            "NOK"=> [
                "symbol"=> "Nkr",
                "name"=> "Norwegian Krone",
                "symbol_native"=> "kr",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "NOK",
                "name_plural"=> "Norwegian kroner"
            ],
            "NPR"=> [
                "symbol"=> "NPRs",
                "name"=> "Nepalese Rupee",
                "symbol_native"=> "\u0928\u0947\u0930\u0942",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "NPR",
                "name_plural"=> "Nepalese rupees"
            ],
            "NZD"=> [
                "symbol"=> "NZ$",
                "name"=> "New Zealand Dollar",
                "symbol_native"=> "$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "NZD",
                "name_plural"=> "New Zealand dollars"
            ],
            "OMR"=> [
                "symbol"=> "OMR",
                "name"=> "Omani Rial",
                "symbol_native"=> "\u0631.\u0639.\u200f",
                "decimal_digits"=> 3,
                "rounding"=> 0,
                "code"=> "OMR",
                "name_plural"=> "Omani rials"
            ],
            "PAB"=> [
                "symbol"=> "B\/.",
                "name"=> "Panamanian Balboa",
                "symbol_native"=> "B\/.",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "PAB",
                "name_plural"=> "Panamanian balboas"
            ],
            "PEN"=> [
                "symbol"=> "S\/.",
                "name"=> "Peruvian Nuevo Sol",
                "symbol_native"=> "S\/.",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "PEN",
                "name_plural"=> "Peruvian nuevos soles"
            ],
            "PHP"=> [
                "symbol"=> "\u20b1",
                "name"=> "Philippine Peso",
                "symbol_native"=> "\u20b1",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "PHP",
                "name_plural"=> "Philippine pesos"
            ],
            "PKR"=> [
                "symbol"=> "PKRs",
                "name"=> "Pakistani Rupee",
                "symbol_native"=> "\u20a8",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "PKR",
                "name_plural"=> "Pakistani rupees"
            ],
            "PLN"=> [
                "symbol"=> "z\u0142",
                "name"=> "Polish Zloty",
                "symbol_native"=> "z\u0142",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "PLN",
                "name_plural"=> "Polish zlotys"
            ],
            "PYG"=> [
                "symbol"=> "\u20b2",
                "name"=> "Paraguayan Guarani",
                "symbol_native"=> "\u20b2",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "PYG",
                "name_plural"=> "Paraguayan guaranis"
            ],
            "QAR"=> [
                "symbol"=> "QR",
                "name"=> "Qatari Rial",
                "symbol_native"=> "\u0631.\u0642.\u200f",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "QAR",
                "name_plural"=> "Qatari rials"
            ],
            "RON"=> [
                "symbol"=> "RON",
                "name"=> "Romanian Leu",
                "symbol_native"=> "RON",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "RON",
                "name_plural"=> "Romanian lei"
            ],
            "RSD"=> [
                "symbol"=> "din.",
                "name"=> "Serbian Dinar",
                "symbol_native"=> "\u0434\u0438\u043d.",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "RSD",
                "name_plural"=> "Serbian dinars"
            ],
            "RUB"=> [
                "symbol"=> "RUB",
                "name"=> "Russian Ruble",
                "symbol_native"=> "\u20bd.",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "RUB",
                "name_plural"=> "Russian rubles"
            ],
            "RWF"=> [
                "symbol"=> "RF",
                "name"=> "Rwandan Franc",
                "symbol_native"=> "FR",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "RWF",
                "name_plural"=> "Rwandan francs"
            ],
            "SAR"=> [
                "symbol"=> "SR",
                "name"=> "Saudi Riyal",
                "symbol_native"=> "\u0631.\u0633.\u200f",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "SAR",
                "name_plural"=> "Saudi riyals"
            ],
            "SDG"=> [
                "symbol"=> "SDG",
                "name"=> "Sudanese Pound",
                "symbol_native"=> "SDG",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "SDG",
                "name_plural"=> "Sudanese pounds"
            ],
            "SEK"=> [
                "symbol"=> "Skr",
                "name"=> "Swedish Krona",
                "symbol_native"=> "kr",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "SEK",
                "name_plural"=> "Swedish kronor"
            ],
            "SGD"=> [
                "symbol"=> "S$",
                "name"=> "Singapore Dollar",
                "symbol_native"=> "$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "SGD",
                "name_plural"=> "Singapore dollars"
            ],
            "SOS"=> [
                "symbol"=> "Ssh",
                "name"=> "Somali Shilling",
                "symbol_native"=> "Ssh",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "SOS",
                "name_plural"=> "Somali shillings"
            ],
            "SYP"=> [
                "symbol"=> "SY\u00a3",
                "name"=> "Syrian Pound",
                "symbol_native"=> "\u0644.\u0633.\u200f",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "SYP",
                "name_plural"=> "Syrian pounds"
            ],
            "THB"=> [
                "symbol"=> "\u0e3f",
                "name"=> "Thai Baht",
                "symbol_native"=> "\u0e3f",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "THB",
                "name_plural"=> "Thai baht"
            ],
            "TND"=> [
                "symbol"=> "DT",
                "name"=> "Tunisian Dinar",
                "symbol_native"=> "\u062f.\u062a.\u200f",
                "decimal_digits"=> 3,
                "rounding"=> 0,
                "code"=> "TND",
                "name_plural"=> "Tunisian dinars"
            ],
            "TOP"=> [
                "symbol"=> "T$",
                "name"=> "Tongan Pa\u02bbanga",
                "symbol_native"=> "T$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "TOP",
                "name_plural"=> "Tongan pa\u02bbanga"
            ],
            "TRY"=> [
                "symbol"=> "TL",
                "name"=> "Turkish Lira",
                "symbol_native"=> "TL",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "TRY",
                "name_plural"=> "Turkish Lira"
            ],
            "TTD"=> [
                "symbol"=> "TT$",
                "name"=> "Trinidad and Tobago Dollar",
                "symbol_native"=> "$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "TTD",
                "name_plural"=> "Trinidad and Tobago dollars"
            ],
            "TWD"=> [
                "symbol"=> "NT$",
                "name"=> "New Taiwan Dollar",
                "symbol_native"=> "NT$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "TWD",
                "name_plural"=> "New Taiwan dollars"
            ],
            "TZS"=> [
                "symbol"=> "TSh",
                "name"=> "Tanzanian Shilling",
                "symbol_native"=> "TSh",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "TZS",
                "name_plural"=> "Tanzanian shillings"
            ],
            "UAH"=> [
                "symbol"=> "\u20b4",
                "name"=> "Ukrainian Hryvnia",
                "symbol_native"=> "\u20b4",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "UAH",
                "name_plural"=> "Ukrainian hryvnias"
            ],
            "UGX"=> [
                "symbol"=> "USh",
                "name"=> "Ugandan Shilling",
                "symbol_native"=> "USh",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "UGX",
                "name_plural"=> "Ugandan shillings"
            ],
            "UYU"=> [
                "symbol"=> "\$U",
                "name"=> "Uruguayan Peso",
                "symbol_native"=> "$",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "UYU",
                "name_plural"=> "Uruguayan pesos"
            ],
            "UZS"=> [
                "symbol"=> "UZS",
                "name"=> "Uzbekistan Som",
                "symbol_native"=> "UZS",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "UZS",
                "name_plural"=> "Uzbekistan som"
            ],
            "VEF"=> [
                "symbol"=> "Bs.F.",
                "name"=> "Venezuelan Bol\u00edvar",
                "symbol_native"=> "Bs.F.",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "VEF",
                "name_plural"=> "Venezuelan bol\u00edvars"
            ],
            "VND"=> [
                "symbol"=> "\u20ab",
                "name"=> "Vietnamese Dong",
                "symbol_native"=> "\u20ab",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "VND",
                "name_plural"=> "Vietnamese dong"
            ],
            "XAF"=> [
                "symbol"=> "FCFA",
                "name"=> "CFA Franc BEAC",
                "symbol_native"=> "FCFA",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "XAF",
                "name_plural"=> "CFA francs BEAC"
            ],
            "XOF"=> [
                "symbol"=> "CFA",
                "name"=> "CFA Franc BCEAO",
                "symbol_native"=> "CFA",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "XOF",
                "name_plural"=> "CFA francs BCEAO"
            ],
            "YER"=> [
                "symbol"=> "YR",
                "name"=> "Yemeni Rial",
                "symbol_native"=> "\u0631.\u064a.\u200f",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "YER",
                "name_plural"=> "Yemeni rials"
            ],
            "ZAR"=> [
                "symbol"=> "R",
                "name"=> "South African Rand",
                "symbol_native"=> "R",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "ZAR",
                "name_plural"=> "South African rand"
            ],
            "ZMK"=> [
                "symbol"=> "ZK",
                "name"=> "Zambian Kwacha",
                "symbol_native"=> "ZK",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "ZMK",
                "name_plural"=> "Zambian kwachas"
            ],
            "ZWL"=> [
                "symbol"=> "ZWL$",
                "name"=> "Zimbabwean Dollar",
                "symbol_native"=> "ZWL$",
                "decimal_digits"=> 0,
                "rounding"=> 0,
                "code"=> "ZWL",
                "name_plural"=> "Zimbabwean Dollar"
            ],
            "LSL"=> [
                "symbol"=> "M",
                "name"=> "Lesotho",
                "symbol_native"=> "M",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "LSL",
                "name_plural"=> "Maloti"
            ],
            "SZL"=> [
                "symbol"=> "E",
                "name"=> "Eswatini Lilangeni",
                "symbol_native"=> "E",
                "decimal_digits"=> 2,
                "rounding"=> 0,
                "code"=> "SZL",
                "name_plural"=> "Lilangeni"
            ]

        ];

        return $currencies;
    }

    public static function bankKeywords(){
        $bank_keywords = [
            "044" => [
                "Access",
                "Access Bank",
                "Access Bank Plc",
                "Diamond",
                "Diamond Bank",
                "Diamond Bank Plc"
            ],
            "023" => [
                "Citi",
                "Citi Bank"
            ],
            "050" => [
                "Ecobank",
                "Eco Bank",
                "Ecobank Plc",
                "Ecobank Nigeria Plc"
            ],
            "070" => [
                "Fidelity",
                "Fidelity Bank",
                "Fidelity Bank Plc"
            ],
            "011" => [
                "First Bank",
                "Firstbank",
                "FBN",
                "First Bank of Nigeria Limited",
                "First Bank Of Nigeria Plc"
            ],
            "214" => [
                "FCMB",
                "First City",
                "First City Monument Bank",
                "First City Monument Bank Plc",
                "First City Monument Bank Limited"
            ],
            "058" => [
                "GTB",
                "GTBank",
                "Guaranty Trust",
                "Guaranty Trust Bank",
                "Guaranty Trust Bank Plc",
                "Guarantee Trust",
                "Guarantee Trust Bank",
                "Guarantee Trust Bank Plc"
            ],
            "030" => [
                "Heritage",
                "Heritage Bank",
                "Heritage Bank Plc"
            ],
            "301" => [
                "Jaiz",
                "Jaiz Bank"
            ],
            "082" => [
                "Keystone",
                "Keystone Bank",
                "Keystone Bank Plc"
            ],
            "101" => [
                "Providus",
                "Providus Bank",
                "ProvidusBank"
            ],
            "076" => [
                "Skye",
                "Skye Bank",
                "Skye Bank Plc",
                "Polaris Bank Limited"
            ],
            "221" => [
                "Stanbic",
                "Stanbic Ibtc",
                "Stanbic Bank",
                "Stanbic Bank Plc",
                "Stanbic Ibtc Bank Plc"
            ],
            "068" => [
                "Standard Chartered",
                "Standard Chatered",
                "Standard Chartered Bank",
                "Standard Chartered Bank Plc"
            ],
            "232" => [
                "Sterling",
                "Sterling Bank",
                "Sterling Bank Plc"
            ],
            "100" => [
                "Suntrust",
                "Suntrust Bank",
                "SunTrust Bank Nigeria Limited"
            ],
            "032" => [
                "Union",
                "Union Bank",
                "Union Bank Of Nigeria",
                "Union Bank Of Nigeria Plc"
            ],
            "033" => [
                "UBA",
                "United Bank of Africa",
                "United Bank for Africa",
                "United Bank of Africa Plc"
            ],
            "215" => [
                "Unity",
                "Unity Bank",
                "Unity Bank Plc"
            ],
            "035" => [
                "Wema",
                "Wema Bank",
                "Wema Bank Plc"
            ],
            "057" => [
                "Zenith",
                "Zenith Bank",
                "Zenith Bank Plc",
                "Zenith International Bank Plc"
            ],
            "060001" => [
                "Coronation",
                "Coronation Bank",
                "Coronation Merchant Bank"
            ],
            "090262" => [
                "Stella",
                "Stella mbf"
            ],
            "070010" => [
                "Abbey Mortgage Bank",
                "Abbey"
            ],
            "060002" => [
                "FBNQUEST Merchant Bank",
                "FBN Quest"
            ],
            "400001" => [
                "FSDH Merchant Bank",
                "FSDH"
            ],
            "103" => [
                "Globus Bank",
                "Globus"
            ],
            "090118" => [
                "Ibile Microfinance Bank",
                "Ibile"
            ],
            "090267" => [
                "Kuda Microfinance Bank",
                "Kuda Bank",
                "Kuda"
            ],
            "090151" => [
                "Mutual Trust Microfinance Bank",
                "Mutual Trust"
            ],
            "060003" => [
                "Nova Microfinance Bank",
                "Nova"
            ],
            "502" => [
                "Rand Merchant Bank",
                "Rand"
            ],
            "090175" => [
                "Rubies Microfinance Bank",
                "Rubies"
            ],
            "000026" => [
                "Taj Bank Limited",
                "Taj Bank"
            ],
            "000025" => [
                "Titan Trust Bank",
                "Titan Trust"
            ],
            "090110" => [
                "VFD Microfinance Bank",
                "VFD MFB",
                "VFD"
            ]
        ];
        
        return $bank_keywords;
    }

    public static function timeZones(){
        $zones = [
            1 => [
                "value" => "Dateline Standard Time",
                "abbr" => "DST",
                "offset" => -12,
                "isdst" => false,
                "text" => "(UTC-12:00) International Date Line West",
                "utc" => [
                  "Etc/GMT+12"
                ]
            ],

            2 => [
                "value" => "UTC-11",
                "abbr" => "U",
                "offset" => -11,
                "isdst" => false,
                "text" => "(UTC-11:00) Coordinated Universal Time-11",
                "utc" => [
                    "Etc/GMT+11",
                    "Pacific/Midway",
                    "Pacific/Niue",
                    "Pacific/Pago_Pago"
                ],
            ],

            3 => [
                "value" => "Hawaiian Standard Time",
                "abbr" => "HST",
                "offset" => -10,
                "isdst" => false,
                "text" => "(UTC-10:00) Hawaii",
                "utc" => [
                    "Etc/GMT+10",
                    "Pacific/Honolulu",
                    "Pacific/Johnston",
                    "Pacific/Rarotonga",
                    "Pacific/Tahiti"
                ]
            ],
            4 => [
                "value" => "Alaskan Standard Time",
                "abbr" => "AKDT",
                "offset" => -8,
                "isdst" => true,
                "text" => "(UTC-09:00) Alaska",
                "utc" => [
                    "America/Anchorage",
                    "America/Juneau",
                    "America/Nome",
                    "America/Sitka",
                    "America/Yakutat"
                ]
            ],

            5 => [
                "value" => "Pacific Standard Time (Mexico)",
                "abbr" => "PDT",
                "offset" => -7,
                "isdst" => true,
                "text" => "(UTC-08:00) Baja California",
                "utc" => [
                    "America/Santa_Isabel"
                ]
            ],

            6 => [
                "value" => "Pacific Daylight Time",
                "abbr" => "PDT",
                "offset" => -7,
                "isdst" => true,
                "text" => "(UTC-07:00) Pacific Daylight Time (US & Canada)",
                "utc" => [
                    "America/Los_Angeles",
                    "America/Tijuana",
                    "America/Vancouver"
                ]
            ],

            7 => [
                "value" => "Pacific Standard Time",
                "abbr" => "PST",
                "offset" => -8,
                "isdst" => false,
                "text" => "(UTC-08:00) Pacific Standard Time (US & Canada)",
                "utc" => [
                    "America/Los_Angeles",
                    "America/Tijuana",
                    "America/Vancouver",
                    "PST8PDT"
                ]
            ],

            8 => [
                "value" => "US Mountain Standard Time",
                "abbr" => "UMST",
                "offset" => -7,
                "isdst" => false,
                "text" => "(UTC-07:00) Arizona",
                "utc" => [
                    "America/Creston",
                    "America/Dawson",
                    "America/Dawson_Creek",
                    "America/Hermosillo",
                    "America/Phoenix",
                    "America/Whitehorse",
                    "Etc/GMT+7"
                ]
            ],

            9 => [
                "value" => "Mountain Standard Time (Mexico)",
                "abbr" => "MDT",
                "offset" => -6,
                "isdst" => true,
                "text" => "(UTC-07:00) Chihuahua, La Paz, Mazatlan",
                "utc" => [
                    "America/Chihuahua",
                    "America/Mazatlan"
                ]
            ],

            10 => [
                "value" => "Mountain Standard Time",
                "abbr" => "MDT",
                "offset" => -6,
                "isdst" => true,
                "text" => "(UTC-07:00) Mountain Time (US & Canada)",
                "utc" => [
                    "America/Boise",
                    "America/Cambridge_Bay",
                    "America/Denver",
                    "America/Edmonton",
                    "America/Inuvik",
                    "America/Ojinaga",
                    "America/Yellowknife",
                    "MST7MDT"
                ]
            ],

            11 => [
                "value" => "Central America Standard Time",
                "abbr" => "CAST",
                "offset" => -6,
                "isdst" => false,
                "text" => "(UTC-06:00) Central America",
                "utc" => [
                    "America/Belize",
                    "America/Costa_Rica",
                    "America/El_Salvador",
                    "America/Guatemala",
                    "America/Managua",
                    "America/Tegucigalpa",
                    "Etc/GMT+6",
                    "Pacific/Galapagos"
                ]
            ],

            12 => [
                "value" => "Central Standard Time",
                "abbr" => "CDT",
                "offset" => -5,
                "isdst" => true,
                "text" => "(UTC-06:00) Central Time (US & Canada)",
                "utc" => [
                    "America/Chicago",
                    "America/Indiana/Knox",
                    "America/Indiana/Tell_City",
                    "America/Matamoros",
                    "America/Menominee",
                    "America/North_Dakota/Beulah",
                    "America/North_Dakota/Center",
                    "America/North_Dakota/New_Salem",
                    "America/Rainy_River",
                    "America/Rankin_Inlet",
                    "America/Resolute",
                    "America/Winnipeg",
                    "CST6CDT"
                ]
            ],

            13 => [
                "value" => "Central Standard Time (Mexico)",
                "abbr" => "CDT",
                "offset" => -5,
                "isdst" => true,
                "text" => "(UTC-06:00) Guadalajara, Mexico City, Monterrey",
                "utc" => [
                    "America/Bahia_Banderas",
                    "America/Cancun",
                    "America/Merida",
                    "America/Mexico_City",
                    "America/Monterrey"
                ]
            ],

            14 => [
                "value" => "Canada Central Standard Time",
                "abbr" => "CCST",
                "offset" => -6,
                "isdst" => false,
                "text" => "(UTC-06:00) Saskatchewan",
                "utc" => [
                    "America/Regina",
                    "America/Swift_Current"
                ]
            ],

            15 => [
                "value" => "SA Pacific Standard Time",
                "abbr" => "SPST",
                "offset" => -5,
                "isdst" => false,
                "text" => "(UTC-05:00) Bogota, Lima, Quito",
                "utc" => [
                    "America/Bogota",
                    "America/Cayman",
                    "America/Coral_Harbour",
                    "America/Eirunepe",
                    "America/Guayaquil",
                    "America/Jamaica",
                    "America/Lima",
                    "America/Panama",
                    "America/Rio_Branco",
                    "Etc/GMT+5"
                ]
            ],

            16 => [
                "value" => "Eastern Standard Time",
                "abbr" => "EST",
                "offset" => -5,
                "isdst" => false,
                "text" => "(UTC-05:00) Eastern Time (US & Canada)",
                "utc" => [
                    "America/Detroit",
                    "America/Havana",
                    "America/Indiana/Petersburg",
                    "America/Indiana/Vincennes",
                    "America/Indiana/Winamac",
                    "America/Iqaluit",
                    "America/Kentucky/Monticello",
                    "America/Louisville",
                    "America/Montreal",
                    "America/Nassau",
                    "America/New_York",
                    "America/Nipigon",
                    "America/Pangnirtung",
                    "America/Port-au-Prince",
                    "America/Thunder_Bay",
                    "America/Toronto"
                ]
            ],

            17 => [
                "value" => "Eastern Daylight Time",
                "abbr" => "EDT",
                "offset" => -4,
                "isdst" => true,
                "text" => "(UTC-04:00) Eastern Daylight Time (US & Canada)",
                "utc" => [
                    "America/Detroit",
                    "America/Havana",
                    "America/Indiana/Petersburg",
                    "America/Indiana/Vincennes",
                    "America/Indiana/Winamac",
                    "America/Iqaluit",
                    "America/Kentucky/Monticello",
                    "America/Louisville",
                    "America/Montreal",
                    "America/Nassau",
                    "America/New_York",
                    "America/Nipigon",
                    "America/Pangnirtung",
                    "America/Port-au-Prince",
                    "America/Thunder_Bay",
                    "America/Toronto"
                ]
            ],

            18 => [
                "value" => "US Eastern Standard Time",
                "abbr" => "UEDT",
                "offset" => -5,
                "isdst" => false,
                "text" => "(UTC-05:00) Indiana (East)",
                "utc" => [
                    "America/Indiana/Marengo",
                    "America/Indiana/Vevay",
                    "America/Indianapolis"
                ]
            ],

            19 => [
                "value" => "Venezuela Standard Time",
                "abbr" => "VST",
                "offset" => -4.5,
                "isdst" => false,
                "text" => "(UTC-04:30) Caracas",
                "utc" => [
                    "America/Caracas"
                ]
            ],

            20 => [
                "value" => "Paraguay Standard Time",
                "abbr" => "PYT",
                "offset" => -4,
                "isdst" => false,
                "text" => "(UTC-04:00) Asuncion",
                "utc" => [
                    "America/Asuncion"
                ]
            ],

            21 => [
                "value" => "Atlantic Standard Time",
                "abbr" => "ADT",
                "offset" => -3,
                "isdst" => true,
                "text" => "(UTC-04:00) Atlantic Time (Canada)",
                "utc" => [
                    "America/Glace_Bay",
                    "America/Goose_Bay",
                    "America/Halifax",
                    "America/Moncton",
                    "America/Thule",
                    "Atlantic/Bermuda"
                ]
            ],

            22 => [
                "value" => "Central Brazilian Standard Time",
                "abbr" => "CBST",
                "offset" => -4,
                "isdst" => false,
                "text" => "(UTC-04:00) Cuiaba",
                "utc" => [
                    "America/Campo_Grande",
                    "America/Cuiaba"
                ]
            ],

            23 => [
                "value" => "SA Western Standard Time",
                "abbr" => "SWST",
                "offset" => -4,
                "isdst" => false,
                "text" => "(UTC-04:00) Georgetown, La Paz, Manaus, San Juan",
                "utc" => [
                    "America/Anguilla",
                    "America/Antigua",
                    "America/Aruba",
                    "America/Barbados",
                    "America/Blanc-Sablon",
                    "America/Boa_Vista",
                    "America/Curacao",
                    "America/Dominica",
                    "America/Grand_Turk",
                    "America/Grenada",
                    "America/Guadeloupe",
                    "America/Guyana",
                    "America/Kralendijk",
                    "America/La_Paz",
                    "America/Lower_Princes",
                    "America/Manaus",
                    "America/Marigot",
                    "America/Martinique",
                    "America/Montserrat",
                    "America/Port_of_Spain",
                    "America/Porto_Velho",
                    "America/Puerto_Rico",
                    "America/Santo_Domingo",
                    "America/St_Barthelemy",
                    "America/St_Kitts",
                    "America/St_Lucia",
                    "America/St_Thomas",
                    "America/St_Vincent",
                    "America/Tortola",
                    "Etc/GMT+4"
                ]
            ],

            24 => [
                "value" => "Pacific SA Standard Time",
                "abbr" => "PSST",
                "offset" => -4,
                "isdst" => false,
                "text" => "(UTC-04:00) Santiago",
                "utc" => [
                    "America/Santiago",
                    "Antarctica/Palmer"
                ]
            ],

            25 => [
                "value" => "Newfoundland Standard Time",
                "abbr" => "NDT",
                "offset" => -2.5,
                "isdst" => true,
                "text" => "(UTC-03:30) Newfoundland",
                "utc" => [
                    "America/St_Johns"
                ]
            ],

            26 => [
                "value" => "E. South America Standard Time",
                "abbr" => "ESAST",
                "offset" => -3,
                "isdst" => false,
                "text" => "(UTC-03:00) Brasilia",
                "utc" => [
                    "America/Sao_Paulo"
                ]
            ],


            27 => [
                "value" => "Argentina Standard Time",
                "abbr" => "AST",
                "offset" => -3,
                "isdst" => false,
                "text" => "(UTC-03:00) Buenos Aires",
                "utc" => [
                    "America/Argentina/Buenos_Aires",
                    "America/Argentina/Catamarca",
                    "America/Argentina/Cordoba",
                    "America/Argentina/Jujuy",
                    "America/Argentina/La_Rioja",
                    "America/Argentina/Mendoza",
                    "America/Argentina/Rio_Gallegos",
                    "America/Argentina/Salta",
                    "America/Argentina/San_Juan",
                    "America/Argentina/San_Luis",
                    "America/Argentina/Tucuman",
                    "America/Argentina/Ushuaia",
                    "America/Buenos_Aires",
                    "America/Catamarca",
                    "America/Cordoba",
                    "America/Jujuy",
                    "America/Mendoza"
                ]
            ],

            28 => [
                "value" => "SA Eastern Standard Time",
                "abbr" => "SEST",
                "offset" => -3,
                "isdst" => false,
                "text" => "(UTC-03:00) Cayenne, Fortaleza",
                "utc" => [
                    "America/Araguaina",
                    "America/Belem",
                    "America/Cayenne",
                    "America/Fortaleza",
                    "America/Maceio",
                    "America/Paramaribo",
                    "America/Recife",
                    "America/Santarem",
                    "Antarctica/Rothera",
                    "Atlantic/Stanley",
                    "Etc/GMT+3"
                ]
            ],

            29 => [
                "value" => "Greenland Standard Time",
                "abbr" => "GDT",
                "offset" => -3,
                "isdst" => true,
                "text" => "(UTC-03:00) Greenland",
                "utc" => [
                    "America/Godthab"
                ]
            ],

            30 => [
                "value" => "Montevideo Standard Time",
                "abbr" => "MST",
                "offset" => -3,
                "isdst" => false,
                "text" => "(UTC-03:00) Montevideo",
                "utc" => [
                    "America/Montevideo"
                ]
            ],

            31 => [
                "value" => "Bahia Standard Time",
                "abbr" => "BST",
                "offset" => -3,
                "isdst" => false,
                "text" => "(UTC-03:00) Salvador",
                "utc" => [
                    "America/Bahia"
                ]
            ],

            32 => [
                "value" => "UTC-02",
                "abbr" => "U",
                "offset" => -2,
                "isdst" => false,
                "text" => "(UTC-02:00) Coordinated Universal Time-02",
                "utc" => [
                    "America/Noronha",
                    "Atlantic/South_Georgia",
                    "Etc/GMT+2"
                ]
            ],

            33 => [
                "value" => "Mid-Atlantic Standard Time",
                "abbr" => "MDT",
                "offset" => -1,
                "isdst" => true,
                "text" => "(UTC-02:00) Mid-Atlantic - Old",
                "utc" => []
            ],

            34 => [
                "value" => "Azores Standard Time",
                "abbr" => "ADT",
                "offset" => 0,
                "isdst" => true,
                "text" => "(UTC-01:00) Azores",
                "utc" => [
                    "America/Scoresbysund",
                    "Atlantic/Azores"
                ]
            ],

            35 => [
                "value" => "Cape Verde Standard Time",
                "abbr" => "CVST",
                "offset" => -1,
                "isdst" => false,
                "text" => "(UTC-01:00) Cape Verde Is.",
                "utc" => [
                    "Atlantic/Cape_Verde",
                    "Etc/GMT+1"
                ]
            ],

            36 => [
                "value" => "Morocco Standard Time",
                "abbr" => "MDT",
                "offset" => 1,
                "isdst" => true,
                "text" => "(UTC) Casablanca",
                "utc" => [
                    "Africa/Casablanca",
                    "Africa/El_Aaiun"
                ]
            ],

            37 => [
                "value" => "UTC",
                "abbr" => "UTC",
                "offset" => 0,
                "isdst" => false,
                "text" => "(UTC) Coordinated Universal Time",
                "utc" => [
                    "America/Danmarkshavn",
                    "Etc/GMT"
                ]
            ],

            38 => [
                "value" => "GMT Standard Time",
                "abbr" => "GMT",
                "offset" => 0,
                "isdst" => false,
                "text" => "(UTC) Edinburgh, London",
                "utc" => [
                    "Europe/Isle_of_Man",
                    "Europe/Guernsey",
                    "Europe/Jersey",
                    "Europe/London"
                ]
            ],

            39 => [
                "value" => "British Summer Time",
                "abbr" => "BST",
                "offset" => 1,
                "isdst" => true,
                "text" => "(UTC+01:00) Edinburgh, London",
                "utc" => [
                    "Europe/Isle_of_Man",
                    "Europe/Guernsey",
                    "Europe/Jersey",
                    "Europe/London"
                ]
            ],

            40 => [
                "value" => "GMT Standard Time",
                "abbr" => "GDT",
                "offset" => 1,
                "isdst" => true,
                "text" => "(UTC) Dublin, Lisbon",
                "utc" => [
                    "Atlantic/Canary",
                    "Atlantic/Faeroe",
                    "Atlantic/Madeira",
                    "Europe/Dublin",
                    "Europe/Lisbon"
                ]
            ],

            41 => [
                "value" => "Greenwich Standard Time",
                "abbr" => "GST",
                "offset" => 0,
                "isdst" => false,
                "text" => "(UTC) Monrovia, Reykjavik",
                "utc" => [
                    "Africa/Abidjan",
                    "Africa/Accra",
                    "Africa/Bamako",
                    "Africa/Banjul",
                    "Africa/Bissau",
                    "Africa/Conakry",
                    "Africa/Dakar",
                    "Africa/Freetown",
                    "Africa/Lome",
                    "Africa/Monrovia",
                    "Africa/Nouakchott",
                    "Africa/Ouagadougou",
                    "Africa/Sao_Tome",
                    "Atlantic/Reykjavik",
                    "Atlantic/St_Helena"
                ]
            ],

            42 => [
                "value" => "W. Europe Standard Time",
                "abbr" => "WEDT",
                "offset" => 2,
                "isdst" => true,
                "text" => "(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna",
                "utc" => [
                    "Arctic/Longyearbyen",
                    "Europe/Amsterdam",
                    "Europe/Andorra",
                    "Europe/Berlin",
                    "Europe/Busingen",
                    "Europe/Gibraltar",
                    "Europe/Luxembourg",
                    "Europe/Malta",
                    "Europe/Monaco",
                    "Europe/Oslo",
                    "Europe/Rome",
                    "Europe/San_Marino",
                    "Europe/Stockholm",
                    "Europe/Vaduz",
                    "Europe/Vatican",
                    "Europe/Vienna",
                    "Europe/Zurich"
                ]
            ],

            43 => [
                "value" => "Central Europe Standard Time",
                "abbr" => "CEDT",
                "offset" => 2,
                "isdst" => true,
                "text" => "(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague",
                "utc" => [
                    "Europe/Belgrade",
                    "Europe/Bratislava",
                    "Europe/Budapest",
                    "Europe/Ljubljana",
                    "Europe/Podgorica",
                    "Europe/Prague",
                    "Europe/Tirane"
                ]
            ],

            44 => [
                "value" => "Romance Standard Time",
                "abbr" => "RDT",
                "offset" => 2,
                "isdst" => true,
                "text" => "(UTC+01:00) Brussels, Copenhagen, Madrid, Paris",
                "utc" => [
                    "Africa/Ceuta",
                    "Europe/Brussels",
                    "Europe/Copenhagen",
                    "Europe/Madrid",
                    "Europe/Paris"
                ]
            ],

            45 => [
                "value" => "Central European Standard Time",
                "abbr" => "CEDT",
                "offset" => 2,
                "isdst" => true,
                "text" => "(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb",
                "utc" => [
                    "Europe/Sarajevo",
                    "Europe/Skopje",
                    "Europe/Warsaw",
                    "Europe/Zagreb"
                ]
            ],
            46 => [
                "value" => "W. Central Africa Standard Time",
                "abbr" => "WCAST",
                "offset" => 1,
                "isdst" => false,
                "text" => "(UTC+01:00) West Central Africa",
                "utc" => [
                    "Africa/Algiers",
                    "Africa/Bangui",
                    "Africa/Brazzaville",
                    "Africa/Douala",
                    "Africa/Kinshasa",
                    "Africa/Lagos",
                    "Africa/Libreville",
                    "Africa/Luanda",
                    "Africa/Malabo",
                    "Africa/Ndjamena",
                    "Africa/Niamey",
                    "Africa/Porto-Novo",
                    "Africa/Tunis",
                    "Etc/GMT-1"
                ]
            ],
            47 => [
                "value" => "Namibia Standard Time",
                "abbr" => "NST",
                "offset" => 1,
                "isdst" => false,
                "text" => "(UTC+01:00) Windhoek",
                "utc" => [
                    "Africa/Windhoek"
                ]
            ],
            48 => [
                "value" => "GTB Standard Time",
                "abbr" => "GDT",
                "offset" => 3,
                "isdst" => true,
                "text" => "(UTC+02:00) Athens, Bucharest",
                "utc" => [
                    "Asia/Nicosia",
                    "Europe/Athens",
                    "Europe/Bucharest",
                    "Europe/Chisinau"
                ]
            ],
            49 => [
                "value" => "Middle East Standard Time",
                "abbr" => "MEDT",
                "offset" => 3,
                "isdst" => true,
                "text" => "(UTC+02:00) Beirut",
                "utc" => [
                    "Asia/Beirut"
                ]
            ],
            50 => [
                "value" => "Egypt Standard Time",
                "abbr" => "EST",
                "offset" => 2,
                "isdst" => false,
                "text" => "(UTC+02:00) Cairo",
                "utc" => [
                    "Africa/Cairo"
                ]
            ],
            51 => [
                "value" => "Syria Standard Time",
                "abbr" => "SDT",
                "offset" => 3,
                "isdst" => true,
                "text" => "(UTC+02:00) Damascus",
                "utc" => [
                    "Asia/Damascus"
                ]
            ],
            52 => [
                "value" => "E. Europe Standard Time",
                "abbr" => "EEDT",
                "offset" => 3,
                "isdst" => true,
                "text" => "(UTC+02:00) E. Europe",
                "utc" => [
                    "Asia/Nicosia",
                    "Europe/Athens",
                    "Europe/Bucharest",
                    "Europe/Chisinau",
                    "Europe/Helsinki",
                    "Europe/Kyiv",
                    "Europe/Mariehamn",
                    "Europe/Nicosia",
                    "Europe/Riga",
                    "Europe/Sofia",
                    "Europe/Tallinn",
                    "Europe/Uzhgorod",
                    "Europe/Vilnius",
                    "Europe/Zaporozhye"

                ]
            ],
            53 => [
                "value" => "South Africa Standard Time",
                "abbr" => "SAST",
                "offset" => 2,
                "isdst" => false,
                "text" => "(UTC+02:00) Harare, Pretoria",
                "utc" => [
                    "Africa/Blantyre",
                    "Africa/Bujumbura",
                    "Africa/Gaborone",
                    "Africa/Harare",
                    "Africa/Johannesburg",
                    "Africa/Kigali",
                    "Africa/Lubumbashi",
                    "Africa/Lusaka",
                    "Africa/Maputo",
                    "Africa/Maseru",
                    "Africa/Mbabane",
                    "Etc/GMT-2"
                ]
            ],
            54 => [
                "value" => "FLE Standard Time",
                "abbr" => "FDT",
                "offset" => 3,
                "isdst" => true,
                "text" => "(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius",
                "utc" => [
                    "Europe/Helsinki",
                    "Europe/Kyiv",
                    "Europe/Mariehamn",
                    "Europe/Riga",
                    "Europe/Sofia",
                    "Europe/Tallinn",
                    "Europe/Uzhgorod",
                    "Europe/Vilnius",
                    "Europe/Zaporozhye"
                ]
            ],
            55 => [
                "value" => "Turkey Standard Time",
                "abbr" => "TDT",
                "offset" => 3,
                "isdst" => false,
                "text" => "(UTC+03:00) Istanbul",
                "utc" => [
                    "Europe/Istanbul"
                ]
            ],
            56 => [
                "value" => "Israel Standard Time",
                "abbr" => "JDT",
                "offset" => 3,
                "isdst" => true,
                "text" => "(UTC+02:00) Jerusalem",
                "utc" => [
                    "Asia/Jerusalem"
                ]
            ],
            57 => [
                "value" => "Libya Standard Time",
                "abbr" => "LST",
                "offset" => 2,
                "isdst" => false,
                "text" => "(UTC+02:00) Tripoli",
                "utc" => [
                    "Africa/Tripoli"
                ]
            ],
            58 => [
                "value" => "Jordan Standard Time",
                "abbr" => "JST",
                "offset" => 3,
                "isdst" => false,
                "text" => "(UTC+03:00) Amman",
                "utc" => [
                    "Asia/Amman"
                ]
            ],
            59 => [
                "value" => "Arabic Standard Time",
                "abbr" => "AST",
                "offset" => 3,
                "isdst" => false,
                "text" => "(UTC+03:00) Baghdad",
                "utc" => [
                    "Asia/Baghdad"
                ]
            ],
            60 => [
                "value" => "Kaliningrad Standard Time",
                "abbr" => "KST",
                "offset" => 3,
                "isdst" => false,
                "text" => "(UTC+02:00) Kaliningrad",
                "utc" => [
                    "Europe/Kaliningrad"
                ]
            ],
            61 => [
                "value" => "Arab Standard Time",
                "abbr" => "AST",
                "offset" => 3,
                "isdst" => false,
                "text" => "(UTC+03:00) Kuwait, Riyadh",
                "utc" => [
                    "Asia/Aden",
                    "Asia/Bahrain",
                    "Asia/Kuwait",
                    "Asia/Qatar",
                    "Asia/Riyadh"
                ]
            ],
            62 => [
                "value" => "E. Africa Standard Time",
                "abbr" => "EAST",
                "offset" => 3,
                "isdst" => false,
                "text" => "(UTC+03:00) Nairobi",
                "utc" => [
                    "Africa/Addis_Ababa",
                    "Africa/Asmera",
                    "Africa/Dar_es_Salaam",
                    "Africa/Djibouti",
                    "Africa/Juba",
                    "Africa/Kampala",
                    "Africa/Khartoum",
                    "Africa/Mogadishu",
                    "Africa/Nairobi",
                    "Antarctica/Syowa",
                    "Etc/GMT-3",
                    "Indian/Antananarivo",
                    "Indian/Comoro",
                    "Indian/Mayotte"
                ]
            ],
            67 => [
                "value" => "Moscow Standard Time",
                "abbr" => "MSK",
                "offset" => 3,
                "isdst" => false,
                "text" => "(UTC+03:00) Moscow, St. Petersburg, Volgograd, Minsk",
                "utc" => [
                    "Europe/Kirov",
                    "Europe/Moscow",
                    "Europe/Simferopol",
                    "Europe/Volgograd",
                    "Europe/Minsk"
                ]
            ],
            68 => [
                "value" => "Samara Time",
                "abbr" => "SAMT",
                "offset" => 4,
                "isdst" => false,
                "text" => "(UTC+04:00) Samara, Ulyanovsk, Saratov",
                "utc" => [
                    "Europe/Astrakhan",
                    "Europe/Samara",
                    "Europe/Ulyanovsk"
                ]
            ],
            69 => [
                "value" => "Iran Standard Time",
                "abbr" => "IDT",
                "offset" => 4.5,
                "isdst" => true,
                "text" => "(UTC+03:30) Tehran",
                "utc" => [
                    "Asia/Tehran"
                ]
            ],
            70 => [
                "value" => "Arabian Standard Time",
                "abbr" => "AST",
                "offset" => 4,
                "isdst" => false,
                "text" => "(UTC+04:00) Abu Dhabi, Muscat",
                "utc" => [
                    "Asia/Dubai",
                    "Asia/Muscat",
                    "Etc/GMT-4"
                ]
            ],
            71 => [
                "value" => "Azerbaijan Standard Time",
                "abbr" => "ADT",
                "offset" => 5,
                "isdst" => true,
                "text" => "(UTC+04:00) Baku",
                "utc" => [
                    "Asia/Baku"
                ]
            ],
            72 => [
                "value" => "Mauritius Standard Time",
                "abbr" => "MST",
                "offset" => 4,
                "isdst" => false,
                "text" => "(UTC+04:00) Port Louis",
                "utc" => [
                    "Indian/Mahe",
                    "Indian/Mauritius",
                    "Indian/Reunion"
                ]
            ],
            73 => [
                "value" => "Georgian Standard Time",
                "abbr" => "GET",
                "offset" => 4,
                "isdst" => false,
                "text" => "(UTC+04:00) Tbilisi",
                "utc" => [
                    "Asia/Tbilisi"
                ]
            ],
            74 => [
                "value" => "Caucasus Standard Time",
                "abbr" => "CST",
                "offset" => 4,
                "isdst" => false,
                "text" => "(UTC+04:00) Yerevan",
                "utc" => [
                    "Asia/Yerevan"
                ]
            ],
            75 => [
                "value" => "Afghanistan Standard Time",
                "abbr" => "AST",
                "offset" => 4.5,
                "isdst" => false,
                "text" => "(UTC+04:30) Kabul",
                "utc" => [
                    "Asia/Kabul"
                ]
            ],
            76 => [
                "value" => "West Asia Standard Time",
                "abbr" => "WAST",
                "offset" => 5,
                "isdst" => false,
                "text" => "(UTC+05:00) Ashgabat, Tashkent",
                "utc" => [
                    "Antarctica/Mawson",
                    "Asia/Aqtau",
                    "Asia/Aqtobe",
                    "Asia/Ashgabat",
                    "Asia/Dushanbe",
                    "Asia/Oral",
                    "Asia/Samarkand",
                    "Asia/Tashkent",
                    "Etc/GMT-5",
                    "Indian/Kerguelen",
                    "Indian/Maldives"
                ]
            ],
            77 => [
                "value" => "Yekaterinburg Time",
                "abbr" => "YEKT",
                "offset" => 5,
                "isdst" => false,
                "text" => "(UTC+05:00) Yekaterinburg",
                "utc" => [
                    "Asia/Yekaterinburg"
                ]
            ],
            78 => [
                "value" => "Pakistan Standard Time",
                "abbr" => "PKT",
                "offset" => 5,
                "isdst" => false,
                "text" => "(UTC+05:00) Islamabad, Karachi",
                "utc" => [
                    "Asia/Karachi"
                ]
            ],
            79 => [
                "value" => "India Standard Time",
                "abbr" => "IST",
                "offset" => 5.5,
                "isdst" => false,
                "text" => "(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi",
                "utc" => [
                    "Asia/Kolkata",
                    "Asia/Calcutta"
                ]
            ],
            80 => [
                "value" => "Sri Lanka Standard Time",
                "abbr" => "SLST",
                "offset" => 5.5,
                "isdst" => false,
                "text" => "(UTC+05:30) Sri Jayawardenepura",
                "utc" => [
                    "Asia/Colombo"
                ]
            ],
            81 => [
                "value" => "Nepal Standard Time",
                "abbr" => "NST",
                "offset" => 5.75,
                "isdst" => false,
                "text" => "(UTC+05:45) Kathmandu",
                "utc" => [
                    "Asia/Kathmandu"
                ]
            ],
            82 => [
                "value" => "Central Asia Standard Time",
                "abbr" => "CAST",
                "offset" => 6,
                "isdst" => false,
                "text" => "(UTC+06:00) Nur-Sultan (Astana)",
                "utc" => [
                    "Antarctica/Vostok",
                    "Asia/Almaty",
                    "Asia/Bishkek",
                    "Asia/Qyzylorda",
                    "Asia/Urumqi",
                    "Etc/GMT-6",
                    "Indian/Chagos"
                ]
            ],
            83 => [
                "value" => "Bangladesh Standard Time",
                "abbr" => "BST",
                "offset" => 6,
                "isdst" => false,
                "text" => "(UTC+06:00) Dhaka",
                "utc" => [
                    "Asia/Dhaka",
                    "Asia/Thimphu"
                ]
            ],
            84 => [
                "value" => "Myanmar Standard Time",
                "abbr" => "MST",
                "offset" => 6.5,
                "isdst" => false,
                "text" => "(UTC+06:30) Yangon (Rangoon)",
                "utc" => [
                    "Asia/Rangoon",
                    "Indian/Cocos"
                ]
            ],
            85 => [
                "value" => "SE Asia Standard Time",
                "abbr" => "SAST",
                "offset" => 7,
                "isdst" => false,
                "text" => "(UTC+07:00) Bangkok, Hanoi, Jakarta",
                "utc" => [
                    "Antarctica/Davis",
                    "Asia/Bangkok",
                    "Asia/Hovd",
                    "Asia/Jakarta",
                    "Asia/Phnom_Penh",
                    "Asia/Pontianak",
                    "Asia/Saigon",
                    "Asia/Vientiane",
                    "Etc/GMT-7",
                    "Indian/Christmas"
                ]
            ],
            86 => [
                "value" => "N. Central Asia Standard Time",
                "abbr" => "NCAST",
                "offset" => 7,
                "isdst" => false,
                "text" => "(UTC+07:00) Novosibirsk",
                "utc" => [
                    "Asia/Novokuznetsk",
                    "Asia/Novosibirsk",
                    "Asia/Omsk"
                ]
            ],
            87 => [
                "value" => "China Standard Time",
                "abbr" => "CST",
                "offset" => 8,
                "isdst" => false,
                "text" => "(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi",
                "utc" => [
                    "Asia/Hong_Kong",
                    "Asia/Macau",
                    "Asia/Shanghai"
                ]
            ],
            88 => [
                "value" => "North Asia Standard Time",
                "abbr" => "NAST",
                "offset" => 8,
                "isdst" => false,
                "text" => "(UTC+08:00) Krasnoyarsk",
                "utc" => [
                    "Asia/Krasnoyarsk"
                ]
            ],
            89 => [
                "value" => "Singapore Standard Time",
                "abbr" => "MPST",
                "offset" => 8,
                "isdst" => false,
                "text" => "(UTC+08:00) Kuala Lumpur, Singapore",
                "utc" => [
                    "Asia/Brunei",
                    "Asia/Kuala_Lumpur",
                    "Asia/Kuching",
                    "Asia/Makassar",
                    "Asia/Manila",
                    "Asia/Singapore",
                    "Etc/GMT-8"
                ]
            ],
            90 => [
                "value" => "W. Australia Standard Time",
                "abbr" => "WAST",
                "offset" => 8,
                "isdst" => false,
                "text" => "(UTC+08:00) Perth",
                "utc" => [
                    "Antarctica/Casey",
                    "Australia/Perth"
                ]
            ],
            91 => [
                "value" => "Taipei Standard Time",
                "abbr" => "TST",
                "offset" => 8,
                "isdst" => false,
                "text" => "(UTC+08:00) Taipei",
                "utc" => [
                    "Asia/Taipei"
                ]
            ],
            92 => [
                "value" => "Ulaanbaatar Standard Time",
                "abbr" => "UST",
                "offset" => 8,
                "isdst" => false,
                "text" => "(UTC+08:00) Ulaanbaatar",
                "utc" => [
                    "Asia/Choibalsan",
                    "Asia/Ulaanbaatar"
                ]
            ],
            93 => [
                "value" => "North Asia East Standard Time",
                "abbr" => "NAEST",
                "offset" => 8,
                "isdst" => false,
                "text" => "(UTC+08:00) Irkutsk",
                "utc" => [
                    "Asia/Irkutsk"
                ]
            ],
            94 => [
                "value" => "Japan Standard Time",
                "abbr" => "JST",
                "offset" => 9,
                "isdst" => false,
                "text" => "(UTC+09:00) Osaka, Sapporo, Tokyo",
                "utc" => [
                    "Asia/Dili",
                    "Asia/Jayapura",
                    "Asia/Tokyo",
                    "Etc/GMT-9",
                    "Pacific/Palau"
                ]
            ],
            95 => [
                "value" => "Korea Standard Time",
                "abbr" => "KST",
                "offset" => 9,
                "isdst" => false,
                "text" => "(UTC+09:00) Seoul",
                "utc" => [
                    "Asia/Pyongyang",
                    "Asia/Seoul"
                ]
            ],
            96 => [
                "value" => "Cen. Australia Standard Time",
                "abbr" => "CAST",
                "offset" => 9.5,
                "isdst" => false,
                "text" => "(UTC+09:30) Adelaide",
                "utc" => [
                    "Australia/Adelaide",
                    "Australia/Broken_Hill"
                ]
            ],
            97 => [
                "value" => "AUS Central Standard Time",
                "abbr" => "ACST",
                "offset" => 9.5,
                "isdst" => false,
                "text" => "(UTC+09:30) Darwin",
                "utc" => [
                    "Australia/Darwin"
                ]
            ],
            98 => [
                "value" => "E. Australia Standard Time",
                "abbr" => "EAST",
                "offset" => 10,
                "isdst" => false,
                "text" => "(UTC+10:00) Brisbane",
                "utc" => [
                    "Australia/Brisbane",
                    "Australia/Lindeman"
                ]
            ],
            99 => [
                "value" => "AUS Eastern Standard Time",
                "abbr" => "AEST",
                "offset" => 10,
                "isdst" => false,
                "text" => "(UTC+10:00) Canberra, Melbourne, Sydney",
                "utc" => [
                    "Australia/Melbourne",
                    "Australia/Sydney"
                ]
            ],
            100 => [
                "value" => "West Pacific Standard Time",
                "abbr" => "WPST",
                "offset" => 10,
                "isdst" => false,
                "text" => "(UTC+10:00) Guam, Port Moresby",
                "utc" => [
                    "Antarctica/DumontDUrville",
                    "Etc/GMT-10",
                    "Pacific/Guam",
                    "Pacific/Port_Moresby",
                    "Pacific/Saipan",
                    "Pacific/Truk"
                ]
            ],
            101 => [
                "value" => "Tasmania Standard Time",
                "abbr" => "TST",
                "offset" => 10,
                "isdst" => false,
                "text" => "(UTC+10:00) Hobart",
                "utc" => [
                    "Australia/Currie",
                    "Australia/Hobart"
                ]
            ],
            102 => [
                "value" => "Yakutsk Standard Time",
                "abbr" => "YST",
                "offset" => 9,
                "isdst" => false,
                "text" => "(UTC+09:00) Yakutsk",
                "utc" => [
                    "Asia/Chita",
                    "Asia/Khandyga",
                    "Asia/Yakutsk"
                ]
            ],
            103 => [
                "value" => "Central Pacific Standard Time",
                "abbr" => "CPST",
                "offset" => 11,
                "isdst" => false,
                "text" => "(UTC+11:00) Solomon Is., New Caledonia",
                "utc" => [
                    "Antarctica/Macquarie",
                    "Etc/GMT-11",
                    "Pacific/Efate",
                    "Pacific/Guadalcanal",
                    "Pacific/Kosrae",
                    "Pacific/Noumea",
                    "Pacific/Ponape"
                ]
            ],
            104 => [
                "value" => "Vladivostok Standard Time",
                "abbr" => "VST",
                "offset" => 11,
                "isdst" => false,
                "text" => "(UTC+11:00) Vladivostok",
                "utc" => [
                    "Asia/Sakhalin",
                    "Asia/Ust-Nera",
                    "Asia/Vladivostok"
                ]
            ],
            105 => [
                "value" => "New Zealand Standard Time",
                "abbr" => "NZST",
                "offset" => 12,
                "isdst" => false,
                "text" => "(UTC+12:00) Auckland, Wellington",
                "utc" => [
                    "Antarctica/McMurdo",
                    "Pacific/Auckland"
                ]
            ],
            106 => [
                "value" => "UTC+12",
                "abbr" => "U",
                "offset" => 12,
                "isdst" => false,
                "text" => "(UTC+12:00) Coordinated Universal Time+12",
                "utc" => [
                    "Etc/GMT-12",
                    "Pacific/Funafuti",
                    "Pacific/Kwajalein",
                    "Pacific/Majuro",
                    "Pacific/Nauru",
                    "Pacific/Tarawa",
                    "Pacific/Wake",
                    "Pacific/Wallis"
                ]
            ],
            107 => [
                "value" => "Fiji Standard Time",
                "abbr" => "FST",
                "offset" => 12,
                "isdst" => false,
                "text" => "(UTC+12:00) Fiji",
                "utc" => [
                    "Pacific/Fiji"
                ]
            ],
            108 => [
                "value" => "Magadan Standard Time",
                "abbr" => "MST",
                "offset" => 12,
                "isdst" => false,
                "text" => "(UTC+12:00) Magadan",
                "utc" => [
                    "Asia/Anadyr",
                    "Asia/Kamchatka",
                    "Asia/Magadan",
                    "Asia/Srednekolymsk"
                ]
            ],
            109 => [
                "value" => "Kamchatka Standard Time",
                "abbr" => "KDT",
                "offset" => 13,
                "isdst" => true,
                "text" => "(UTC+12:00) Petropavlovsk-Kamchatsky - Old",
                "utc" => [
                    "Asia/Kamchatka"
                ]
            ],
            110 => [
                "value" => "Tonga Standard Time",
                "abbr" => "TST",
                "offset" => 13,
                "isdst" => false,
                "text" => "(UTC+13:00) Nuku'alofa",
                "utc" => [
                    "Etc/GMT-13",
                    "Pacific/Enderbury",
                    "Pacific/Fakaofo",
                    "Pacific/Tongatapu"
                ]
            ],
            111 => [
                "value" => "Samoa Standard Time",
                "abbr" => "SST",
                "offset" => 13,
                "isdst" => false,
                "text" => "(UTC+13:00) Samoa",
                "utc" => [
                    "Pacific/Apia"
                ]
            ]




        ];

        return $zones;
    }

}
  