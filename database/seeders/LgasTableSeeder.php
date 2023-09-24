<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class LgasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Schema::disableForeignKeyConstraints();
        \DB::table('lgas')->truncate();
        \DB::table('lgas')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'Aba South',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'Arochukwu',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'Bende',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 =>
            array(
                'id' => 4,
                'name' => 'Ikwuano',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 =>
            array(
                'id' => 5,
                'name' => 'Isiala Ngwa North',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            5 =>
            array(
                'id' => 6,
                'name' => 'Isiala Ngwa South',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            6 =>
            array(
                'id' => 7,
                'name' => 'Isuikwuato',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            7 =>
            array(
                'id' => 8,
                'name' => 'Obi Ngwa',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            8 =>
            array(
                'id' => 9,
                'name' => 'Ohafia',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            9 =>
            array(
                'id' => 10,
                'name' => 'Osisioma',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            10 =>
            array(
                'id' => 11,
                'name' => 'Ugwunagbo',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            11 =>
            array(
                'id' => 12,
                'name' => 'Ukwa East',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            12 =>
            array(
                'id' => 13,
                'name' => 'Ukwa West',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            13 =>
            array(
                'id' => 14,
                'name' => 'Umuahia North',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            14 =>
            array(
                'id' => 15,
                'name' => 'Umuahia South',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            15 =>
            array(
                'id' => 16,
                'name' => 'Umu Nneochi',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            16 =>
            array(
                'id' => 17,
                'name' => 'Fufure',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            17 =>
            array(
                'id' => 18,
                'name' => 'Ganye',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            18 =>
            array(
                'id' => 19,
                'name' => 'Gayuk',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            19 =>
            array(
                'id' => 20,
                'name' => 'Gombi',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            20 =>
            array(
                'id' => 21,
                'name' => 'Grie',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            21 =>
            array(
                'id' => 22,
                'name' => 'Hong',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            22 =>
            array(
                'id' => 23,
                'name' => 'Jada',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            23 =>
            array(
                'id' => 24,
                'name' => 'Lamurde',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            24 =>
            array(
                'id' => 25,
                'name' => 'Madagali',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            25 =>
            array(
                'id' => 26,
                'name' => 'Maiha',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            26 =>
            array(
                'id' => 27,
                'name' => 'Mayo Belwa',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            27 =>
            array(
                'id' => 28,
                'name' => 'Michika',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            28 =>
            array(
                'id' => 29,
                'name' => 'Mubi North',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            29 =>
            array(
                'id' => 30,
                'name' => 'Mubi South',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            30 =>
            array(
                'id' => 31,
                'name' => 'Numan',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            31 =>
            array(
                'id' => 32,
                'name' => 'Shelleng',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            32 =>
            array(
                'id' => 33,
                'name' => 'Song',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            33 =>
            array(
                'id' => 34,
                'name' => 'Toungo',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            34 =>
            array(
                'id' => 35,
                'name' => 'Yola North',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            35 =>
            array(
                'id' => 36,
                'name' => 'Yola South',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            36 =>
            array(
                'id' => 37,
                'name' => 'Eastern Obolo',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            37 =>
            array(
                'id' => 38,
                'name' => 'Eket',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            38 =>
            array(
                'id' => 39,
                'name' => 'Esit Eket',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            39 =>
            array(
                'id' => 40,
                'name' => 'Essien Udim',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            40 =>
            array(
                'id' => 41,
                'name' => 'Etim Ekpo',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            41 =>
            array(
                'id' => 42,
                'name' => 'Etinan',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            42 =>
            array(
                'id' => 43,
                'name' => 'Ibeno',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            43 =>
            array(
                'id' => 44,
                'name' => 'Ibesikpo Asutan',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            44 =>
            array(
                'id' => 45,
                'name' => 'Ibiono-Ibom',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            45 =>
            array(
                'id' => 46,
                'name' => 'Ika',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            46 =>
            array(
                'id' => 47,
                'name' => 'Ikono',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            47 =>
            array(
                'id' => 48,
                'name' => 'Ikot Abasi',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            48 =>
            array(
                'id' => 49,
                'name' => 'Ikot Ekpene',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            49 =>
            array(
                'id' => 50,
                'name' => 'Ini',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            50 =>
            array(
                'id' => 51,
                'name' => 'Itu',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            51 =>
            array(
                'id' => 52,
                'name' => 'Mbo',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            52 =>
            array(
                'id' => 53,
                'name' => 'Mkpat-Enin',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            53 =>
            array(
                'id' => 54,
                'name' => 'Nsit-Atai',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            54 =>
            array(
                'id' => 55,
                'name' => 'Nsit-Ibom',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            55 =>
            array(
                'id' => 56,
                'name' => 'Nsit-Ubium',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            56 =>
            array(
                'id' => 57,
                'name' => 'Obot Akara',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            57 =>
            array(
                'id' => 58,
                'name' => 'Okobo',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            58 =>
            array(
                'id' => 59,
                'name' => 'Onna',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            59 =>
            array(
                'id' => 60,
                'name' => 'Oron',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            60 =>
            array(
                'id' => 61,
                'name' => 'Oruk Anam',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            61 =>
            array(
                'id' => 62,
                'name' => 'Udung-Uko',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            62 =>
            array(
                'id' => 63,
                'name' => 'Ukanafun',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            63 =>
            array(
                'id' => 64,
                'name' => 'Uruan',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            64 =>
            array(
                'id' => 65,
                'name' => 'Urue-Offong/Oruko',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            65 =>
            array(
                'id' => 66,
                'name' => 'Uyo',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            66 =>
            array(
                'id' => 67,
                'name' => 'Anambra East',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            67 =>
            array(
                'id' => 68,
                'name' => 'Anambra West',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            68 =>
            array(
                'id' => 69,
                'name' => 'Anaocha',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            69 =>
            array(
                'id' => 70,
                'name' => 'Awka North',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            70 =>
            array(
                'id' => 71,
                'name' => 'Awka South',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            71 =>
            array(
                'id' => 72,
                'name' => 'Ayamelum',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            72 =>
            array(
                'id' => 73,
                'name' => 'Dunukofia',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            73 =>
            array(
                'id' => 74,
                'name' => 'Ekwusigo',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            74 =>
            array(
                'id' => 75,
                'name' => 'Idemili North',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            75 =>
            array(
                'id' => 76,
                'name' => 'Idemili South',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            76 =>
            array(
                'id' => 77,
                'name' => 'Ihiala',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            77 =>
            array(
                'id' => 78,
                'name' => 'Njikoka',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            78 =>
            array(
                'id' => 79,
                'name' => 'Nnewi North',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            79 =>
            array(
                'id' => 80,
                'name' => 'Nnewi South',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            80 =>
            array(
                'id' => 81,
                'name' => 'Ogbaru',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            81 =>
            array(
                'id' => 82,
                'name' => 'Onitsha North',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            82 =>
            array(
                'id' => 83,
                'name' => 'Onitsha South',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            83 =>
            array(
                'id' => 84,
                'name' => 'Orumba North',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            84 =>
            array(
                'id' => 85,
                'name' => 'Orumba South',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            85 =>
            array(
                'id' => 86,
                'name' => 'Oyi',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            86 =>
            array(
                'id' => 87,
                'name' => 'Bauchi',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            87 =>
            array(
                'id' => 88,
                'name' => 'Bogoro',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            88 =>
            array(
                'id' => 89,
                'name' => 'Damban',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            89 =>
            array(
                'id' => 90,
                'name' => 'Darazo',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            90 =>
            array(
                'id' => 91,
                'name' => 'Dass',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            91 =>
            array(
                'id' => 92,
                'name' => 'Gamawa',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            92 =>
            array(
                'id' => 93,
                'name' => 'Ganjuwa',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            93 =>
            array(
                'id' => 94,
                'name' => 'Giade',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            94 =>
            array(
                'id' => 95,
                'name' => 'Itas/Gadau',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            95 =>
            array(
                'id' => 96,
                'name' => 'Jamaare',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            96 =>
            array(
                'id' => 97,
                'name' => 'Katagum',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            97 =>
            array(
                'id' => 98,
                'name' => 'Kirfi',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            98 =>
            array(
                'id' => 99,
                'name' => 'Misau',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            99 =>
            array(
                'id' => 100,
                'name' => 'Ningi',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            100 =>
            array(
                'id' => 101,
                'name' => 'Shira',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            101 =>
            array(
                'id' => 102,
                'name' => 'Tafawa Balewa',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            102 =>
            array(
                'id' => 103,
                'name' => 'Toro',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            103 =>
            array(
                'id' => 104,
                'name' => 'Warji',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            104 =>
            array(
                'id' => 105,
                'name' => 'Zaki',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            105 =>
            array(
                'id' => 106,
                'name' => 'Ekeremor',
                'state_id' => 6,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            106 =>
            array(
                'id' => 107,
                'name' => 'Kolokuma/Opokuma',
                'state_id' => 6,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            107 =>
            array(
                'id' => 108,
                'name' => 'Nembe',
                'state_id' => 6,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            108 =>
            array(
                'id' => 109,
                'name' => 'Ogbia',
                'state_id' => 6,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            109 =>
            array(
                'id' => 110,
                'name' => 'Sagbama',
                'state_id' => 6,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            110 =>
            array(
                'id' => 111,
                'name' => 'Southern Ijaw',
                'state_id' => 6,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            111 =>
            array(
                'id' => 112,
                'name' => 'Yenagoa',
                'state_id' => 6,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            112 =>
            array(
                'id' => 113,
                'name' => 'Apa',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            113 =>
            array(
                'id' => 114,
                'name' => 'Ado',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            114 =>
            array(
                'id' => 115,
                'name' => 'Buruku',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            115 =>
            array(
                'id' => 116,
                'name' => 'Gboko',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            116 =>
            array(
                'id' => 117,
                'name' => 'Guma',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            117 =>
            array(
                'id' => 118,
                'name' => 'Gwer East',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            118 =>
            array(
                'id' => 119,
                'name' => 'Gwer West',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            119 =>
            array(
                'id' => 120,
                'name' => 'Katsina-Ala',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            120 =>
            array(
                'id' => 121,
                'name' => 'Konshisha',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            121 =>
            array(
                'id' => 122,
                'name' => 'Kwande',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            122 =>
            array(
                'id' => 123,
                'name' => 'Logo',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            123 =>
            array(
                'id' => 124,
                'name' => 'Makurdi',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            124 =>
            array(
                'id' => 125,
                'name' => 'Obi',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            125 =>
            array(
                'id' => 126,
                'name' => 'Ogbadibo',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            126 =>
            array(
                'id' => 127,
                'name' => 'Ohimini',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            127 =>
            array(
                'id' => 128,
                'name' => 'Oju',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            128 =>
            array(
                'id' => 129,
                'name' => 'Okpokwu',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            129 =>
            array(
                'id' => 130,
                'name' => 'Oturkpo',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            130 =>
            array(
                'id' => 131,
                'name' => 'Tarka',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            131 =>
            array(
                'id' => 132,
                'name' => 'Ukum',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            132 =>
            array(
                'id' => 133,
                'name' => 'Ushongo',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            133 =>
            array(
                'id' => 134,
                'name' => 'Vandeikya',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            134 =>
            array(
                'id' => 135,
                'name' => 'Askira/Uba',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            135 =>
            array(
                'id' => 136,
                'name' => 'Bama',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            136 =>
            array(
                'id' => 137,
                'name' => 'Bayo',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            137 =>
            array(
                'id' => 138,
                'name' => 'Biu',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            138 =>
            array(
                'id' => 139,
                'name' => 'Chibok',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            139 =>
            array(
                'id' => 140,
                'name' => 'Damboa',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            140 =>
            array(
                'id' => 141,
                'name' => 'Dikwa',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            141 =>
            array(
                'id' => 142,
                'name' => 'Gubio',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            142 =>
            array(
                'id' => 143,
                'name' => 'Guzamala',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            143 =>
            array(
                'id' => 144,
                'name' => 'Gwoza',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            144 =>
            array(
                'id' => 145,
                'name' => 'Hawul',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            145 =>
            array(
                'id' => 146,
                'name' => 'Jere',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            146 =>
            array(
                'id' => 147,
                'name' => 'Kaga',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            147 =>
            array(
                'id' => 148,
                'name' => 'Kala/Balge',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            148 =>
            array(
                'id' => 149,
                'name' => 'Konduga',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            149 =>
            array(
                'id' => 150,
                'name' => 'Kukawa',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            150 =>
            array(
                'id' => 151,
                'name' => 'Kwaya Kusar',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            151 =>
            array(
                'id' => 152,
                'name' => 'Mafa',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            152 =>
            array(
                'id' => 153,
                'name' => 'Magumeri',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            153 =>
            array(
                'id' => 154,
                'name' => 'Maiduguri',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            154 =>
            array(
                'id' => 155,
                'name' => 'Marte',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            155 =>
            array(
                'id' => 156,
                'name' => 'Mobbar',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            156 =>
            array(
                'id' => 157,
                'name' => 'Monguno',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            157 =>
            array(
                'id' => 158,
                'name' => 'Ngala',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            158 =>
            array(
                'id' => 159,
                'name' => 'Nganzai',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            159 =>
            array(
                'id' => 160,
                'name' => 'Shani',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            160 =>
            array(
                'id' => 161,
                'name' => 'Akamkpa',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            161 =>
            array(
                'id' => 162,
                'name' => 'Akpabuyo',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            162 =>
            array(
                'id' => 163,
                'name' => 'Bakassi',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            163 =>
            array(
                'id' => 164,
                'name' => 'Bekwarra',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            164 =>
            array(
                'id' => 165,
                'name' => 'Biase',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            165 =>
            array(
                'id' => 166,
                'name' => 'Boki',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            166 =>
            array(
                'id' => 167,
                'name' => 'Calabar Municipal',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            167 =>
            array(
                'id' => 168,
                'name' => 'Calabar South',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            168 =>
            array(
                'id' => 169,
                'name' => 'Etung',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            169 =>
            array(
                'id' => 170,
                'name' => 'Ikom',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            170 =>
            array(
                'id' => 171,
                'name' => 'Obanliku',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            171 =>
            array(
                'id' => 172,
                'name' => 'Obubra',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            172 =>
            array(
                'id' => 173,
                'name' => 'Obudu',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            173 =>
            array(
                'id' => 174,
                'name' => 'Odukpani',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            174 =>
            array(
                'id' => 175,
                'name' => 'Ogoja',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            175 =>
            array(
                'id' => 176,
                'name' => 'Yakuur',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            176 =>
            array(
                'id' => 177,
                'name' => 'Yala',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            177 =>
            array(
                'id' => 178,
                'name' => 'Aniocha South',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            178 =>
            array(
                'id' => 179,
                'name' => 'Bomadi',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            179 =>
            array(
                'id' => 180,
                'name' => 'Burutu',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            180 =>
            array(
                'id' => 181,
                'name' => 'Ethiope East',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            181 =>
            array(
                'id' => 182,
                'name' => 'Ethiope West',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            182 =>
            array(
                'id' => 183,
                'name' => 'Ika North East',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            183 =>
            array(
                'id' => 184,
                'name' => 'Ika South',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            184 =>
            array(
                'id' => 185,
                'name' => 'Isoko North',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            185 =>
            array(
                'id' => 186,
                'name' => 'Isoko South',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            186 =>
            array(
                'id' => 187,
                'name' => 'Ndokwa East',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            187 =>
            array(
                'id' => 188,
                'name' => 'Ndokwa West',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            188 =>
            array(
                'id' => 189,
                'name' => 'Okpe',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            189 =>
            array(
                'id' => 190,
                'name' => 'Oshimili North',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            190 =>
            array(
                'id' => 191,
                'name' => 'Oshimili South',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            191 =>
            array(
                'id' => 192,
                'name' => 'Patani',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            192 =>
            array(
                'id' => 193,
                'name' => 'Sapele',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            193 =>
            array(
                'id' => 194,
                'name' => 'Udu',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            194 =>
            array(
                'id' => 195,
                'name' => 'Ughelli North',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            195 =>
            array(
                'id' => 196,
                'name' => 'Ughelli South',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            196 =>
            array(
                'id' => 197,
                'name' => 'Ukwuani',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            197 =>
            array(
                'id' => 198,
                'name' => 'Uvwie',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            198 =>
            array(
                'id' => 199,
                'name' => 'Warri North',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            199 =>
            array(
                'id' => 200,
                'name' => 'Warri South',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            200 =>
            array(
                'id' => 201,
                'name' => 'Warri South West',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            201 =>
            array(
                'id' => 202,
                'name' => 'Afikpo North',
                'state_id' => 11,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            202 =>
            array(
                'id' => 203,
                'name' => 'Afikpo South',
                'state_id' => 11,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            203 =>
            array(
                'id' => 204,
                'name' => 'Ebonyi',
                'state_id' => 11,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            204 =>
            array(
                'id' => 205,
                'name' => 'Ezza North',
                'state_id' => 11,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            205 =>
            array(
                'id' => 206,
                'name' => 'Ezza South',
                'state_id' => 11,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            206 =>
            array(
                'id' => 207,
                'name' => 'Ikwo',
                'state_id' => 11,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            207 =>
            array(
                'id' => 208,
                'name' => 'Ishielu',
                'state_id' => 11,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            208 =>
            array(
                'id' => 209,
                'name' => 'Ivo',
                'state_id' => 11,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            209 =>
            array(
                'id' => 210,
                'name' => 'Izzi',
                'state_id' => 11,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            210 =>
            array(
                'id' => 211,
                'name' => 'Ohaozara',
                'state_id' => 11,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            211 =>
            array(
                'id' => 212,
                'name' => 'Ohaukwu',
                'state_id' => 11,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            212 =>
            array(
                'id' => 213,
                'name' => 'Onicha',
                'state_id' => 11,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            213 =>
            array(
                'id' => 214,
                'name' => 'Egor',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            214 =>
            array(
                'id' => 215,
                'name' => 'Esan Central',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            215 =>
            array(
                'id' => 216,
                'name' => 'Esan North-East',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            216 =>
            array(
                'id' => 217,
                'name' => 'Esan South-East',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            217 =>
            array(
                'id' => 218,
                'name' => 'Esan West',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            218 =>
            array(
                'id' => 219,
                'name' => 'Etsako Central',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            219 =>
            array(
                'id' => 220,
                'name' => 'Etsako East',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            220 =>
            array(
                'id' => 221,
                'name' => 'Etsako West',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            221 =>
            array(
                'id' => 222,
                'name' => 'Igueben',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            222 =>
            array(
                'id' => 223,
                'name' => 'Ikpoba Okha',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            223 =>
            array(
                'id' => 224,
                'name' => 'Orhionmwon',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            224 =>
            array(
                'id' => 225,
                'name' => 'Oredo',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            225 =>
            array(
                'id' => 226,
                'name' => 'Ovia North-East',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            226 =>
            array(
                'id' => 227,
                'name' => 'Ovia South-West',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            227 =>
            array(
                'id' => 228,
                'name' => 'Owan East',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            228 =>
            array(
                'id' => 229,
                'name' => 'Owan West',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            229 =>
            array(
                'id' => 230,
                'name' => 'Uhunmwonde',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            230 =>
            array(
                'id' => 231,
                'name' => 'Efon',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            231 =>
            array(
                'id' => 232,
                'name' => 'Ekiti East',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            232 =>
            array(
                'id' => 233,
                'name' => 'Ekiti South-West',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            233 =>
            array(
                'id' => 234,
                'name' => 'Ekiti West',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            234 =>
            array(
                'id' => 235,
                'name' => 'Emure',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            235 =>
            array(
                'id' => 236,
                'name' => 'Gbonyin',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            236 =>
            array(
                'id' => 237,
                'name' => 'Ido Osi',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            237 =>
            array(
                'id' => 238,
                'name' => 'Ijero',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            238 =>
            array(
                'id' => 239,
                'name' => 'Ikere',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            239 =>
            array(
                'id' => 240,
                'name' => 'Ikole',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            240 =>
            array(
                'id' => 241,
                'name' => 'Ilejemeje',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            241 =>
            array(
                'id' => 242,
                'name' => 'Irepodun/Ifelodun',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            242 =>
            array(
                'id' => 243,
                'name' => 'Ise/Orun',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            243 =>
            array(
                'id' => 244,
                'name' => 'Moba',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            244 =>
            array(
                'id' => 245,
                'name' => 'Oye',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            245 =>
            array(
                'id' => 246,
                'name' => 'Awgu',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            246 =>
            array(
                'id' => 247,
                'name' => 'Enugu East',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            247 =>
            array(
                'id' => 248,
                'name' => 'Enugu North',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            248 =>
            array(
                'id' => 249,
                'name' => 'Enugu South',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            249 =>
            array(
                'id' => 250,
                'name' => 'Ezeagu',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            250 =>
            array(
                'id' => 251,
                'name' => 'Igbo Etiti',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            251 =>
            array(
                'id' => 252,
                'name' => 'Igbo Eze North',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            252 =>
            array(
                'id' => 253,
                'name' => 'Igbo Eze South',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            253 =>
            array(
                'id' => 254,
                'name' => 'Isi Uzo',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            254 =>
            array(
                'id' => 255,
                'name' => 'Nkanu East',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            255 =>
            array(
                'id' => 256,
                'name' => 'Nkanu West',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            256 =>
            array(
                'id' => 257,
                'name' => 'Nsukka',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            257 =>
            array(
                'id' => 258,
                'name' => 'Oji River',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            258 =>
            array(
                'id' => 259,
                'name' => 'Udenu',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            259 =>
            array(
                'id' => 260,
                'name' => 'Udi',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            260 =>
            array(
                'id' => 261,
                'name' => 'Uzo Uwani',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            261 =>
            array(
                'id' => 262,
                'name' => 'Bwari',
                'state_id' => 15,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            262 =>
            array(
                'id' => 263,
                'name' => 'Gwagwalada',
                'state_id' => 15,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            263 =>
            array(
                'id' => 264,
                'name' => 'Kuje',
                'state_id' => 15,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            264 =>
            array(
                'id' => 265,
                'name' => 'Kwali',
                'state_id' => 15,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            265 =>
            array(
                'id' => 266,
                'name' => 'Municipal Area Council',
                'state_id' => 15,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            266 =>
            array(
                'id' => 267,
                'name' => 'Balanga',
                'state_id' => 16,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            267 =>
            array(
                'id' => 268,
                'name' => 'Billiri',
                'state_id' => 16,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            268 =>
            array(
                'id' => 269,
                'name' => 'Dukku',
                'state_id' => 16,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            269 =>
            array(
                'id' => 270,
                'name' => 'Funakaye',
                'state_id' => 16,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            270 =>
            array(
                'id' => 271,
                'name' => 'Gombe',
                'state_id' => 16,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            271 =>
            array(
                'id' => 272,
                'name' => 'Kaltungo',
                'state_id' => 16,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            272 =>
            array(
                'id' => 273,
                'name' => 'Kwami',
                'state_id' => 16,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            273 =>
            array(
                'id' => 274,
                'name' => 'Nafada',
                'state_id' => 16,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            274 =>
            array(
                'id' => 275,
                'name' => 'Shongom',
                'state_id' => 16,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            275 =>
            array(
                'id' => 276,
                'name' => 'Yamaltu/Deba',
                'state_id' => 16,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            276 =>
            array(
                'id' => 277,
                'name' => 'Ahiazu Mbaise',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            277 =>
            array(
                'id' => 278,
                'name' => 'Ehime Mbano',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            278 =>
            array(
                'id' => 279,
                'name' => 'Ezinihitte',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            279 =>
            array(
                'id' => 280,
                'name' => 'Ideato North',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            280 =>
            array(
                'id' => 281,
                'name' => 'Ideato South',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            281 =>
            array(
                'id' => 282,
                'name' => 'Ihitte/Uboma',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            282 =>
            array(
                'id' => 283,
                'name' => 'Ikeduru',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            283 =>
            array(
                'id' => 284,
                'name' => 'Isiala Mbano',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            284 =>
            array(
                'id' => 285,
                'name' => 'Isu',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            285 =>
            array(
                'id' => 286,
                'name' => 'Mbaitoli',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            286 =>
            array(
                'id' => 287,
                'name' => 'Ngor Okpala',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            287 =>
            array(
                'id' => 288,
                'name' => 'Njaba',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            288 =>
            array(
                'id' => 289,
                'name' => 'Nkwerre',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            289 =>
            array(
                'id' => 290,
                'name' => 'Nwangele',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            290 =>
            array(
                'id' => 291,
                'name' => 'Obowo',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            291 =>
            array(
                'id' => 292,
                'name' => 'Oguta',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            292 =>
            array(
                'id' => 293,
                'name' => 'Ohaji/Egbema',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            293 =>
            array(
                'id' => 294,
                'name' => 'Okigwe',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            294 =>
            array(
                'id' => 295,
                'name' => 'Orlu',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            295 =>
            array(
                'id' => 296,
                'name' => 'Orsu',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            296 =>
            array(
                'id' => 297,
                'name' => 'Oru East',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            297 =>
            array(
                'id' => 298,
                'name' => 'Oru West',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            298 =>
            array(
                'id' => 299,
                'name' => 'Owerri Municipal',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            299 =>
            array(
                'id' => 300,
                'name' => 'Owerri North',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            300 =>
            array(
                'id' => 301,
                'name' => 'Owerri West',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            301 =>
            array(
                'id' => 302,
                'name' => 'Unuimo',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            302 =>
            array(
                'id' => 303,
                'name' => 'Babura',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            303 =>
            array(
                'id' => 304,
                'name' => 'Biriniwa',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            304 =>
            array(
                'id' => 305,
                'name' => 'Birnin Kudu',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            305 =>
            array(
                'id' => 306,
                'name' => 'Buji',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            306 =>
            array(
                'id' => 307,
                'name' => 'Dutse',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            307 =>
            array(
                'id' => 308,
                'name' => 'Gagarawa',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            308 =>
            array(
                'id' => 309,
                'name' => 'Garki',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            309 =>
            array(
                'id' => 310,
                'name' => 'Gumel',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            310 =>
            array(
                'id' => 311,
                'name' => 'Guri',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            311 =>
            array(
                'id' => 312,
                'name' => 'Gwaram',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            312 =>
            array(
                'id' => 313,
                'name' => 'Gwiwa',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            313 =>
            array(
                'id' => 314,
                'name' => 'Hadejia',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            314 =>
            array(
                'id' => 315,
                'name' => 'Jahun',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            315 =>
            array(
                'id' => 316,
                'name' => 'Kafin Hausa',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            316 =>
            array(
                'id' => 317,
                'name' => 'Kazaure',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            317 =>
            array(
                'id' => 318,
                'name' => 'Kiri Kasama',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            318 =>
            array(
                'id' => 319,
                'name' => 'Kiyawa',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            319 =>
            array(
                'id' => 320,
                'name' => 'Kaugama',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            320 =>
            array(
                'id' => 321,
                'name' => 'Maigatari',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            321 =>
            array(
                'id' => 322,
                'name' => 'Malam Madori',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            322 =>
            array(
                'id' => 323,
                'name' => 'Miga',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            323 =>
            array(
                'id' => 324,
                'name' => 'Ringim',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            324 =>
            array(
                'id' => 325,
                'name' => 'Roni',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            325 =>
            array(
                'id' => 326,
                'name' => 'Sule Tankarkar',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            326 =>
            array(
                'id' => 327,
                'name' => 'Taura',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            327 =>
            array(
                'id' => 328,
                'name' => 'Yankwashi',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            328 =>
            array(
                'id' => 329,
                'name' => 'Chikun',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            329 =>
            array(
                'id' => 330,
                'name' => 'Giwa',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            330 =>
            array(
                'id' => 331,
                'name' => 'Igabi',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            331 =>
            array(
                'id' => 332,
                'name' => 'Ikara',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            332 =>
            array(
                'id' => 333,
                'name' => 'Jaba',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            333 =>
            array(
                'id' => 334,
                'name' => 'Jemaa',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            334 =>
            array(
                'id' => 335,
                'name' => 'Kachia',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            335 =>
            array(
                'id' => 336,
                'name' => 'Kaduna North',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            336 =>
            array(
                'id' => 337,
                'name' => 'Kaduna South',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            337 =>
            array(
                'id' => 338,
                'name' => 'Kagarko',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            338 =>
            array(
                'id' => 339,
                'name' => 'Kajuru',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            339 =>
            array(
                'id' => 340,
                'name' => 'Kaura',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            340 =>
            array(
                'id' => 341,
                'name' => 'Kauru',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            341 =>
            array(
                'id' => 342,
                'name' => 'Kubau',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            342 =>
            array(
                'id' => 343,
                'name' => 'Kudan',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            343 =>
            array(
                'id' => 344,
                'name' => 'Lere',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            344 =>
            array(
                'id' => 345,
                'name' => 'Makarfi',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            345 =>
            array(
                'id' => 346,
                'name' => 'Sabon Gari',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            346 =>
            array(
                'id' => 347,
                'name' => 'Sanga',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            347 =>
            array(
                'id' => 348,
                'name' => 'Soba',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            348 =>
            array(
                'id' => 349,
                'name' => 'Zangon Kataf',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            349 =>
            array(
                'id' => 350,
                'name' => 'Zaria',
                'state_id' => 19,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            350 =>
            array(
                'id' => 351,
                'name' => 'Albasu',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            351 =>
            array(
                'id' => 352,
                'name' => 'Bagwai',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            352 =>
            array(
                'id' => 353,
                'name' => 'Bebeji',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            353 =>
            array(
                'id' => 354,
                'name' => 'Bichi',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            354 =>
            array(
                'id' => 355,
                'name' => 'Bunkure',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            355 =>
            array(
                'id' => 356,
                'name' => 'Dala',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            356 =>
            array(
                'id' => 357,
                'name' => 'Dambatta',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            357 =>
            array(
                'id' => 358,
                'name' => 'Dawakin Kudu',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            358 =>
            array(
                'id' => 359,
                'name' => 'Dawakin Tofa',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            359 =>
            array(
                'id' => 360,
                'name' => 'Doguwa',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            360 =>
            array(
                'id' => 361,
                'name' => 'Fagge',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            361 =>
            array(
                'id' => 362,
                'name' => 'Gabasawa',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            362 =>
            array(
                'id' => 363,
                'name' => 'Garko',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            363 =>
            array(
                'id' => 364,
                'name' => 'Garun Mallam',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            364 =>
            array(
                'id' => 365,
                'name' => 'Gaya',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            365 =>
            array(
                'id' => 366,
                'name' => 'Gezawa',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            366 =>
            array(
                'id' => 367,
                'name' => 'Gwale',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            367 =>
            array(
                'id' => 368,
                'name' => 'Gwarzo',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            368 =>
            array(
                'id' => 369,
                'name' => 'Kabo',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            369 =>
            array(
                'id' => 370,
                'name' => 'Kano Municipal',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            370 =>
            array(
                'id' => 371,
                'name' => 'Karaye',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            371 =>
            array(
                'id' => 372,
                'name' => 'Kibiya',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            372 =>
            array(
                'id' => 373,
                'name' => 'Kiru',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            373 =>
            array(
                'id' => 374,
                'name' => 'Kumbotso',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            374 =>
            array(
                'id' => 375,
                'name' => 'Kunchi',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            375 =>
            array(
                'id' => 376,
                'name' => 'Kura',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            376 =>
            array(
                'id' => 377,
                'name' => 'Madobi',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            377 =>
            array(
                'id' => 378,
                'name' => 'Makoda',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            378 =>
            array(
                'id' => 379,
                'name' => 'Minjibir',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            379 =>
            array(
                'id' => 380,
                'name' => 'Nasarawa',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            380 =>
            array(
                'id' => 381,
                'name' => 'Rano',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            381 =>
            array(
                'id' => 382,
                'name' => 'Rimin Gado',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            382 =>
            array(
                'id' => 383,
                'name' => 'Rogo',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            383 =>
            array(
                'id' => 384,
                'name' => 'Shanono',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            384 =>
            array(
                'id' => 385,
                'name' => 'Sumaila',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            385 =>
            array(
                'id' => 386,
                'name' => 'Takai',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            386 =>
            array(
                'id' => 387,
                'name' => 'Tarauni',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            387 =>
            array(
                'id' => 388,
                'name' => 'Tofa',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            388 =>
            array(
                'id' => 389,
                'name' => 'Tsanyawa',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            389 =>
            array(
                'id' => 390,
                'name' => 'Tudun Wada',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            390 =>
            array(
                'id' => 391,
                'name' => 'Ungogo',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            391 =>
            array(
                'id' => 392,
                'name' => 'Warawa',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            392 =>
            array(
                'id' => 393,
                'name' => 'Wudil',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            393 =>
            array(
                'id' => 394,
                'name' => 'Batagarawa',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            394 =>
            array(
                'id' => 395,
                'name' => 'Batsari',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            395 =>
            array(
                'id' => 396,
                'name' => 'Baure',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            396 =>
            array(
                'id' => 397,
                'name' => 'Bindawa',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            397 =>
            array(
                'id' => 398,
                'name' => 'Charanchi',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            398 =>
            array(
                'id' => 399,
                'name' => 'Dandume',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            399 =>
            array(
                'id' => 400,
                'name' => 'Danja',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            400 =>
            array(
                'id' => 401,
                'name' => 'Dan Musa',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            401 =>
            array(
                'id' => 402,
                'name' => 'Daura',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            402 =>
            array(
                'id' => 403,
                'name' => 'Dutsi',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            403 =>
            array(
                'id' => 404,
                'name' => 'Dutsin Ma',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            404 =>
            array(
                'id' => 405,
                'name' => 'Faskari',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            405 =>
            array(
                'id' => 406,
                'name' => 'Funtua',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            406 =>
            array(
                'id' => 407,
                'name' => 'Ingawa',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            407 =>
            array(
                'id' => 408,
                'name' => 'Jibia',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            408 =>
            array(
                'id' => 409,
                'name' => 'Kafur',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            409 =>
            array(
                'id' => 410,
                'name' => 'Kaita',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            410 =>
            array(
                'id' => 411,
                'name' => 'Kankara',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            411 =>
            array(
                'id' => 412,
                'name' => 'Kankia',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            412 =>
            array(
                'id' => 413,
                'name' => 'Katsina',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            413 =>
            array(
                'id' => 414,
                'name' => 'Kurfi',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            414 =>
            array(
                'id' => 415,
                'name' => 'Kusada',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            415 =>
            array(
                'id' => 416,
                'name' => 'MaiAdua',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            416 =>
            array(
                'id' => 417,
                'name' => 'Malumfashi',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            417 =>
            array(
                'id' => 418,
                'name' => 'Mani',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            418 =>
            array(
                'id' => 419,
                'name' => 'Mashi',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            419 =>
            array(
                'id' => 420,
                'name' => 'Matazu',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            420 =>
            array(
                'id' => 421,
                'name' => 'Musawa',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            421 =>
            array(
                'id' => 422,
                'name' => 'Rimi',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            422 =>
            array(
                'id' => 423,
                'name' => 'Sabuwa',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            423 =>
            array(
                'id' => 424,
                'name' => 'Safana',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            424 =>
            array(
                'id' => 425,
                'name' => 'Sandamu',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            425 =>
            array(
                'id' => 426,
                'name' => 'Zango',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            426 =>
            array(
                'id' => 427,
                'name' => 'Arewa Dandi',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            427 =>
            array(
                'id' => 428,
                'name' => 'Argungu',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            428 =>
            array(
                'id' => 429,
                'name' => 'Augie',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            429 =>
            array(
                'id' => 430,
                'name' => 'Bagudo',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            430 =>
            array(
                'id' => 431,
                'name' => 'Birnin Kebbi',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            431 =>
            array(
                'id' => 432,
                'name' => 'Bunza',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            432 =>
            array(
                'id' => 433,
                'name' => 'Dandi',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            433 =>
            array(
                'id' => 434,
                'name' => 'Fakai',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            434 =>
            array(
                'id' => 435,
                'name' => 'Gwandu',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            435 =>
            array(
                'id' => 436,
                'name' => 'Jega',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            436 =>
            array(
                'id' => 437,
                'name' => 'Kalgo',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            437 =>
            array(
                'id' => 438,
                'name' => 'Koko/Besse',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            438 =>
            array(
                'id' => 439,
                'name' => 'Maiyama',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            439 =>
            array(
                'id' => 440,
                'name' => 'Ngaski',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            440 =>
            array(
                'id' => 441,
                'name' => 'Sakaba',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            441 =>
            array(
                'id' => 442,
                'name' => 'Shanga',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            442 =>
            array(
                'id' => 443,
                'name' => 'Suru',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            443 =>
            array(
                'id' => 444,
                'name' => 'Wasagu/Danko',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            444 =>
            array(
                'id' => 445,
                'name' => 'Yauri',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            445 =>
            array(
                'id' => 446,
                'name' => 'Zuru',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            446 =>
            array(
                'id' => 447,
                'name' => 'Ajaokuta',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            447 =>
            array(
                'id' => 448,
                'name' => 'Ankpa',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            448 =>
            array(
                'id' => 449,
                'name' => 'Bassa',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            449 =>
            array(
                'id' => 450,
                'name' => 'Dekina',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            450 =>
            array(
                'id' => 451,
                'name' => 'Ibaji',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            451 =>
            array(
                'id' => 452,
                'name' => 'Idah',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            452 =>
            array(
                'id' => 453,
                'name' => 'Igalamela Odolu',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            453 =>
            array(
                'id' => 454,
                'name' => 'Ijumu',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            454 =>
            array(
                'id' => 455,
                'name' => 'Kabba/Bunu',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            455 =>
            array(
                'id' => 456,
                'name' => 'Kogi',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            456 =>
            array(
                'id' => 457,
                'name' => 'Lokoja',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            457 =>
            array(
                'id' => 458,
                'name' => 'Mopa Muro',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            458 =>
            array(
                'id' => 459,
                'name' => 'Ofu',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            459 =>
            array(
                'id' => 460,
                'name' => 'Ogori/Magongo',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            460 =>
            array(
                'id' => 461,
                'name' => 'Okehi',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            461 =>
            array(
                'id' => 462,
                'name' => 'Okene',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            462 =>
            array(
                'id' => 463,
                'name' => 'Olamaboro',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            463 =>
            array(
                'id' => 464,
                'name' => 'Omala',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            464 =>
            array(
                'id' => 465,
                'name' => 'Yagba East',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            465 =>
            array(
                'id' => 466,
                'name' => 'Yagba West',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            466 =>
            array(
                'id' => 467,
                'name' => 'Baruten',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            467 =>
            array(
                'id' => 468,
                'name' => 'Edu',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            468 =>
            array(
                'id' => 469,
                'name' => 'Ekiti',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            469 =>
            array(
                'id' => 470,
                'name' => 'Ifelodun',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            470 =>
            array(
                'id' => 471,
                'name' => 'Ilorin East',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            471 =>
            array(
                'id' => 472,
                'name' => 'Ilorin South',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            472 =>
            array(
                'id' => 473,
                'name' => 'Ilorin West',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            473 =>
            array(
                'id' => 474,
                'name' => 'Irepodun',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            474 =>
            array(
                'id' => 475,
                'name' => 'Isin',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            475 =>
            array(
                'id' => 476,
                'name' => 'Kaiama',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            476 =>
            array(
                'id' => 477,
                'name' => 'Moro',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            477 =>
            array(
                'id' => 478,
                'name' => 'Offa',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            478 =>
            array(
                'id' => 479,
                'name' => 'Oke Ero',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            479 =>
            array(
                'id' => 480,
                'name' => 'Oyun',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            480 =>
            array(
                'id' => 481,
                'name' => 'Pategi',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            481 =>
            array(
                'id' => 482,
                'name' => 'Ajeromi-Ifelodun',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            482 =>
            array(
                'id' => 483,
                'name' => 'Alimosho',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            483 =>
            array(
                'id' => 484,
                'name' => 'Amuwo-Odofin',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            484 =>
            array(
                'id' => 485,
                'name' => 'Apapa',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            485 =>
            array(
                'id' => 486,
                'name' => 'Badagry',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            486 =>
            array(
                'id' => 487,
                'name' => 'Epe',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            487 =>
            array(
                'id' => 488,
                'name' => 'Eti Osa',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            488 =>
            array(
                'id' => 489,
                'name' => 'Ibeju-Lekki',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            489 =>
            array(
                'id' => 490,
                'name' => 'Ifako-Ijaiye',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            490 =>
            array(
                'id' => 491,
                'name' => 'Ikeja',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            491 =>
            array(
                'id' => 492,
                'name' => 'Ikorodu',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            492 =>
            array(
                'id' => 493,
                'name' => 'Kosofe',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            493 =>
            array(
                'id' => 494,
                'name' => 'Lagos Island',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            494 =>
            array(
                'id' => 495,
                'name' => 'Lagos Mainland',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            495 =>
            array(
                'id' => 496,
                'name' => 'Mushin',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            496 =>
            array(
                'id' => 497,
                'name' => 'Ojo',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            497 =>
            array(
                'id' => 498,
                'name' => 'Oshodi-Isolo',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            498 =>
            array(
                'id' => 499,
                'name' => 'Shomolu',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            499 =>
            array(
                'id' => 500,
                'name' => 'Surulere',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
        \DB::table('lgas')->insert(array(
            0 =>
            array(
                'id' => 501,
                'name' => 'Awe',
                'state_id' => 26,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 =>
            array(
                'id' => 502,
                'name' => 'Doma',
                'state_id' => 26,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 =>
            array(
                'id' => 503,
                'name' => 'Karu',
                'state_id' => 26,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 =>
            array(
                'id' => 504,
                'name' => 'Keana',
                'state_id' => 26,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 =>
            array(
                'id' => 505,
                'name' => 'Keffi',
                'state_id' => 26,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            5 =>
            array(
                'id' => 506,
                'name' => 'Kokona',
                'state_id' => 26,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            6 =>
            array(
                'id' => 507,
                'name' => 'Lafia',
                'state_id' => 26,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            7 =>
            array(
                'id' => 508,
                'name' => 'Nasarawa',
                'state_id' => 26,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            8 =>
            array(
                'id' => 509,
                'name' => 'Nasarawa Egon',
                'state_id' => 26,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            9 =>
            array(
                'id' => 510,
                'name' => 'Obi',
                'state_id' => 26,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            10 =>
            array(
                'id' => 511,
                'name' => 'Toto',
                'state_id' => 26,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            11 =>
            array(
                'id' => 512,
                'name' => 'Wamba',
                'state_id' => 26,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            12 =>
            array(
                'id' => 513,
                'name' => 'Agwara',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            13 =>
            array(
                'id' => 514,
                'name' => 'Bida',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            14 =>
            array(
                'id' => 515,
                'name' => 'Borgu',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            15 =>
            array(
                'id' => 516,
                'name' => 'Bosso',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            16 =>
            array(
                'id' => 517,
                'name' => 'Chanchaga',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            17 =>
            array(
                'id' => 518,
                'name' => 'Edati',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            18 =>
            array(
                'id' => 519,
                'name' => 'Gbako',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            19 =>
            array(
                'id' => 520,
                'name' => 'Gurara',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            20 =>
            array(
                'id' => 521,
                'name' => 'Katcha',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            21 =>
            array(
                'id' => 522,
                'name' => 'Kontagora',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            22 =>
            array(
                'id' => 523,
                'name' => 'Lapai',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            23 =>
            array(
                'id' => 524,
                'name' => 'Lavun',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            24 =>
            array(
                'id' => 525,
                'name' => 'Magama',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            25 =>
            array(
                'id' => 526,
                'name' => 'Mariga',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            26 =>
            array(
                'id' => 527,
                'name' => 'Mashegu',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            27 =>
            array(
                'id' => 528,
                'name' => 'Mokwa',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            28 =>
            array(
                'id' => 529,
                'name' => 'Moya',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            29 =>
            array(
                'id' => 530,
                'name' => 'Paikoro',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            30 =>
            array(
                'id' => 531,
                'name' => 'Rafi',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            31 =>
            array(
                'id' => 532,
                'name' => 'Rijau',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            32 =>
            array(
                'id' => 533,
                'name' => 'Shiroro',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            33 =>
            array(
                'id' => 534,
                'name' => 'Suleja',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            34 =>
            array(
                'id' => 535,
                'name' => 'Tafa',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            35 =>
            array(
                'id' => 536,
                'name' => 'Wushishi',
                'state_id' => 27,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            36 =>
            array(
                'id' => 537,
                'name' => 'Abeokuta South',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            37 =>
            array(
                'id' => 538,
                'name' => 'Ado-Odo/Ota',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            38 =>
            array(
                'id' => 539,
                'name' => 'Egbado North',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            39 =>
            array(
                'id' => 540,
                'name' => 'Egbado South',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            40 =>
            array(
                'id' => 541,
                'name' => 'Ewekoro',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            41 =>
            array(
                'id' => 542,
                'name' => 'Ifo',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            42 =>
            array(
                'id' => 543,
                'name' => 'Ijebu East',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            43 =>
            array(
                'id' => 544,
                'name' => 'Ijebu North',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            44 =>
            array(
                'id' => 545,
                'name' => 'Ijebu North East',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            45 =>
            array(
                'id' => 546,
                'name' => 'Ijebu Ode',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            46 =>
            array(
                'id' => 547,
                'name' => 'Ikenne',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            47 =>
            array(
                'id' => 548,
                'name' => 'Imeko Afon',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            48 =>
            array(
                'id' => 549,
                'name' => 'Ipokia',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            49 =>
            array(
                'id' => 550,
                'name' => 'Obafemi Owode',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            50 =>
            array(
                'id' => 551,
                'name' => 'Odeda',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            51 =>
            array(
                'id' => 552,
                'name' => 'Odogbolu',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            52 =>
            array(
                'id' => 553,
                'name' => 'Ogun Waterside',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            53 =>
            array(
                'id' => 554,
                'name' => 'Remo North',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            54 =>
            array(
                'id' => 555,
                'name' => 'Shagamu',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            55 =>
            array(
                'id' => 556,
                'name' => 'Akoko North-West',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            56 =>
            array(
                'id' => 557,
                'name' => 'Akoko South-West',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            57 =>
            array(
                'id' => 558,
                'name' => 'Akoko South-East',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            58 =>
            array(
                'id' => 559,
                'name' => 'Akure North',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            59 =>
            array(
                'id' => 560,
                'name' => 'Akure South',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            60 =>
            array(
                'id' => 561,
                'name' => 'Ese Odo',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            61 =>
            array(
                'id' => 562,
                'name' => 'Idanre',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            62 =>
            array(
                'id' => 563,
                'name' => 'Ifedore',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            63 =>
            array(
                'id' => 564,
                'name' => 'Ilaje',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            64 =>
            array(
                'id' => 565,
                'name' => 'Ile Oluji/Okeigbo',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            65 =>
            array(
                'id' => 566,
                'name' => 'Irele',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            66 =>
            array(
                'id' => 567,
                'name' => 'Odigbo',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            67 =>
            array(
                'id' => 568,
                'name' => 'Okitipupa',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            68 =>
            array(
                'id' => 569,
                'name' => 'Ondo East',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            69 =>
            array(
                'id' => 570,
                'name' => 'Ondo West',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            70 =>
            array(
                'id' => 571,
                'name' => 'Ose',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            71 =>
            array(
                'id' => 572,
                'name' => 'Owo',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            72 =>
            array(
                'id' => 573,
                'name' => 'Atakunmosa West',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            73 =>
            array(
                'id' => 574,
                'name' => 'Aiyedaade',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            74 =>
            array(
                'id' => 575,
                'name' => 'Aiyedire',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            75 =>
            array(
                'id' => 576,
                'name' => 'Boluwaduro',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            76 =>
            array(
                'id' => 577,
                'name' => 'Boripe',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            77 =>
            array(
                'id' => 578,
                'name' => 'Ede North',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            78 =>
            array(
                'id' => 579,
                'name' => 'Ede South',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            79 =>
            array(
                'id' => 580,
                'name' => 'Ife Central',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            80 =>
            array(
                'id' => 581,
                'name' => 'Ife East',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            81 =>
            array(
                'id' => 582,
                'name' => 'Ife North',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            82 =>
            array(
                'id' => 583,
                'name' => 'Ife South',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            83 =>
            array(
                'id' => 584,
                'name' => 'Egbedore',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            84 =>
            array(
                'id' => 585,
                'name' => 'Ejigbo',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            85 =>
            array(
                'id' => 586,
                'name' => 'Ifedayo',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            86 =>
            array(
                'id' => 587,
                'name' => 'Ifelodun',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            87 =>
            array(
                'id' => 588,
                'name' => 'Ila',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            88 =>
            array(
                'id' => 589,
                'name' => 'Ilesa East',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            89 =>
            array(
                'id' => 590,
                'name' => 'Ilesa West',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            90 =>
            array(
                'id' => 591,
                'name' => 'Irepodun',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            91 =>
            array(
                'id' => 592,
                'name' => 'Irewole',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            92 =>
            array(
                'id' => 593,
                'name' => 'Isokan',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            93 =>
            array(
                'id' => 594,
                'name' => 'Iwo',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            94 =>
            array(
                'id' => 595,
                'name' => 'Obokun',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            95 =>
            array(
                'id' => 596,
                'name' => 'Odo Otin',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            96 =>
            array(
                'id' => 597,
                'name' => 'Ola Oluwa',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            97 =>
            array(
                'id' => 598,
                'name' => 'Olorunda',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            98 =>
            array(
                'id' => 599,
                'name' => 'Oriade',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            99 =>
            array(
                'id' => 600,
                'name' => 'Orolu',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            100 =>
            array(
                'id' => 601,
                'name' => 'Osogbo',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            101 =>
            array(
                'id' => 602,
                'name' => 'Akinyele',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            102 =>
            array(
                'id' => 603,
                'name' => 'Atiba',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            103 =>
            array(
                'id' => 604,
                'name' => 'Atisbo',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            104 =>
            array(
                'id' => 605,
                'name' => 'Egbeda',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            105 =>
            array(
                'id' => 606,
                'name' => 'Ibadan North',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            106 =>
            array(
                'id' => 607,
                'name' => 'Ibadan North-East',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            107 =>
            array(
                'id' => 608,
                'name' => 'Ibadan North-West',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            108 =>
            array(
                'id' => 609,
                'name' => 'Ibadan South-East',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            109 =>
            array(
                'id' => 610,
                'name' => 'Ibadan South-West',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            110 =>
            array(
                'id' => 611,
                'name' => 'Ibarapa Central',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            111 =>
            array(
                'id' => 612,
                'name' => 'Ibarapa East',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            112 =>
            array(
                'id' => 613,
                'name' => 'Ibarapa North',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            113 =>
            array(
                'id' => 614,
                'name' => 'Ido',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            114 =>
            array(
                'id' => 615,
                'name' => 'Irepo',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            115 =>
            array(
                'id' => 616,
                'name' => 'Iseyin',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            116 =>
            array(
                'id' => 617,
                'name' => 'Itesiwaju',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            117 =>
            array(
                'id' => 618,
                'name' => 'Iwajowa',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            118 =>
            array(
                'id' => 619,
                'name' => 'Kajola',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            119 =>
            array(
                'id' => 620,
                'name' => 'Lagelu',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            120 =>
            array(
                'id' => 621,
                'name' => 'Ogbomosho North',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            121 =>
            array(
                'id' => 622,
                'name' => 'Ogbomosho South',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            122 =>
            array(
                'id' => 623,
                'name' => 'Ogo Oluwa',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            123 =>
            array(
                'id' => 624,
                'name' => 'Olorunsogo',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            124 =>
            array(
                'id' => 625,
                'name' => 'Oluyole',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            125 =>
            array(
                'id' => 626,
                'name' => 'Ona Ara',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            126 =>
            array(
                'id' => 627,
                'name' => 'Orelope',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            127 =>
            array(
                'id' => 628,
                'name' => 'Ori Ire',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            128 =>
            array(
                'id' => 629,
                'name' => 'Oyo',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            129 =>
            array(
                'id' => 630,
                'name' => 'Oyo East',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            130 =>
            array(
                'id' => 631,
                'name' => 'Saki East',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            131 =>
            array(
                'id' => 632,
                'name' => 'Saki West',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            132 =>
            array(
                'id' => 633,
                'name' => 'Surulere',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            133 =>
            array(
                'id' => 634,
                'name' => 'Barkin Ladi',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            134 =>
            array(
                'id' => 635,
                'name' => 'Bassa',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            135 =>
            array(
                'id' => 636,
                'name' => 'Jos East',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            136 =>
            array(
                'id' => 637,
                'name' => 'Jos North',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            137 =>
            array(
                'id' => 638,
                'name' => 'Jos South',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            138 =>
            array(
                'id' => 639,
                'name' => 'Kanam',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            139 =>
            array(
                'id' => 640,
                'name' => 'Kanke',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            140 =>
            array(
                'id' => 641,
                'name' => 'Langtang South',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            141 =>
            array(
                'id' => 642,
                'name' => 'Langtang North',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            142 =>
            array(
                'id' => 643,
                'name' => 'Mangu',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            143 =>
            array(
                'id' => 644,
                'name' => 'Mikang',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            144 =>
            array(
                'id' => 645,
                'name' => 'Pankshin',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            145 =>
            array(
                'id' => 646,
                'name' => 'Quaan Pan',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            146 =>
            array(
                'id' => 647,
                'name' => 'Riyom',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            147 =>
            array(
                'id' => 648,
                'name' => 'Shendam',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            148 =>
            array(
                'id' => 649,
                'name' => 'Wase',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            149 =>
            array(
                'id' => 650,
                'name' => 'Ahoada East',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            150 =>
            array(
                'id' => 651,
                'name' => 'Ahoada West',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            151 =>
            array(
                'id' => 652,
                'name' => 'Akuku-Toru',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            152 =>
            array(
                'id' => 653,
                'name' => 'Andoni',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            153 =>
            array(
                'id' => 654,
                'name' => 'Asari-Toru',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            154 =>
            array(
                'id' => 655,
                'name' => 'Bonny',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            155 =>
            array(
                'id' => 656,
                'name' => 'Degema',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            156 =>
            array(
                'id' => 657,
                'name' => 'Eleme',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            157 =>
            array(
                'id' => 658,
                'name' => 'Emuoha',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            158 =>
            array(
                'id' => 659,
                'name' => 'Etche',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            159 =>
            array(
                'id' => 660,
                'name' => 'Gokana',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            160 =>
            array(
                'id' => 661,
                'name' => 'Ikwerre',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            161 =>
            array(
                'id' => 662,
                'name' => 'Khana',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            162 =>
            array(
                'id' => 663,
                'name' => 'Obio/Akpor',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            163 =>
            array(
                'id' => 664,
                'name' => 'Ogba/Egbema/Ndoni',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            164 =>
            array(
                'id' => 665,
                'name' => 'Ogu/Bolo',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            165 =>
            array(
                'id' => 666,
                'name' => 'Okrika',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            166 =>
            array(
                'id' => 667,
                'name' => 'Omuma',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            167 =>
            array(
                'id' => 668,
                'name' => 'Opobo/Nkoro',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            168 =>
            array(
                'id' => 669,
                'name' => 'Oyigbo',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            169 =>
            array(
                'id' => 670,
                'name' => 'Port Harcourt',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            170 =>
            array(
                'id' => 671,
                'name' => 'Tai',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            171 =>
            array(
                'id' => 672,
                'name' => 'Bodinga',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            172 =>
            array(
                'id' => 673,
                'name' => 'Dange Shuni',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            173 =>
            array(
                'id' => 674,
                'name' => 'Gada',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            174 =>
            array(
                'id' => 675,
                'name' => 'Goronyo',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            175 =>
            array(
                'id' => 676,
                'name' => 'Gudu',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            176 =>
            array(
                'id' => 677,
                'name' => 'Gwadabawa',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            177 =>
            array(
                'id' => 678,
                'name' => 'Illela',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            178 =>
            array(
                'id' => 679,
                'name' => 'Isa',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            179 =>
            array(
                'id' => 680,
                'name' => 'Kebbe',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            180 =>
            array(
                'id' => 681,
                'name' => 'Kware',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            181 =>
            array(
                'id' => 682,
                'name' => 'Rabah',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            182 =>
            array(
                'id' => 683,
                'name' => 'Sabon Birni',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            183 =>
            array(
                'id' => 684,
                'name' => 'Shagari',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            184 =>
            array(
                'id' => 685,
                'name' => 'Silame',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            185 =>
            array(
                'id' => 686,
                'name' => 'Sokoto North',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            186 =>
            array(
                'id' => 687,
                'name' => 'Sokoto South',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            187 =>
            array(
                'id' => 688,
                'name' => 'Tambuwal',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            188 =>
            array(
                'id' => 689,
                'name' => 'Tangaza',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            189 =>
            array(
                'id' => 690,
                'name' => 'Tureta',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            190 =>
            array(
                'id' => 691,
                'name' => 'Wamako',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            191 =>
            array(
                'id' => 692,
                'name' => 'Wurno',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            192 =>
            array(
                'id' => 693,
                'name' => 'Yabo',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            193 =>
            array(
                'id' => 694,
                'name' => 'Bali',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            194 =>
            array(
                'id' => 695,
                'name' => 'Donga',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            195 =>
            array(
                'id' => 696,
                'name' => 'Gashaka',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            196 =>
            array(
                'id' => 697,
                'name' => 'Gassol',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            197 =>
            array(
                'id' => 698,
                'name' => 'Ibi',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            198 =>
            array(
                'id' => 699,
                'name' => 'Jalingo',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            199 =>
            array(
                'id' => 700,
                'name' => 'Karim Lamido',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            200 =>
            array(
                'id' => 701,
                'name' => 'Kumi',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            201 =>
            array(
                'id' => 702,
                'name' => 'Lau',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            202 =>
            array(
                'id' => 703,
                'name' => 'Sardauna',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            203 =>
            array(
                'id' => 704,
                'name' => 'Takum',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            204 =>
            array(
                'id' => 705,
                'name' => 'Ussa',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            205 =>
            array(
                'id' => 706,
                'name' => 'Wukari',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            206 =>
            array(
                'id' => 707,
                'name' => 'Yorro',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            207 =>
            array(
                'id' => 708,
                'name' => 'Zing',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            208 =>
            array(
                'id' => 709,
                'name' => 'Bursari',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            209 =>
            array(
                'id' => 710,
                'name' => 'Damaturu',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            210 =>
            array(
                'id' => 711,
                'name' => 'Fika',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            211 =>
            array(
                'id' => 712,
                'name' => 'Fune',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            212 =>
            array(
                'id' => 713,
                'name' => 'Geidam',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            213 =>
            array(
                'id' => 714,
                'name' => 'Gujba',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            214 =>
            array(
                'id' => 715,
                'name' => 'Gulani',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            215 =>
            array(
                'id' => 716,
                'name' => 'Jakusko',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            216 =>
            array(
                'id' => 717,
                'name' => 'Karasuwa',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            217 =>
            array(
                'id' => 718,
                'name' => 'Machina',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            218 =>
            array(
                'id' => 719,
                'name' => 'Nangere',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            219 =>
            array(
                'id' => 720,
                'name' => 'Nguru',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            220 =>
            array(
                'id' => 721,
                'name' => 'Potiskum',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            221 =>
            array(
                'id' => 722,
                'name' => 'Tarmuwa',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            222 =>
            array(
                'id' => 723,
                'name' => 'Yunusari',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            223 =>
            array(
                'id' => 724,
                'name' => 'Yusufari',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            224 =>
            array(
                'id' => 725,
                'name' => 'Bakura',
                'state_id' => 37,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            225 =>
            array(
                'id' => 726,
                'name' => 'Birnin Magaji/Kiyaw',
                'state_id' => 37,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            226 =>
            array(
                'id' => 727,
                'name' => 'Bukkuyum',
                'state_id' => 37,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            227 =>
            array(
                'id' => 728,
                'name' => 'Bungudu',
                'state_id' => 37,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            228 =>
            array(
                'id' => 729,
                'name' => 'Gummi',
                'state_id' => 37,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            229 =>
            array(
                'id' => 730,
                'name' => 'Gusau',
                'state_id' => 37,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            230 =>
            array(
                'id' => 731,
                'name' => 'Kaura Namoda',
                'state_id' => 37,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            231 =>
            array(
                'id' => 732,
                'name' => 'Maradun',
                'state_id' => 37,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            232 =>
            array(
                'id' => 733,
                'name' => 'Maru',
                'state_id' => 37,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            233 =>
            array(
                'id' => 734,
                'name' => 'Shinkafi',
                'state_id' => 37,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            234 =>
            array(
                'id' => 735,
                'name' => 'Talata Mafara',
                'state_id' => 37,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            235 =>
            array(
                'id' => 736,
                'name' => 'Chafe',
                'state_id' => 37,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            236 =>
            array(
                'id' => 737,
                'name' => 'Zurmi',
                'state_id' => 37,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            237 =>
            array(
                'id' => 738,
                'name' => 'Aba North',
                'state_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            238 =>
            array(
                'id' => 739,
                'name' => 'Demsa',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            238 =>
            array(
                'id' => 739,
                'name' => 'Demsa',
                'state_id' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            239 =>
            array(
                'id' => 740,
                'name' => 'Abak',
                'state_id' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            240 =>
            array(
                'id' => 741,
                'name' => 'Alkaleri',
                'state_id' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            241 =>
            array(
                'id' => 742,
                'name' => 'Aguata',
                'state_id' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            242 =>
            array(
                'id' => 743,
                'name' => 'Brass',
                'state_id' => 6,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            243 =>
            array(
                'id' => 744,
                'name' => 'Agatu',
                'state_id' => 7,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            244 =>
            array(
                'id' => 745,
                'name' => 'Abadam',
                'state_id' => 8,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            245 =>
            array(
                'id' => 746,
                'name' => 'Abi',
                'state_id' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            246 =>
            array(
                'id' => 747,
                'name' => 'Aniocha',
                'state_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            247 =>
            array(
                'id' => 748,
                'name' => 'Abakaliki',
                'state_id' => 11,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            248 =>
            array(
                'id' => 749,
                'name' => 'Akoko-Edo',
                'state_id' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            249 =>
            array(
                'id' => 750,
                'name' => 'Ado-Ekiti',
                'state_id' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            250 =>
            array(
                'id' => 751,
                'name' => 'Aninri',
                'state_id' => 14,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            251 =>
            array(
                'id' => 752,
                'name' => 'Abaji',
                'state_id' => 15,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            252 =>
            array(
                'id' => 753,
                'name' => 'Akko',
                'state_id' => 16,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            253 =>
            array(
                'id' => 754,
                'name' => 'Aboh Mbaise',
                'state_id' => 17,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            254 =>
            array(
                'id' => 755,
                'name' => 'Auyo',
                'state_id' => 18,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            255 =>
            array(
                'id' => 756,
                'name' => 'Ajingi',
                'state_id' => 20,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            256 =>
            array(
                'id' => 757,
                'name' => 'Bakori',
                'state_id' => 21,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            257 =>
            array(
                'id' => 758,
                'name' => 'Aleiro',
                'state_id' => 22,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            258 =>
            array(
                'id' => 759,
                'name' => 'Adavi',
                'state_id' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            259 =>
            array(
                'id' => 760,
                'name' => 'Asa',
                'state_id' => 24,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            260 =>
            array(
                'id' => 761,
                'name' => 'Agege',
                'state_id' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            261 =>
            array(
                'id' => 762,
                'name' => 'Akwanga',
                'state_id' => 26,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            262 =>
            array(
                'id' => 763,
                'name' => 'Abeokuta North',
                'state_id' => 28,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            263 =>
            array(
                'id' => 764,
                'name' => 'Akoko North-East',
                'state_id' => 29,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            264 =>
            array(
                'id' => 765,
                'name' => 'Atakunmosa East',
                'state_id' => 30,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            265 =>
            array(
                'id' => 766,
                'name' => 'Afijio',
                'state_id' => 31,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            266 =>
            array(
                'id' => 767,
                'name' => 'Bokkos',
                'state_id' => 32,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            267 =>
            array(
                'id' => 768,
                'name' => 'Abua/Odual',
                'state_id' => 33,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            268 =>
            array(
                'id' => 769,
                'name' => 'Binji',
                'state_id' => 34,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            269 =>
            array(
                'id' => 770,
                'name' => 'Ardo Kola',
                'state_id' => 35,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            270 =>
            array(
                'id' => 771,
                'name' => 'Anka',
                'state_id' => 37,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            271 =>
            array(
                'id' => 772,
                'name' => 'Bade',
                'state_id' => 36,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            272 =>
            array(
                'id' => 773,
                'name' => 'Buliisa',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            273 =>
            array(
                'id' => 774,
                'name' => 'Buikwe',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            274 =>
            array(
                'id' => 775,
                'name' => 'Bukomansimbi',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            275 =>
            array(
                'id' => 776,
                'name' => 'Buvuma',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            276 =>
            array(
                'id' => 777,
                'name' => 'Gomba',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            277 =>
            array(
                'id' => 778,
                'name' => 'Kalangala',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            278 =>
            array(
                'id' => 779,
                'name' => 'Kalungu',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            279 =>
            array(
                'id' => 780,
                'name' => 'Kampala',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            280 =>
            array(
                'id' => 781,
                'name' => 'Kasanda',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            281 =>
            array(
                'id' => 782,
                'name' => 'Kayunga',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            282 =>
            array(
                'id' => 783,
                'name' => 'Kiboga',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            283 =>
            array(
                'id' => 786,
                'name' => 'Kyankwanzi',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            284 =>
            array(
                'id' => 787,
                'name' => 'Kyotera',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            285 =>
            array(
                'id' => 788,
                'name' => 'Luweero',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            286 =>
            array(
                'id' => 789,
                'name' => 'Lwengo',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            287 =>
            array(
                'id' => 790,
                'name' => 'Lyantonde',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            288 =>
            array(
                'id' => 791,
                'name' => 'Masaka',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            289 =>
            array(
                'id' => 792,
                'name' => 'Mityana',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            289 =>
            array(
                'id' => 793,
                'name' => 'Mpigi',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            290 =>
            array(
                'id' => 794,
                'name' => 'Mubende',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            291 =>
            array(
                'id' => 795,
                'name' => 'Mukono',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            292 =>
            array(
                'id' => 796,
                'name' => 'Nakaseke',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            293 =>
            array(
                'id' => 797,
                'name' => 'Nakasongola',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            294 =>
            array(
                'id' => 798,
                'name' => 'Rakai',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            295 =>
            array(
                'id' => 799,
                'name' => 'Sembabule',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            296 =>
            array(
                'id' => 800,
                'name' => 'Wakiso',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            297 =>
            array(
                'id' => 801,
                'name' => 'Butambala',
                'state_id' => 4038,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            298 =>
            array(
                'id' => 802,
                'name' => 'Amuria',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            299 =>
            array(
                'id' => 803,
                'name' => 'Budaka',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            300 =>
            array(
                'id' => 804,
                'name' => 'Bududa',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            301 =>
            array(
                'id' => 805,
                'name' => 'Bugiri',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            302 =>
            array(
                'id' => 806,
                'name' => 'Bugweri',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            303 =>
            array(
                'id' => 807,
                'name' => 'Bukedea',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            304 =>
            array(
                'id' => 808,
                'name' => 'Bukwo',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            305 =>
            array(
                'id' => 809,
                'name' => 'Bulambuli',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            306 =>
            array(
                'id' => 810,
                'name' => 'Busia',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            307 =>
            array(
                'id' => 811,
                'name' => 'Butaleja',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            308 =>
            array(
                'id' => 812,
                'name' => 'Butebo',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            309 =>
            array(
                'id' => 813,
                'name' => 'Buyende',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            310 =>
            array(
                'id' => 814,
                'name' => 'Iganga',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            311 =>
            array(
                'id' => 815,
                'name' => 'Jinja',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            312 =>
            array(
                'id' => 816,
                'name' => 'Kaberamaido',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            313 =>
            array(
                'id' => 817,
                'name' => 'Kaliro',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            314 =>
            array(
                'id' => 818,
                'name' => 'Kamuli',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            315 =>
            array(
                'id' => 819,
                'name' => 'Kapchorwa',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            316 =>
            array(
                'id' => 820,
                'name' => 'Kapelebyong',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            317 =>
            array(
                'id' => 821,
                'name' => 'Katakwi',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            318 =>
            array(
                'id' => 822,
                'name' => 'Kibuku',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            319 =>
            array(
                'id' => 823,
                'name' => 'Kumi',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            320 =>
            array(
                'id' => 824,
                'name' => 'Kween',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            321 =>
            array(
                'id' => 825,
                'name' => 'Luuka',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            322 =>
            array(
                'id' => 826,
                'name' => 'Manafwa',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            323 =>
            array(
                'id' => 827,
                'name' => 'Mayuge',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            324 =>
            array(
                'id' => 828,
                'name' => 'Mbale',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            325 =>
            array(
                'id' => 829,
                'name' => 'Namayingo',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            326 =>
            array(
                'id' => 830,
                'name' => 'Namisindwa',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            327 =>
            array(
                'id' => 831,
                'name' => 'Namutumba',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            328 =>
            array(
                'id' => 832,
                'name' => 'Ngora',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            329 =>
            array(
                'id' => 833,
                'name' => 'Pallisa',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            330 =>
            array(
                'id' => 834,
                'name' => 'Serere',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            331 =>
            array(
                'id' => 835,
                'name' => 'Sironko',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            332 =>
            array(
                'id' => 836,
                'name' => 'Soroti',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            333 =>
            array(
                'id' => 837,
                'name' => 'Tororo',
                'state_id' => 4037,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            334 =>
            array(
                'id' => 838,
                'name' => 'Abim',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            335 =>
            array(
                'id' => 839,
                'name' => 'Adjumani',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            336 =>
            array(
                'id' => 840,
                'name' => 'Agago',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            337 =>
            array(
                'id' => 841,
                'name' => 'Alebtong',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            338 =>
            array(
                'id' => 842,
                'name' => 'Amolatar',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            339 =>
            array(
                'id' => 843,
                'name' => 'Amudat',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            340 =>
            array(
                'id' => 844,
                'name' => 'Amuru',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            341 =>
            array(
                'id' => 845,
                'name' => 'Apac',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            342 =>
            array(
                'id' => 846,
                'name' => 'Arua',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            343 =>
            array(
                'id' => 847,
                'name' => 'Dokolo',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            344 =>
            array(
                'id' => 848,
                'name' => 'Gulu',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            345 =>
            array(
                'id' => 849,
                'name' => 'Kaabong',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            346 =>
            array(
                'id' => 850,
                'name' => 'Karenga',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            347 =>
            array(
                'id' => 851,
                'name' => 'Kitgum',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            348 =>
            array(
                'id' => 852,
                'name' => 'Koboko',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            349 =>
            array(
                'id' => 853,
                'name' => 'Kole',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            350 =>
            array(
                'id' => 854,
                'name' => 'Kotido',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            351 =>
            array(
                'id' => 855,
                'name' => 'Kwania',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            352 =>
            array(
                'id' => 856,
                'name' => 'Lamwo',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            353 =>
            array(
                'id' => 857,
                'name' => 'Lira',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            354 =>
            array(
                'id' => 858,
                'name' => 'Madi-Okollo',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            355 =>
            array(
                'id' => 859,
                'name' => 'Maracha',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            356 =>
            array(
                'id' => 860,
                'name' => 'Moroto',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            357 =>
            array(
                'id' => 861,
                'name' => 'Moyo',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            358 =>
            array(
                'id' => 862,
                'name' => 'Nabilatuk',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            359 =>
            array(
                'id' => 863,
                'name' => 'Nakapiripirit',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            360 =>
            array(
                'id' => 864,
                'name' => 'Napak',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            361 =>
            array(
                'id' => 865,
                'name' => 'Nebbi',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            362 =>
            array(
                'id' => 866,
                'name' => 'Nwoya',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            363 =>
            array(
                'id' => 867,
                'name' => 'Obongi',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            364 =>
            array(
                'id' => 868,
                'name' => 'Omoro',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            365 =>
            array(
                'id' => 869,
                'name' => 'Otuke',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            366 =>
            array(
                'id' => 870,
                'name' => 'Oyam',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            367 =>
            array(
                'id' => 871,
                'name' => 'Pader',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            368 =>
            array(
                'id' => 872,
                'name' => 'Pakwach',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            369 =>
            array(
                'id' => 873,
                'name' => 'Terego',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            370 =>
            array(
                'id' => 874,
                'name' => 'Yumbe',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            371 =>
            array(
                'id' => 875,
                'name' => 'Zombo',
                'state_id' => 4039,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            372 =>
            array(
                'id' => 876,
                'name' => 'Buhweju',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            373 =>
            array(
                'id' => 877,
                'name' => 'Buliisa',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            374 =>
            array(
                'id' => 878,
                'name' => 'Bundibugyo',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            375 =>
            array(
                'id' => 879,
                'name' => 'Bunyangabu',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            376 =>
            array(
                'id' => 880,
                'name' => 'Bushenyi',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            377 =>
            array(
                'id' => 881,
                'name' => 'Hoima',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            378 =>
            array(
                'id' => 882,
                'name' => 'Ibanda',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            379 =>
            array(
                'id' => 883,
                'name' => 'Isingiro',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            380 =>
            array(
                'id' => 884,
                'name' => 'Kabale',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            381 =>
            array(
                'id' => 885,
                'name' => 'Kabarole',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            382 =>
            array(
                'id' => 886,
                'name' => 'Kagadi',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            383 =>
            array(
                'id' => 887,
                'name' => 'Kakumiro',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            384 =>
            array(
                'id' => 888,
                'name' => 'Sheema',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            385 =>
            array(
                'id' => 889,
                'name' => 'Rwampara',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            386 =>
            array(
                'id' => 890,
                'name' => 'Rukungiri',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            387 =>
            array(
                'id' => 891,
                'name' => 'Rukiga',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            388 =>
            array(
                'id' => 892,
                'name' => 'Rubirizi',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            389 =>
            array(
                'id' => 893,
                'name' => 'Rubanda',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            390 =>
            array(
                'id' => 894,
                'name' => 'Ntungamo',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            391 =>
            array(
                'id' => 895,
                'name' => 'Ntoroko',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            392 => array(
                'id' => 896,
                'name' => 'Mitooma',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            393 =>
            array(
                'id' => 897,
                'name' => 'Mbarara',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            394 =>
            array(
                'id' => 898,
                'name' => 'Masindi',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            395 =>
            array(
                'id' => 899,
                'name' => 'Kyenjojo',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            396 =>
            array(
                'id' => 900,
                'name' => 'Kyegegwa',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            397 =>
            array(
                'id' => 901,
                'name' => 'Kitagwenda',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            398 =>
            array(
                'id' => 902,
                'name' => 'Kisoro',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            399 =>
            array(
                'id' => 903,
                'name' => 'Kiryandongo',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            400 =>
            array(
                'id' => 904,
                'name' => 'Kiruhura',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            401 =>
            array(
                'id' => 905,
                'name' => 'Kikuube',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            402 =>
            array(
                'id' => 906,
                'name' => 'Kibaale',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            403 =>
            array(
                'id' => 907,
                'name' => 'Kazo',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            404 =>
            array(
                'id' => 908,
                'name' => 'Kasese',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            405 =>
            array(
                'id' => 909,
                'name' => 'Kanungu',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            406 =>
            array(
                'id' => 910,
                'name' => 'Kamwenge',
                'state_id' => 4040,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            407 =>
            array(
                'id' => 911,
                'name' => 'Arusha City',
                'state_id' => 4041,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            408 => array(
                'id' => 912,
                'name' => 'Arusha Rural District',
                'state_id' => 4041,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            409 => array(
                'id' => 913,
                'name' => 'Karatu District',
                'state_id' => 4041,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            410 => array(
                'id' => 914,
                'name' => 'Longido District',
                'state_id' => 4041,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                ),
            411 => array(
                'id' => 915,
                'name' => 'Meru District',
                'state_id' => 4041,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            412 => array(
                'id' => 916,
                'name' => 'Monduli District',
                'state_id' => 4041,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            413 => array(
                'id' => 917,
                'name' => 'Ngorongoro District',
                'state_id' => 4041,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            414 => array(
                'id' => 918,
                'name' => 'Ilala District',
                'state_id' => 4042,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            415 => array(
                'id' => 919,
                'name' => 'Kinondoni District',
                'state_id' => 4042,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            416 => array(
                'id' => 920,
                'name' => 'Temeke District',
                'state_id' => 4042,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            417 => array(
                'id' => 921,
                'name' => 'Kigamboni District',
                'state_id' => 4042,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            418 => array(
                'id' => 922,
                'name' => 'Ubungo District',
                'state_id' => 4042,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            420 => array(
                'id' => 923,
                'name' => 'Chamwino District',
                'state_id' => 4043,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            421 => array(
                'id' => 924,
                'name' => 'Chemba District',
                'state_id' => 4043,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            422 => array(
                'id' => 925,
                'name' => 'Dodoma Municipal',
                'state_id' => 4043,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            423 => array(
                'id' => 926,
                'name' => 'Kondoa District',
                'state_id' => 4043,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            424 => array(
                'id' => 927,
                'name' => 'Kongwa District',
                'state_id' => 4043,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            425 => array(
                'id' => 928,
                'name' => 'Mpwapwa District',
                'state_id' => 4043,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            426 => array(
                'id' => 929,
                'name' => 'Bukombe District',
                'state_id' => 4044,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            427 => array(
                'id' => 930,
                'name' => 'Chato District',
                'state_id' => 4044,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            428 => array(
                'id' => 931,
                'name' => 'Geita District',
                'state_id' => 4044,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            429 => array(
                'id' => 932,
                'name' => 'Geita Town',
                'state_id' => 4044,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            430 => array(
                'id' => 933,
                'name' => 'Mbogwe District',
                'state_id' => 4044,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            431 => array(
                'id' => 934,
                'name' => 'Nyang\'hwale District',
                'state_id' => 4044,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            432 => array(
                'id' => 935,
                'name' => 'Iringa District',
                'state_id' => 4045,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            433 => array(
                'id' => 936,
                'name' => 'Kilolo District',
                'state_id' => 4045,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            434 => array(
                'id' => 937,
                'name' => 'Mufindi District',
                'state_id' => 4045,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            435 => array(
                'id' => 938,
                'name' => 'Iringa Municipal',
                'state_id' => 4045,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            436 => array(
                'id' => 939,
                'name' => 'Mafinga Town',
                'state_id' => 4045,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            437 => array(
                'id' => 940,
                'name' => 'Biharamulo District',
                'state_id' => 4046,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            438 => array(
                'id' => 941,
                'name' => 'Bukoba Rural',
                'state_id' => 4046,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            439 => array(
                'id' => 942,
                'name' => 'Bukoba Urban',
                'state_id' => 4046,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            440 => array(
                'id' => 943,
                'name' => 'Karagwe District',
                'state_id' => 4046,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            441 => array(
                'id' => 944,
                'name' => 'Kyerwa District',
                'state_id' => 4046,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            442 => array(
                'id' => 945,
                'name' => 'Missenyi District',
                'state_id' => 4046,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            443 => array(
                'id' => 946,
                'name' => 'Muleba District',
                'state_id' => 4046,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            444 => array(
                'id' => 947,
                'name' => 'Ngara District',
                'state_id' => 4046,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            445 => array(
                'id' => 948,
                'name' => 'Nsimbo District',
                'state_id' => 4047,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            446 => array(
                'id' => 949,
                'name' => 'Mpanda District',
                'state_id' => 4047,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            447 => array(
                'id' => 950,
                'name' => 'Mlele District',
                'state_id' => 4047,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            448 => array(
                'id' => 951,
                'name' => 'Buhigwe District',
                'state_id' => 4048,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            449 => array(
                'id' => 952,
                'name' => 'Bukoba Rural',
                'state_id' => 4048,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            450 => array(
                'id' => 953,
                'name' => 'Bukoba Urban',
                'state_id' => 4048,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            451 => array(
                'id' => 954,
                'name' => 'Karagwe District',
                'state_id' => 4048,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            452 => array(
                'id' => 955,
                'name' => 'Kyerwa District',
                'state_id' => 4048,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            453 => array(
                'id' => 956,
                'name' => 'Missenyi District',
                'state_id' => 4048,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            454 => array(
                'id' => 957,
                'name' => 'Muleba District',
                'state_id' => 4048,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            455 => array(
                'id' => 958,
                'name' => 'Ngara District',
                'state_id' => 4048,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            456 => array(
                'id' => 959,
                'name' => 'Moshi District',
                'state_id' => 4049,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            457 => array(
                'id' => 960,
                'name' => 'Moshi Municipal',
                'state_id' => 4049,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            458 => array(
                'id' => 961,
                'name' => 'Hai District',
                'state_id' => 4049,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            459 => array(
                'id' => 962,
                'name' => 'Siha District',
                'state_id' => 4049,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            460 => array(
                'id' => 963,
                'name' => 'Rombo District',
                'state_id' => 4049,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            461 => array(
                'id' => 964,
                'name' => 'Mwanga District',
                'state_id' => 4049,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            462 => array(
                'id' => 965,
                'name' => 'Same District',
                'state_id' => 4049,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            463 => array(
                'id' => 966,
                'name' => 'Kilwa District',
                'state_id' => 4050,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            464 => array(
                'id' => 967,
                'name' => 'Lindi District',
                'state_id' => 4050,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            465 => array(
                'id' => 968,
                'name' => 'Liwale District',
                'state_id' => 4050,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            466 => array(
                'id' => 969,
                'name' => 'Nachingwea District',
                'state_id' => 4050,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            467 => array(
                'id' => 970,
                'name' => 'Ruangwa District',
                'state_id' => 4050,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            468 => array(
                'id' => 971,
                'name' => 'Babati District',
                'state_id' => 4051,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            469 => array(
                'id' => 972,
                'name' => 'Babati Town',
                'state_id' => 4051,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            470 => array(
                'id' => 973,
                'name' => 'Hanang District',
                'state_id' => 4051,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            471 => array(
                'id' => 974,
                'name' => 'Kiteto District',
                'state_id' => 4051,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            472 => array(
                'id' => 975,
                'name' => 'Mbulu District',
                'state_id' => 4051,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            473 => array(
                'id' => 976,
                'name' => 'Simanjiro District',
                'state_id' => 4051,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            474 => array(
                'id' => 977,
                'name' => 'Bunda District',
                'state_id' => 4052,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            475 => array(
                'id' => 978,
                'name' => 'Butiama District',
                'state_id' => 4052,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            476 => array(
                'id' => 979,
                'name' => 'Musoma District',
                'state_id' => 4052,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            477 => array(
                'id' => 980,
                'name' => 'Musoma Municipal',
                'state_id' => 4052,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            478 => array(
                'id' => 981,
                'name' => 'Rorya District',
                'state_id' => 4052,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            479 => array(
                'id' => 982,
                'name' => 'Serengeti District',
                'state_id' => 4052,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            480 => array(
                'id' => 983,
                'name' => 'Tarime District',
                'state_id' => 4052,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            481 => array(
                'id' => 984,
                'name' => 'Tarime District',
                'state_id' => 4052,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            482 => array(
                'id' => 985,
                'name' => 'Chunya District',
                'state_id' => 4053,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            483 => array(
                'id' => 986,
                'name' => 'Ileje District',
                'state_id' => 4053,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            484 => array(
                'id' => 987,
                'name' => 'Kyela District',
                'state_id' => 4053,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            485 => array(
                'id' => 988,
                'name' => 'Mbarali District',
                'state_id' => 4053,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            486 => array(
                'id' => 989,
                'name' => 'Mbeya District',
                'state_id' => 4053,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            487 => array(
                'id' => 990,
                'name' => 'Mbozi District',
                'state_id' => 4053,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            488 => array(
                'id' => 991,
                'name' => 'Momba District',
                'state_id' => 4053,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            489 => array(
                'id' => 992,
                'name' => 'Rungwe District',
                'state_id' => 4053,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            490 => array(
                'id' => 993,
                'name' => 'Magharibi',
                'state_id' => 4054,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            491 => array(
                'id' => 994,
                'name' => 'Mjini',
                'state_id' => 4054,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            492 => array(
                'id' => 995,
                'name' => 'Gairo District',
                'state_id' => 4055,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            493 => array(
                'id' => 996,
                'name' => 'Ifakara Township',
                'state_id' => 4055,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            494 => array(
                'id' => 997,
                'name' => 'Kilombero District',
                'state_id' => 4055,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            495 => array(
                'id' => 998,
                'name' => 'Kilosa District',
                'state_id' => 4055,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            496 => array(
                'id' => 999,
                'name' => 'Malinyi District',
                'state_id' => 4055,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            497 => array(
                'id' => 1000,
                'name' => 'Morogoro District',
                'state_id' => 4055,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            498 => array(
                'id' => 1001,
                'name' => 'Morogoro Municipal',
                'state_id' => 4055,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            499 => array(
                'id' => 1002,
                'name' => 'Mvomero District',
                'state_id' => 4055,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            500 => array(
                'id' => 1003,
                'name' => 'Ulanga District',
                'state_id' => 4055,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            501 => array(
                'id' => 1004,
                'name' => 'Masasi District',
                'state_id' => 4056,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            502 => array(
                'id' => 1005,
                'name' => 'Masasi Town',
                'state_id' => 4056,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            503 => array(
                'id' => 1006,
                'name' => 'Mtwara District',
                'state_id' => 4056,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            504 => array(
                'id' => 1007,
                'name' => 'Mtwara Municipal',
                'state_id' => 4056,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            505 => array(
                'id' => 1008,
                'name' => 'Nanyumbu District',
                'state_id' => 4056,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            506 => array(
                'id' => 1009,
                'name' => 'Newala District',
                'state_id' => 4056,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            507 => array(
                'id' => 1010,
                'name' => 'Tandahimba District',
                'state_id' => 4056,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            508 => array(
                'id' => 1011,
                'name' => 'Ilemela District',
                'state_id' => 4057,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            509 => array(
                'id' => 1012,
                'name' => 'Kwimba District',
                'state_id' => 4057,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            510 => array(
                'id' => 1013,
                'name' => 'Magu District',
                'state_id' => 4057,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            511 => array(
                'id' => 1014,
                'name' => 'Misungwi District',
                'state_id' => 4057,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            512 => array(
                'id' => 1015,
                'name' => 'Nyamagana District',
                'state_id' => 4057,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            513 => array(
                'id' => 1016,
                'name' => 'Sengerema District',
                'state_id' => 4057,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            514 => array(
                'id' => 1017,
                'name' => 'Ukerewe District',
                'state_id' => 4057,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            515 => array(
                'id' => 1018,
                'name' => 'Ludewa District',
                'state_id' => 4058,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            516 => array(
                'id' => 1019,
                'name' => 'Makambako Town',
                'state_id' => 4058,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            517 => array(
                'id' => 1020,
                'name' => 'Makete District',
                'state_id' => 4058,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            518 => array(
                'id' => 1021,
                'name' => 'Njombe District',
                'state_id' => 4058,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            519 => array(
                'id' => 1022,
                'name' => 'Njombe Town',
                'state_id' => 4058,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            520 => array(
                'id' => 1023,
                'name' => 'Wanging\'ombe District',
                'state_id' => 4058,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            521 => array(
                'id' => 1024,
                'name' => 'Micheweni',
                'state_id' => 4059,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            522 => array(
                'id' => 1025,
                'name' => 'Wete',
                'state_id' => 4059,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            523 => array(
                'id' => 1026,
                'name' => 'Chake Chake',
                'state_id' => 4060,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            524 => array(
                'id' => 1027,
                'name' => 'Mkoani',
                'state_id' => 4060,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            525 => array(
                'id' => 1028,
                'name' => 'Bagamoyo District',
                'state_id' => 4061,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            526 => array(
                'id' => 1029,
                'name' => 'Kibaha District',
                'state_id' => 4061,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            527 => array(
                'id' => 1030,
                'name' => 'Kibaha Town',
                'state_id' => 4061,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            528 => array(
                'id' => 1031,
                'name' => 'Kisarawe District',
                'state_id' => 4061,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            529 => array(
                'id' => 1032,
                'name' => 'Mafia District',
                'state_id' => 4061,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            530 => array(
                'id' => 1033,
                'name' => 'Mkuranga District',
                'state_id' => 4061,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            531 => array(
                'id' => 1034,
                'name' => 'Rufiji District',
                'state_id' => 4061,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            532 => array(
                'id' => 1035,
                'name' => 'Kalambo District',
                'state_id' => 4062,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            533 => array(
                'id' => 1036,
                'name' => 'Lyamba lya Mfipa District',
                'state_id' => 4062,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            534 => array(
                'id' => 1037,
                'name' => 'Nkasi District',
                'state_id' => 4062,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            535 => array(
                'id' => 1038,
                'name' => 'Sumbawanga District',
                'state_id' => 4062,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            536 => array(
                'id' => 1039,
                'name' => 'Mbinga District',
                'state_id' => 4063,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            537 => array(
                'id' => 1040,
                'name' => 'Namtumbo District',
                'state_id' => 4063,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            538 => array(
                'id' => 1041,
                'name' => 'Nyasa District',
                'state_id' => 4063,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            539 => array(
                'id' => 1042,
                'name' => 'Songea District',
                'state_id' => 4063,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            540 => array(
                'id' => 1043,
                'name' => 'Songea Municipal',
                'state_id' => 4063,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            541 => array(
                'id' => 1044,
                'name' => 'Tunduru District',
                'state_id' => 4063,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            542 => array(
                'id' => 1045,
                'name' => 'Kahama Town',
                'state_id' => 4064,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            543 => array(
                'id' => 1046,
                'name' => 'Kishapu District',
                'state_id' => 4064,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            544 => array(
                'id' => 1047,
                'name' => 'Msalala District',
                'state_id' => 4064,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            545 => array(
                'id' => 1048,
                'name' => 'Shinyanga District',
                'state_id' => 4064,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            546 => array(
                'id' => 1049,
                'name' => 'Shinyanga Municipal',
                'state_id' => 4064,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            547 => array(
                'id' => 1050,
                'name' => 'Ushetu District',
                'state_id' => 4064,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            548 => array(
                'id' => 1051,
                'name' => 'Bariadi District',
                'state_id' => 4065,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            549 => array(
                'id' => 1052,
                'name' => 'Busega District',
                'state_id' => 4065,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            550 => array(
                'id' => 1053,
                'name' => 'Itilima District',
                'state_id' => 4065,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            551 => array(
                'id' => 1054,
                'name' => 'Maswa District',
                'state_id' => 4065,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            552 => array(
                'id' => 1055,
                'name' => 'Meatu District',
                'state_id' => 4065,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            553 => array(
                'id' => 1056,
                'name' => 'Iramba District',
                'state_id' => 4066,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            554 => array(
                'id' => 1057,
                'name' => 'Ikungi District',
                'state_id' => 4066,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            555 => array(
                'id' => 1058,
                'name' => 'Manyoni District',
                'state_id' => 4066,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            556 => array(
                'id' => 1059,
                'name' => 'Mkalama District',
                'state_id' => 4066,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            557 => array(
                'id' => 1060,
                'name' => 'Singida District',
                'state_id' => 4066,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            558 => array(
                'id' => 1061,
                'name' => 'Singida Municipality',
                'state_id' => 4066,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            559 => array(
                'id' => 1062,
                'name' => 'Itigi District',
                'state_id' => 4066,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            560 => array(
                'id' => 1063,
                'name' => 'Ileje District',
                'state_id' => 4067,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            561 => array(
                'id' => 1064,
                'name' => 'Mbozi District',
                'state_id' => 4067,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            562 => array(
                'id' => 1065,
                'name' => 'Momba District',
                'state_id' => 4067,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            563 => array(
                'id' => 1066,
                'name' => 'Songwe District',
                'state_id' => 4067,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            564 => array(
                'id' => 1067,
                'name' => 'Igunga District',
                'state_id' => 4068,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            565 => array(
                'id' => 1068,
                'name' => 'Kaliua District',
                'state_id' => 4068,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            566 => array(
                'id' => 1069,
                'name' => 'Nzega District',
                'state_id' => 4068,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            567 => array(
                'id' => 1070,
                'name' => 'Sikonge District',
                'state_id' => 4068,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            568 => array(
                'id' => 1071,
                'name' => 'Tabora Municipal',
                'state_id' => 4068,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            569 => array(
                'id' => 1072,
                'name' => 'Urambo District',
                'state_id' => 4068,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            570 => array(
                'id' => 1073,
                'name' => 'Uyui District',
                'state_id' => 4068,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            571 => array(
                'id' => 1074,
                'name' => 'Handeni District',
                'state_id' => 4069,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            572 => array(
                'id' => 1075,
                'name' => 'Handeni Town',
                'state_id' => 4069,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            573 => array(
                'id' => 1076,
                'name' => 'Kilindi District',
                'state_id' => 4069,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            574 => array(
                'id' => 1077,
                'name' => 'Korogwe District',
                'state_id' => 4069,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            575 => array(
                'id' => 1078,
                'name' => 'Korogwe Town',
                'state_id' => 4069,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            576 => array(
                'id' => 1079,
                'name' => 'Lushoto District',
                'state_id' => 4069,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            577 => array(
                'id' => 1080,
                'name' => 'Muheza District',
                'state_id' => 4069,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            578 => array(
                'id' => 1081,
                'name' => 'Mkinga District',
                'state_id' => 4069,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            579 => array(
                'id' => 1082,
                'name' => 'Pangani District',
                'state_id' => 4069,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            580 => array(
                'id' => 1083,
                'name' => 'Tanga City',
                'state_id' => 4069,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            581 => array(
                'id' => 1084,
                'name' => 'Kaskazini A',
                'state_id' => 4070,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            582 => array(
                'id' => 1085,
                'name' => 'Kaskazini B',
                'state_id' => 4070,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            583 => array(
                'id' => 1086,
                'name' => 'Kati District',
                'state_id' => 4071,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            584 => array(
                'id' => 1087,
                'name' => 'Kusini District',
                'state_id' => 4071,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            585 => array(
                'id' => 1088,
                'name' => 'Bahi District',
                'state_id' => 4043,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
    }
}
