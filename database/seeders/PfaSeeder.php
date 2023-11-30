<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\GeneralResourceService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PfaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void
    {
        \DB::table('pfas')->truncate();

        $insert = [
            [
                'id' => 10001,
                'name' => 'AIICO',
                'pfa_code' => '0033',
                'country_id' => 154
            ],
            [
                'id' => 10002,
                'name' => 'Anchor Pensions',
                'pfa_code' => '0036',
                'country_id' => 154
            ],
            [
                'id' => 10003,
                'name' => 'ARM Pensions',
                'pfa_code' => '0026',
                'country_id' => 154
            ],
            [
                'id' => 10004,
                'name' => 'Crusader Sterling Pensions Limited',
                'pfa_code' => '0032',
                'country_id' => 154
            ],
            [
                'id' => 10005,
                'name' => 'FGPL',
                'pfa_code' => '0029',
                'country_id' => 154
            ],
            [
                'id' => 10006,
                'name' => 'Fidelity Pensions',
                'pfa_code' => '0043',
                'country_id' => 154
            ],
            [
                'id' => 10007,
                'name' => 'Leadway Pensure PFA',
                'pfa_code' => '0023',
                'country_id' => 154
            ],
            [
                'id' => 10008,
                'name' => 'NLPC',
                'pfa_code' => '0031',
                'country_id' => 154
            ],
            [
                'id' => 10009,
                'name' => 'OAK',
                'pfa_code' => '0034',
                'country_id' => 154
            ],
            [
                'id' => 10010,
                'name' => 'PAL',
                'pfa_code' => '0025',
                'country_id' => 154
            ],
            [
                'id' => 10011,
                'name' => 'Radix Pensions',
                'pfa_code' => '0046',
                'country_id' => 154
            ],
            [
                'id' => 10012,
                'name' => 'Access Pensions',
                'pfa_code' => '0024',
                'country_id' => 154
            ],
            [
                'id' => 10013,
                'name' => 'Stanbic IBTC',
                'pfa_code' => '0021',
                'country_id' => 154
            ],
            [
                'id' => 10014,
                'name' => 'Guaranty Trust Pensions',
                'pfa_code' => '0040',
                'country_id' => 154
            ],
            [
                'id' => 10015,
                'name' => 'APT Pensions',
                'pfa_code' => '0037',
                'country_id' => 154
            ],
            [
                'id' => 10016,
                'name' => 'Premium Pensions',
                'pfa_code' => '0022',
                'country_id' => 154
            ],
            [
                'id' => 10017,
                'name' => 'Veritas Glanvills Pensions',
                'pfa_code' => '0042',
                'country_id' => 154
            ],
            [
                'id' => 10018,
                'name' => 'TangerineAPT Pensions',
                'pfa_code' => '0035',
                'country_id' => 154
            ],
            [
                'id' => 10019,
                'name' => 'FCMB Pensions',
                'pfa_code' => '0030',
                'country_id' => 154
            ],
            [
                'id' => 10020,
                'name' => 'Trustfund Pensions',
                'pfa_code' => '0028',
                'country_id' => 154
            ],
            [
                'id' => 10021,
                'name' => 'DATABANK ASSET MANAGEMENT SERVICES LIMITED',
                'pfa_code' => 'DATABANK0001',
                'country_id' => 76
            ],
            [
                'id' => 10022,
                'name' => 'EDC INVESTMENTS LIMITED',
                'pfa_code' => 'EDC0002',
                'country_id' => 76
            ],
            [
                'id' => 10023,
                'name' => 'PRUDENTIAL SECURITIES LIMITED',
                'pfa_code' => 'PRUDENTIAL0003',
                'country_id' => 76
            ],
            [
                'id' => 10024,
                'name' => 'CIDAN INVESTMENTS LIMITED',
                'pfa_code' => 'CIDAN0004',
                'country_id' => 76
            ],
            [
                'id' => 10025,
                'name' => 'CAL ASSET MANAGEMENT COMPANY LIMITED',
                'pfa_code' => 'CAL0005',
                'country_id' => 76
            ],
            [
                'id' => 10026,
                'name' => 'FIDELITY SECURITIES LIMITED',
                'pfa_code' => 'FIDELITY0006',
                'country_id' => 76
            ],
            [
                'id' => 10027,
                'name' => 'REPUBLIC INVESTMENTS (GHANA) LIMITED',
                'pfa_code' => 'REPUBLIC0007',
                'country_id' => 76
            ],
            [
                'id' => 10028,
                'name' => 'NEW GENERATION INVESTMENT SERVICES LIMITED',
                'pfa_code' => 'NGIS0008',
                'country_id' => 76
            ],
            [
                'id' => 10029,
                'name' => 'SAS INVESTMENT MANAGEMENT LIMITED',
                'pfa_code' => 'SAS0009',
                'country_id' => 76
            ],
            [
                'id' => 10030,
                'name' => 'CORNERSTONE CAPITAL ADVISORS LIMITED',
                'pfa_code' => 'CSCA0010',
                'country_id' => 76
            ],
            [
                'id' => 10031,
                'name' => 'UMB INVESTMENT HOLDINGS',
                'pfa_code' => 'UMBC0011',
                'country_id' => 76
            ],
            [
                'id' => 10032,
                'name' => 'SDC CAPITAL HOLDINGS',
                'pfa_code' => 'SDCC0012',
                'country_id' => 76
            ],
            [
                'id' => 10033,
                'name' => 'TESAH CAPITAL HOLDINGS',
                'pfa_code' => 'TESAH0013',
                'country_id' => 76
            ],
            [
                'id' => 10034,
                'name' => 'CRYSTAL CAPITAL AND INVESTMENTS',
                'pfa_code' => 'CRYSTAL0014',
                'country_id' => 76
            ],
            [
                'id' => 10035,
                'name' => 'INVESTCORP ASSET MANAGEMENT',
                'pfa_code' => 'INVESTCORP0015',
                'country_id' => 76
            ],
            [
                'id' => 10036,
                'name' => 'INVESTITURE FUND MANAGERS ',
                'pfa_code' => 'INVESTITURE0016',
                'country_id' => 76
            ],
            [
                'id' => 10037,
                'name' => 'NIMED CAPITAL LIMITED ',
                'pfa_code' => 'NIMED0017',
                'country_id' => 76
            ],
            [
                'id' => 10038,
                'name' => 'STANBIC INVESTMENT MANAGEMENT',
                'pfa_code' => 'STANBIC0018',
                'country_id' => 76
            ],
            [
                'id' => 10039,
                'name' => 'OCTANEDC LIMITED ',
                'pfa_code' => 'OCTANEDC0019',
                'country_id' => 76
            ],
            [
                'id' => 10040,
                'name' => 'IGS FINANCIAL SERVICES ',
                'pfa_code' => 'IGS0020',
                'country_id' => 76
            ],
            [
                'id' => 10041,
                'name' => 'ECOCAPITAL INVESTMENT',
                'pfa_code' => 'ECOCAPITAL0021',
                'country_id' => 76
            ],
            [
                'id' => 10042,
                'name' => 'SEM CAPITAL ADVISORS LIMITED ',
                'pfa_code' => 'SEM0022',
                'country_id' => 76
            ],
            [
                'id' => 10043,
                'name' => 'BORA CAPITAL ADVISORS LIMITED ',
                'pfa_code' => 'BORA0023',
                'country_id' => 76
            ],
            [
                'id' => 10044,
                'name' => 'IC ASSET MANAGERS (GHANA)',
                'pfa_code' => 'IC0024',
                'country_id' => 76
            ],
            [
                'id' => 10045,
                'name' => 'WAICA RE CAPITAL LIMITED ',
                'pfa_code' => 'WAICA0025',
                'country_id' => 76
            ],
            [
                'id' => 10046,
                'name' => 'SERENGETI ASSET MANAGEMENT',
                'pfa_code' => 'SERENGETI0026',
                'country_id' => 76
            ],
            [
                'id' => 10047,
                'name' => 'SIC FINANCIAL SERVICES LIMITED ',
                'pfa_code' => 'SIC0027',
                'country_id' => 76
            ],
            [
                'id' => 10048,
                'name' => 'BLACK STAR ADVISORS LIMITED ',
                'pfa_code' => 'BLACK0028',
                'country_id' => 76
            ],
            [
                'id' => 10049,
                'name' => 'GLICO CAPITAL LIMITED ',
                'pfa_code' => 'GLICO0029',
                'country_id' => 76
            ],
            [
                'id' => 10050,
                'name' => 'FIRST ATLANTIC ASSET MGT. CO.',
                'pfa_code' => 'FIRST0030',
                'country_id' => 76
            ],
            [
                'id' => 10051,
                'name' => 'SENTINEL ASSET MANAGEMENT',
                'pfa_code' => 'SENTINEL0031',
                'country_id' => 76
            ],
            [
                'id' => 10052,
                'name' => 'DELTA CAPITAL LIMITED ',
                'pfa_code' => 'DELTA0032',
                'country_id' => 76
            ],
            [
                'id' => 10053,
                'name' => 'CHAPEL HILL DENHAM MGT. GH.',
                'pfa_code' => 'CHAPEL0033',
                'country_id' => 76
            ],
            [
                'id' => 10054,
                'name' => 'REGAL ALLIANCE INVESTMENTS',
                'pfa_code' => 'REGAL0034',
                'country_id' => 76
            ],
            [
                'id' => 10055,
                'name' => 'ALGEBRA CAPITAL MANAGEMENT',
                'pfa_code' => 'ALGEBRA0035',
                'country_id' => 76
            ],
            [
                'id' => 10056,
                'name' => 'CITITRUST CAPITAL LIMITED ',
                'pfa_code' => 'CITITRUST0036',
                'country_id' => 76
            ],
            [
                'id' => 10057,
                'name' => 'OYA CAPITAL LIMITED ',
                'pfa_code' => 'OYA0037',
                'country_id' => 76
            ],
            [
                'id' => 10058,
                'name' => 'PHOENIXAFRICA SECURITIES',
                'pfa_code' => 'PHOENIXAFRICA0038',
                'country_id' => 76
            ],
            [
                'id' => 10059,
                'name' => 'MONARCH CAPITAL LIMITED ',
                'pfa_code' => 'MONARCH0039',
                'country_id' => 76
            ],
            [
                'id' => 10060,
                'name' => 'SALEM FINANCIAL SERVICES',
                'pfa_code' => 'SALEM0040',
                'country_id' => 76
            ],
            [
                'id' => 10061,
                'name' => 'ASHFIELD INVESTMENT MANAGERS',
                'pfa_code' => 'ASHFIELD0041',
                'country_id' => 76
            ],
            [
                'id' => 10062,
                'name' => 'TEMPLE INVESTMENTS LIMITED',
                'pfa_code' => 'TEMPLE0042',
                'country_id' => 76
            ],
            [
                'id' => 10063,
                'name' => 'Amana Umbrella Pension Scheme',
                'pfa_code' => 'UMB-AMANA0001',
                'country_id' => 106
            ],
            [
                'id' => 10064,
                'name' => 'APA Life Umbrella Retirement Fund',
                'pfa_code' => 'UMB-APA0002',
                'country_id' => 106
            ],
            [
                'id' => 10065,
                'name' => 'British American Insurance Umbrella Retirement Fund',
                'pfa_code' => 'UMB-BRITISH0003',
                'country_id' => 106
            ],
            [
                'id' => 10066,
                'name' => 'CFC Life Assurance Ltd Umbrella Fund',
                'pfa_code' => 'UMB-CFC0004',
                'country_id' => 106
            ],
            [
                'id' => 10067,
                'name' => 'CIC Umbrella Retirement Benefits Scheme',
                'pfa_code' => 'UMB-CIC0005',
                'country_id' => 106
            ],
            [
                'id' => 10068,
                'name' => 'CICAM Umbrella Retirement Fund',
                'pfa_code' => 'UMB-CICAM0006',
                'country_id' => 106
            ],
            [
                'id' => 10069,
                'name' => 'Co-optrust Investment Retirement Benefits Scheme',
                'pfa_code' => 'UMB-CO-OPTRUST0007',
                'country_id' => 106
            ],
            [
                'id' => 10070,
                'name' => 'County Pension Fund',
                'pfa_code' => 'UMB-COUNTY0008',
                'country_id' => 106
            ],
            [
                'id' => 10071,
                'name' => 'Cytonn Umbrella Retirement Benefits Scheme',
                'pfa_code' => 'UMB-CYTONN0009',
                'country_id' => 106
            ],
            [
                'id' => 10072,
                'name' => 'Enwealth Umbrella Fund',
                'pfa_code' => 'UMB-ENWEALTH0010',
                'country_id' => 106
            ],
            [
                'id' => 10073,
                'name' => 'Fusion Umbrella Retirement Benefits Scheme',
                'pfa_code' => 'UMB-FUSION0011',
                'country_id' => 106
            ],
            [
                'id' => 10074,
                'name' => 'ICEALION Umbrella Retirement Benefits Scheme',
                'pfa_code' => 'UMB-ICEALION0012',
                'country_id' => 106
            ],
            [
                'id' => 10075,
                'name' => 'ICEALION Guaranteed Umbrella Fund',
                'pfa_code' => 'UMB-ICEALION0013',
                'country_id' => 106
            ],
            [
                'id' => 100106,
                'name' => 'Kenindia Umbrella Provident Fund',
                'pfa_code' => 'UMB-KENINDIA0014',
                'country_id' => 106
            ],
            [
                'id' => 10077,
                'name' => 'Kenya Orient Umbrella Pension Fund',
                'pfa_code' => 'UMB-KENYA0015',
                'country_id' => 106
            ],
            [
                'id' => 10078,
                'name' => 'Kivuli Umbrella Fund',
                'pfa_code' => 'UMB-KIVULI0016',
                'country_id' => 106
            ],
            [
                'id' => 10079,
                'name' => 'Madison Umbrella Retirement Benefits Scheme',
                'pfa_code' => 'UMB-MADISON0017',
                'country_id' => 106
            ],
            [
                'id' => 10080,
                'name' => 'Minet Kenya Umbrella Retirement Fund',
                'pfa_code' => 'UMB-MINET0018',
                'country_id' => 106
            ],
            [
                'id' => 10081,
                'name' => 'Mwavuli Pension Fund',
                'pfa_code' => 'UMB-MWAVULI0019',
                'country_id' => 106
            ],
            [
                'id' => 10082,
                'name' => 'Ngao Umbrella Pension Scheme',
                'pfa_code' => 'UMB-NGAO0020',
                'country_id' => 106
            ],
            [
                'id' => 10083,
                'name' => 'Octagon Umbrella Retirement Benefits Scheme',
                'pfa_code' => 'UMB-OCTAGON0021',
                'country_id' => 106
            ],
            [
                'id' => 10084,
                'name' => 'Old Mutual Umbrella Retirement Benefits Scheme',
                'pfa_code' => 'UMB-MUTUAL0022',
                'country_id' => 106
            ],
            [
                'id' => 10085,
                'name' => 'Sanlam Umbrella Retirement Fund',
                'pfa_code' => 'UMB-SANLAM0023',
                'country_id' => 106
            ],
            [
                'id' => 10086,
                'name' => 'Pioneer Umbrella Retirement Fund',
                'pfa_code' => 'UMB-PIONEER0024',
                'country_id' => 106
            ],
            [
                'id' => 10087,
                'name' => 'Prudential Umbrella Retirement Benefits Scheme',
                'pfa_code' => 'UMB-PRUDENTIAL0025',
                'country_id' => 106
            ],
            [
                'id' => 10088,
                'name' => 'Suluhu Umbrella Scheme',
                'pfa_code' => 'UMB-SULUHU0026',
                'country_id' => 106
            ],
            [
                'id' => 10089,
                'name' => 'Takaful Umbrella Fund',
                'pfa_code' => 'UMB-TAKAFUL0027',
                'country_id' => 106
            ],
            [
                'id' => 10090,
                'name' => 'The Jubilee Insurance Umbrella Scheme',
                'pfa_code' => 'UMB-JUBILEE0028',
                'country_id' => 106
            ],
            [
                'id' => 10091,
                'name' => 'The Kenyan Alliance Insurance Company Limited Umbrella Fund',
                'pfa_code' => 'UMB-ALLIANCE0029',
                'country_id' => 106
            ],
            [
                'id' => 10092,
                'name' => 'The Monarch Umbrella Retirement Fund',
                'pfa_code' => 'UMB-MONARCH0030',
                'country_id' => 106
            ],
            [
                'id' => 10093,
                'name' => 'UAP Umbrella Retirement Benefits Scheme',
                'pfa_code' => 'UMB-UAP0031',
                'country_id' => 106
            ],
            [
                'id' => 10094,
                'name' => 'Zamara Fanaka Retirement Fund (Provident & Pension Sections)',
                'pfa_code' => 'UMB-ZAMARA0032',
                'country_id' => 106
            ],
            [
                'id' => 10095,
                'name' => 'Amana Personal Pension Plan',
                'pfa_code' => 'IND-AMANA0033',
                'country_id' => 106
            ],
            [
                'id' => 10096,
                'name' => 'Apollo Insurance Co. Ltd. Individual Pension Arrangement',
                'pfa_code' => 'IND-APOLLO0034',
                'country_id' => 106
            ],
            [
                'id' => 10097,
                'name' => 'Benefits At Work Personal Pension Scheme',
                'pfa_code' => 'IND-BENEFITS0035',
                'country_id' => 106
            ],
            [
                'id' => 10098,
                'name' => 'Blue MSMEs Jua Kali Individual Retirement Benefits Scheme (Mbao Pension Plan)',
                'pfa_code' => 'IND-BLUE0036',
                'country_id' => 106
            ],
            [
                'id' => 10099,
                'name' => 'British American Personal Pension Plan',
                'pfa_code' => 'IND-BRITISH0037',
                'country_id' => 106
            ],
            [
                'id' => 10100,
                'name' => 'CFC Life Individual Pension Plan',
                'pfa_code' => 'IND-CFC0038',
                'country_id' => 106
            ],
            [
                'id' => 10101,
                'name' => 'Chancery Personal Pension Plan',
                'pfa_code' => 'IND-CHANCERY0039',
                'country_id' => 106
            ],
            [
                'id' => 10102,
                'name' => 'CIC (Jipange Personal Pension Plan)',
                'pfa_code' => 'IND-CIC0040',
                'country_id' => 106
            ],
            [
                'id' => 10103,
                'name' => 'NCBA Individual Pension Plan',
                'pfa_code' => 'IND-NCBA0041',
                'country_id' => 106
            ],
            [
                'id' => 10104,
                'name' => 'CPF Individual Pension Scheme',
                'pfa_code' => 'IND-CPF0042',
                'country_id' => 106
            ],
            [
                'id' => 10105,
                'name' => 'Cytonn Personal Retirement Benefits Scheme',
                'pfa_code' => 'IND-CYTONN0043',
                'country_id' => 106
            ],
            [
                'id' => 10106,
                'name' => 'Dry Associates Personal Provident Plan',
                'pfa_code' => 'IND-DRY0043',
                'country_id' => 106
            ],
            [
                'id' => 10107,
                'name' => 'Enwealth Diaspora & Expatriates Retirement Fund',
                'pfa_code' => 'IND-ENWEALTH0044',
                'country_id' => 106
            ],
            [
                'id' => 10108,
                'name' => 'Enwealth Personal Pension Scheme',
                'pfa_code' => 'IND-ENWEALTH0045',
                'country_id' => 106
            ],
            [
                'id' => 10109,
                'name' => 'Fahari Retirement Plan',
                'pfa_code' => 'IND-FAHARI0046',
                'country_id' => 106
            ],
            [
                'id' => 10110,
                'name' => 'GA Life Personal Provident Plan',
                'pfa_code' => 'IND-PROVIDENT0047',
                'country_id' => 106
            ],
            [
                'id' => 10111,
                'name' => 'GA Life Personal Pension Plan',
                'pfa_code' => 'IND-GA0048',
                'country_id' => 106
            ],
            [
                'id' => 10112,
                'name' => 'Gencap Individual Pension Plan',
                'pfa_code' => 'IND-GENCAP0049',
                'country_id' => 106
            ],
            [
                'id' => 10113,
                'name' => 'ICEA Lion Individual Retirement Benefits Scheme',
                'pfa_code' => 'IND-ICEA0050',
                'country_id' => 106
            ],
            [
                'id' => 10114,
                'name' => 'Jubilee Insurance Company Ltd Personal Pension Plan',
                'pfa_code' => 'IND-JUBILEE0051',
                'country_id' => 106
            ],
            [
                'id' => 10115,
                'name' => 'Kenindia Assurance Co. Ltd. Personal Pension Plan',
                'pfa_code' => 'IND-KENINDIA0052',
                'country_id' => 106
            ],
            [
                'id' => 10116,
                'name' => 'Kenyan Alliance Insurance Co. Ltd. Individual Retirement Benefits Scheme',
                'pfa_code' => 'IND-KENYAN0053',
                'country_id' => 106
            ],
            [
                'id' => 10117,
                'name' => 'Madison Insurance Personal Pension Plan',
                'pfa_code' => 'IND-MADISON0054',
                'country_id' => 106
            ],
            [
                'id' => 10118,
                'name' => 'Mafao Fund',
                'pfa_code' => 'IND-MAFAO0055',
                'country_id' => 106
            ],
            [
                'id' => 10119,
                'name' => 'Mercantile Personal Provident Fund Scheme',
                'pfa_code' => 'IND-MERCANTILE0056',
                'country_id' => 106
            ],
            [
                'id' => 10120,
                'name' => 'Minet Individual Pension Plan',
                'pfa_code' => 'IND-MINET0057',
                'country_id' => 106
            ],
            [
                'id' => 10121,
                'name' => 'Mwavuli Individual Pension Plan',
                'pfa_code' => 'IND-MWAVULI0058',
                'country_id' => 106
            ],
            [
                'id' => 10122,
                'name' => 'NTISL Personal Pension Plan',
                'pfa_code' => 'IND-NTISL0059',
                'country_id' => 106
            ],
            [
                'id' => 10123,
                'name' => 'The Heritage AII Company Ltd. Individual Retirement Benefits Scheme',
                'pfa_code' => 'IND-HERITAGE0060',
                'country_id' => 106
            ],
            [
                'id' => 10124,
                'name' => 'The Kenya Orient Individual Pension Plan',
                'pfa_code' => 'IND-ORIENT0061',
                'country_id' => 106
            ],
            [
                'id' => 10125,
                'name' => 'The Monarch Personal Pension Plan',
                'pfa_code' => 'IND-MONARCH0062',
                'country_id' => 106
            ],
            [
                'id' => 10126,
                'name' => 'Octagon Personal Pension Scheme',
                'pfa_code' => 'IND-OCTAGON0063',
                'country_id' => 106
            ],
            [
                'id' => 10127,
                'name' => 'Old Mutual Individual Retirement Benefits Scheme',
                'pfa_code' => 'IND-OLDMUTUAL0064',
                'country_id' => 106
            ],
            [
                'id' => 10128,
                'name' => 'Pan Africa Life Personal Pension Plan',
                'pfa_code' => 'IND-PAN0065',
                'country_id' => 106
            ],
            [
                'id' => 10129,
                'name' => 'Prudential Individual Retirement Benefits Scheme',
                'pfa_code' => 'IND-PRUDENTIAL0066',
                'country_id' => 106
            ],
            [
                'id' => 10130,
                'name' => 'Pioneer Assurance Individual Retirement Benefits Scheme',
                'pfa_code' => 'IND-PIONEER0067',
                'country_id' => 106
            ],
            [
                'id' => 10131,
                'name' => 'Stanlib Individual Pension Plan',
                'pfa_code' => 'IND-STANLIB0068',
                'country_id' => 106
            ],
            [
                'id' => 10132,
                'name' => 'UAP Life Assurance Individual Retirement Benefits Plan',
                'pfa_code' => 'IND-UAP0069',
                'country_id' => 106
            ],
            [
                'id' => 10133,
                'name' => 'Wakili Personal Retirement Benefits Scheme',
                'pfa_code' => 'IND-WAKILI0070',
                'country_id' => 106
            ],
            [
                'id' => 10134,
                'name' => 'Zamara Vuna Pension Plan',
                'pfa_code' => 'IND-ZAMARA0071',
                'country_id' => 106
            ],
            [
                'id' => 10135,
                'name' => 'Zimele Personal Pension Plan',
                'pfa_code' => 'IND-ZIMELE0072',
                'country_id' => 106
            ],
            [
                'id' => 10136,
                'name' => 'NPF PENSION',
                'pfa_code' => '0047',
                'country_id' => 154
            ],
            [
                'id' => 10137,
                'name' => 'Norrenberger Pensions Limited',
                'pfa_code' => '0036',
                'country_id' => 154
            ]
        ];

        \DB::table('pfas')->insert($insert);
    }
}
