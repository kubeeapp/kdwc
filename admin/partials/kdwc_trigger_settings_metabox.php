
<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// get trigger rules data
$data = array();
global $data_versions, $available_pages, $post_status, $languages, $data_rules, $displayClosedFeature, $freeTriggers, $lockedConditionBox, $lockedVersionBox, $isLicenseValid, $allowedTriggersForRecurrence, $notAllowedTriggersForGroups,  $timezones, $removePagesVisitedCookie;

require_once(IFSO_PLUGIN_BASE_DIR. 'public/models/data-rules/kdwc-data-rules-model.class.php'); //including the model to get the trigger type list from it

/* Loading from DB */

// Default Content + correspnding Default Metadata
$data_default = get_post_meta( $post->ID, 'kdwc_trigger_default', true );
$data_default_metadata_json = 
                get_post_meta( $post->ID,
                              'kdwc_trigger_default_metadata',
                               true );
if ( !empty($data_default_metadata_json) ) {
    $data_default_metadata = json_decode($data_default_metadata_json, true);
} else {
    $data_default_metadata = array(
        'statistics_count' => 0
    );
}


// Rules + Versions
$data_rules_json = get_post_meta( $post->ID, 'kdwc_trigger_rules', true );
$data_rules = json_decode($data_rules_json, true);

$data_versions = get_post_meta( $post->ID, 'kdwc_trigger_version', false );
global $broken_trigger_types;
$broken_trigger_types = check_for_broken_trigger_types();

// TODO seperate to different file
$timezones = array("(UTC-12:00) International Date Line West","(UTC-11:00) Coordinated Universal Time-11","(UTC-10:00) Hawaii","(UTC-09:00) Alaska","(UTC-08:00) Baja California","(UTC-08:00) Pacific Time (US & Canada)","(UTC-07:00) Arizona","(UTC-07:00) Chihuahua, La Paz, Mazatlan","(UTC-07:00) Mountain Time (US & Canada)","(UTC-06:00) Central America","(UTC-06:00) Central Time (US & Canada)","(UTC-06:00) Guadalajara, Mexico City, Monterrey","(UTC-06:00) Saskatchewan","(UTC-05:00) Bogota, Lima, Quito","(UTC-05:00) Eastern Time (US & Canada)","(UTC-05:00) Indiana (East)","(UTC-04:30) Caracas","(UTC-04:00) Asuncion","(UTC-04:00) Atlantic Time (Canada)","(UTC-04:00) Cuiaba","(UTC-04:00) Georgetown, La Paz, Manaus, San Juan","(UTC-04:00) Santiago","(UTC-03:30) Newfoundland","(UTC-03:00) Brasilia","(UTC-03:00) Buenos Aires","(UTC-03:00) Cayenne, Fortaleza","(UTC-03:00) Greenland","(UTC-03:00) Montevideo","(UTC-03:00) Salvador","(UTC-02:00) Coordinated Universal Time-02","(UTC-02:00) Mid-Atlantic - Old","(UTC-01:00) Azores","(UTC-01:00) Cape Verde Is.","(UTC) Casablanca","(UTC) Coordinated Universal Time","(UTC) Dublin, Edinburgh, Lisbon, London","(UTC) Monrovia, Reykjavik","(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna","(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague","(UTC+01:00) Brussels, Copenhagen, Madrid, Paris","(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb","(UTC+01:00) West Central Africa","(UTC+01:00) Windhoek","(UTC+02:00) Athens, Bucharest","(UTC+02:00) Beirut","(UTC+02:00) Cairo","(UTC+02:00) Damascus","(UTC+02:00) E. Europe","(UTC+02:00) Harare, Pretoria","(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius","(UTC+03:00) Istanbul","(UTC+02:00) Jerusalem","(UTC+02:00) Tripoli","(UTC+03:00) Amman","(UTC+03:00) Baghdad","(UTC+03:00) Kaliningrad, Minsk","(UTC+03:00) Kuwait, Riyadh","(UTC+03:00) Nairobi","(UTC+03:00) Moscow, St. Petersburg, Volgograd","(UTC+04:00) Samara, Ulyanovsk, Saratov","(UTC+03:30) Tehran","(UTC+04:00) Abu Dhabi, Muscat","(UTC+04:00) Baku","(UTC+04:00) Port Louis","(UTC+04:00) Tbilisi","(UTC+04:00) Yerevan","(UTC+04:30) Kabul","(UTC+05:00) Ashgabat, Tashkent","(UTC+05:00) Islamabad, Karachi","(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi","(UTC+05:30) Sri Jayawardenepura","(UTC+05:45) Kathmandu","(UTC+06:00) Astana","(UTC+06:00) Dhaka","(UTC+06:00) Ekaterinburg","(UTC+06:30) Yangon (Rangoon)","(UTC+07:00) Bangkok, Hanoi, Jakarta","(UTC+07:00) Novosibirsk","(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi","(UTC+08:00) Krasnoyarsk","(UTC+08:00) Kuala Lumpur, Singapore","(UTC+08:00) Perth","(UTC+08:00) Taipei","(UTC+08:00) Ulaanbaatar","(UTC+09:00) Irkutsk","(UTC+09:00) Osaka, Sapporo, Tokyo","(UTC+09:00) Seoul","(UTC+09:30) Adelaide","(UTC+09:30) Darwin","(UTC+10:00) Brisbane","(UTC+10:00) Canberra, Melbourne, Sydney","(UTC+10:00) Guam, Port Moresby","(UTC+10:00) Hobart","(UTC+10:00) Yakutsk","(UTC+11:00) Solomon Is., New Caledonia","(UTC+11:00) Vladivostok","(UTC+12:00) Auckland, Wellington","(UTC+12:00) Coordinated Universal Time+12","(UTC+12:00) Fiji","(UTC+12:00) Magadan","(UTC+12:00) Petropavlovsk-Kamchatsky - Old","(UTC+13:00) Nuku'alofa","(UTC+13:00) Samoa");

$languages = array(
        array('en-US', 'en-UK', 'en', 'English', 'English'),
        array('he', 'heb', 'heb', 'heb', 'Hebrew', 'עברית'),
        array('ab', 'abk', 'abk', 'abk', 'Abkhaz', 'аҧсуа бызшәа, аҧсшәа'),
        array('aa', 'aar', 'aar', 'aar', 'Afar', 'Afaraf'),
        array('af', 'afr', 'afr', 'afr', 'Afrikaans', 'Afrikaans'),
        array('ak', 'aka', 'aka', 'aka', 'Akan', 'Akan'),
        array('sq', 'sqi', 'alb', 'sqi', 'Albanian', 'Shqip'),
        array('am', 'amh', 'amh', 'amh', 'Amharic', 'አማርኛ'),
        array('ar', 'ara', 'ara', 'ara', 'Arabic', 'العربية'),
        array('an', 'arg', 'arg', 'arg', 'Aragonese', 'aragonés'),
        array('hy', 'hye', 'arm', 'hye', 'Armenian', 'Հայերեն'),
        array('as', 'asm', 'asm', 'asm', 'Assamese', 'অসমীয়া'),
        array('av', 'ava', 'ava', 'ava', 'Avaric', 'авар мацӀ, магӀарул мацӀ'),
        array('ae', 'ave', 'ave', 'ave', 'Avestan', 'avesta'),
        array('ay', 'aym', 'aym', 'aym', 'Aymara', 'aymar aru'),
        array('az', 'aze', 'aze', 'aze', 'Azerbaijani', 'azərbaycan dili'),
        array('bm', 'bam', 'bam', 'bam', 'Bambara', 'bamanankan'),
        array('ba', 'bak', 'bak', 'bak', 'Bashkir', 'башҡорт теле'),
        array('eu', 'eus', 'baq', 'eus', 'Basque', 'euskara, euskera'),
        array('be', 'bel', 'bel', 'bel', 'Belarusian', 'беларуская мова'),
        array('bn', 'ben', 'ben', 'ben', 'Bengali, Bangla', 'বাংলা'),
        array('bh', 'bih', 'bih', '', 'Bihari', 'भोजपुरी'),
        array('bi', 'bis', 'bis', 'bis', 'Bislama', 'Bislama'),
        array('bs', 'bos', 'bos', 'bos', 'Bosnian', 'bosanski jezik'),
        array('br', 'bre', 'bre', 'bre', 'Breton', 'brezhoneg'),
        array('bg', 'bul', 'bul', 'bul', 'Bulgarian', 'български език'),
        array('my', 'mya', 'bur', 'mya', 'Burmese', 'ဗမာစာ'),
        array('ca', 'cat', 'cat', 'cat', 'Catalan', 'català'),
        array('ch', 'cha', 'cha', 'cha', 'Chamorro', 'Chamoru'),
        array('ce', 'che', 'che', 'che', 'Chechen', 'нохчийн мотт'),
        array('ny', 'nya', 'nya', 'nya', 'Chichewa, Chewa, Nyanja', 'chiCheŵa, chinyanja'),
        array('zh', 'zho', 'chi', 'zho', 'Chinese', '中文 (Zhōngwén), 汉语, 漢語'),
        array('cv', 'chv', 'chv', 'chv', 'Chuvash', 'чӑваш чӗлхи'),
        array('kw', 'cor', 'cor', 'cor', 'Cornish', 'Kernewek'),
        array('co', 'cos', 'cos', 'cos', 'Corsican', 'corsu, lingua corsa'),
        array('cr', 'cre', 'cre', 'cre', 'Cree', 'ᓀᐦᐃᔭᐍᐏᐣ'),
        array('hr', 'hrv', 'hrv', 'hrv', 'Croatian', 'hrvatski jezik'),
        array('cs', 'ces', 'cze', 'ces', 'Czech', 'čeština, český jazyk'),
        array('da', 'dan', 'dan', 'dan', 'Danish', 'dansk'),
        array('dv', 'div', 'div', 'div', 'Divehi, Dhivehi, Maldivian', 'ދިވެހި'),
        array('nl', 'nld', 'dut', 'nld', 'Dutch', 'Nederlands, Vlaams'),
        array('dz', 'dzo', 'dzo', 'dzo', 'Dzongkha', 'རྫོང་ཁ'),
        array('eo', 'epo', 'epo', 'epo', 'Esperanto', 'Esperanto'),
        array('et', 'est', 'est', 'est', 'Estonian', 'eesti, eesti keel'),
        array('ee', 'ewe', 'ewe', 'ewe', 'Ewe', 'Eʋegbe'),
        array('fo', 'fao', 'fao', 'fao', 'Faroese', 'føroyskt'),
        array('fj', 'fij', 'fij', 'fij', 'Fijian', 'vosa Vakaviti'),
        array('fi', 'fin', 'fin', 'fin', 'Finnish', 'suomi, suomen kieli'),
        array('fr', 'fra', 'fre', 'fra', 'French', 'français, langue française'),
        array('ff', 'ful', 'ful', 'ful', 'Fula, Fulah, Pulaar, Pular', 'Fulfulde, Pulaar, Pular'),
        array('gl', 'glg', 'glg', 'glg', 'Galician', 'galego'),
        array('ka', 'kat', 'geo', 'kat', 'Georgian', 'ქართული'),
        array('de', 'deu', 'ger', 'deu', 'German', 'Deutsch'),
        array('el', 'ell', 'gre', 'ell', 'Greek', 'ελληνικά'),
        array('gn', 'grn', 'grn', 'grn', 'Guaraní', 'Avañe\'ẽ'),
        array('gu', 'guj', 'guj', 'guj', 'Gujarati', 'ગુજરાતી'),
        array('ht', 'hat', 'hat', 'hat', 'Haitian, Haitian Creole', 'Kreyòl ayisyen'),
        array('ha', 'hau', 'hau', 'hau', 'Hausa', '(Hausa) هَوُسَ'),
        array('hz', 'her', 'her', 'her', 'Herero', 'Otjiherero'),
        array('hi', 'hin', 'hin', 'hin', 'Hindi', 'हिन्दी, हिंदी'),
        array('ho', 'hmo', 'hmo', 'hmo', 'Hiri Motu', 'Hiri Motu'),
        array('hu', 'hun', 'hun', 'hun', 'Hungarian', 'magyar'),
        array('ia', 'ina', 'ina', 'ina', 'Interlingua', 'Interlingua'),
        array('id', 'ind', 'ind', 'ind', 'Indonesian', 'Bahasa Indonesia'),
        array('ie', 'ile', 'ile', 'ile', 'Interlingue', 'Originally called Occidental; then Interlingue after WWII'),
        array('ga', 'gle', 'gle', 'gle', 'Irish', 'Gaeilge'),
        array('ig', 'ibo', 'ibo', 'ibo', 'Igbo', 'Asụsụ Igbo'),
        array('ik', 'ipk', 'ipk', 'ipk', 'Inupiaq', 'Iñupiaq, Iñupiatun'),
        array('io', 'ido', 'ido', 'ido', 'Ido', 'Ido'),
        array('is', 'isl', 'ice', 'isl', 'Icelandic', 'Íslenska'),
        array('it', 'ita', 'ita', 'ita', 'Italian', 'italiano'),
        array('iu', 'iku', 'iku', 'iku', 'Inuktitut', 'ᐃᓄᒃᑎᑐᑦ'),
        array('ja', 'jpn', 'jpn', 'jpn', 'Japanese', '日本語 (にほんご)'),
        array('jv', 'jav', 'jav', 'jav', 'Javanese', 'basa Jawa'),
        array('kl', 'kal', 'kal', 'kal', 'Kalaallisut, Greenlandic', 'kalaallisut, kalaallit oqaasii'),
        array('kn', 'kan', 'kan', 'kan', 'Kannada', 'ಕನ್ನಡ'),
        array('kr', 'kau', 'kau', 'kau', 'Kanuri', 'Kanuri'),
        array('ks', 'kas', 'kas', 'kas', 'Kashmiri', 'कश्मीरी, كشميري‎'),
        array('kk', 'kaz', 'kaz', 'kaz', 'Kazakh', 'қазақ тілі'),
        array('km', 'khm', 'khm', 'khm', 'Khmer', 'ខ្មែរ, ខេមរភាសា, ភាសាខ្មែរ'),
        array('ki', 'kik', 'kik', 'kik', 'Kikuyu, Gikuyu', 'Gĩkũyũ'),
        array('rw', 'kin', 'kin', 'kin', 'Kinyarwanda', 'Ikinyarwanda'),
        array('ky', 'kir', 'kir', 'kir', 'Kyrgyz', 'Кыргызча, Кыргыз тили'),
        array('kv', 'kom', 'kom', 'kom', 'Komi', 'коми кыв'),
        array('kg', 'kon', 'kon', 'kon', 'Kongo', 'Kikongo'),
        array('ko', 'kor', 'kor', 'kor', 'Korean', '한국어, 조선어'),
        array('ku', 'kur', 'kur', 'kur', 'Kurdish', 'Kurdî, كوردی‎'),
        array('kj', 'kua', 'kua', 'kua', 'Kwanyama, Kuanyama', 'Kuanyama'),
        array('la', 'lat', 'lat', 'lat', 'Latin', 'latine, lingua latina'),
        array('', '', '', 'lld', 'Ladin', 'ladin, lingua ladina'),
        array('lb', 'ltz', 'ltz', 'ltz', 'Luxembourgish, Letzeburgesch', 'Lëtzebuergesch'),
        array('lg', 'lug', 'lug', 'lug', 'Ganda', 'Luganda'),
        array('li', 'lim', 'lim', 'lim', 'Limburgish, Limburgan, Limburger', 'Limburgs'),
        array('ln', 'lin', 'lin', 'lin', 'Lingala', 'Lingála'),
        array('lo', 'lao', 'lao', 'lao', 'Lao', 'ພາສາລາວ'),
        array('lt', 'lit', 'lit', 'lit', 'Lithuanian', 'lietuvių kalba'),
        array('lu', 'lub', 'lub', 'lub', 'Luba-Katanga', 'Tshiluba'),
        array('lv', 'lav', 'lav', 'lav', 'Latvian', 'latviešu valoda'),
        array('gv', 'glv', 'glv', 'glv', 'Manx', 'Gaelg, Gailck'),
        array('mk', 'mkd', 'mac', 'mkd', 'Macedonian', 'македонски јазик'),
        array('mg', 'mlg', 'mlg', 'mlg', 'Malagasy', 'fiteny malagasy'),
        array('ms', 'msa', 'may', 'msa', 'Malay', 'bahasa Melayu, بهاس ملايو‎'),
        array('ml', 'mal', 'mal', 'mal', 'Malayalam', 'മലയാളം'),
        array('mt', 'mlt', 'mlt', 'mlt', 'Maltese', 'Malti'),
        array('mi', 'mri', 'mao', 'mri', 'Māori', 'te reo Māori'),
        array('mr', 'mar', 'mar', 'mar', 'Marathi (Marāṭhī)', 'मराठी'),
        array('mh', 'mah', 'mah', 'mah', 'Marshallese', 'Kajin M̧ajeļ'),
        array('mn', 'mon', 'mon', 'mon', 'Mongolian', 'монгол'),
        array('na', 'nau', 'nau', 'nau', 'Nauru', 'Ekakairũ Naoero'),
        array('nv', 'nav', 'nav', 'nav', 'Navajo, Navaho', 'Diné bizaad'),
        array('nd', 'nde', 'nde', 'nde', 'Northern Ndebele', 'isiNdebele'),
        array('ne', 'nep', 'nep', 'nep', 'Nepali', 'नेपाली'),
        array('ng', 'ndo', 'ndo', 'ndo', 'Ndonga', 'Owambo'),
        array('nb', 'nob', 'nob', 'nob', 'Norwegian Bokmål', 'Norsk bokmål'),
        array('nn', 'nno', 'nno', 'nno', 'Norwegian Nynorsk', 'Norsk nynorsk'),
        array('no', 'nor', 'nor', 'nor', 'Norwegian', 'Norsk'),
        array('ii', 'iii', 'iii', 'iii', 'Nuosu', 'ꆈꌠ꒿ Nuosuhxop'),
        array('nr', 'nbl', 'nbl', 'nbl', 'Southern Ndebele', 'isiNdebele'),
        array('oc', 'oci', 'oci', 'oci', 'Occitan', 'occitan, lenga d\'òc'),
        array('oj', 'oji', 'oji', 'oji', 'Ojibwe, Ojibwa', 'ᐊᓂᔑᓈᐯᒧᐎᓐ'),
        array('cu', 'chu', 'chu', 'chu', 'Old Church Slavonic, Church Slavonic, Old Bulgarian', 'ѩзыкъ словѣньскъ'),
        array('om', 'orm', 'orm', 'orm', 'Oromo', 'Afaan Oromoo'),
        array('or', 'ori', 'ori', 'ori', 'Oriya', 'ଓଡ଼ିଆ'),
        array('os', 'oss', 'oss', 'oss', 'Ossetian, Ossetic', 'ирон æвзаг'),
        array('pa', 'pan', 'pan', 'pan', 'Panjabi, Punjabi', 'ਪੰਜਾਬੀ, پنجابی‎'),
        array('pi', 'pli', 'pli', 'pli', 'Pāli', 'पाऴि'),
        array('fa', 'fas', 'per', 'fas', 'Persian (Farsi)', 'فارسی'),
        array('pl', 'pol', 'pol', 'pol', 'Polish', 'język polski, polszczyzna'),
        array('ps', 'pus', 'pus', 'pus', 'Pashto, Pushto', 'پښتو'),
        array('pt', 'por', 'por', 'por', 'Portuguese', 'português'),
        array('qu', 'que', 'que', 'que', 'Quechua', 'Runa Simi, Kichwa'),
        array('rm', 'roh', 'roh', 'roh', 'Romansh', 'rumantsch grischun'),
        array('rn', 'run', 'run', 'run', 'Kirundi', 'Ikirundi'),
        array('ro', 'ron', 'rum', 'ron', 'Romanian', 'limba română'),
        array('ru', 'rus', 'rus', 'rus', 'Russian', 'Русский'),
        array('sa', 'san', 'san', 'san', 'Sanskrit (Saṁskṛta)', 'संस्कृतम्'),
        array('sc', 'srd', 'srd', 'srd', 'Sardinian', 'sardu'),
        array('sd', 'snd', 'snd', 'snd', 'Sindhi', 'सिन्धी, سنڌي، سندھی‎'),
        array('se', 'sme', 'sme', 'sme', 'Northern Sami', 'Davvisámegiella'),
        array('sm', 'smo', 'smo', 'smo', 'Samoan', 'gagana fa\'a Samoa'),
        array('sg', 'sag', 'sag', 'sag', 'Sango', 'yângâ tî sängö'),
        array('sr', 'srp', 'srp', 'srp', 'Serbian', 'српски језик'),
        array('gd', 'gla', 'gla', 'gla', 'Scottish Gaelic, Gaelic', 'Gàidhlig'),
        array('sn', 'sna', 'sna', 'sna', 'Shona', 'chiShona'),
        array('si', 'sin', 'sin', 'sin', 'Sinhala, Sinhalese', 'සිංහල'),
        array('sk', 'slk', 'slo', 'slk', 'Slovak', 'slovenčina, slovenský jazyk'),
        array('sl', 'slv', 'slv', 'slv', 'Slovene', 'slovenski jezik, slovenščina'),
        array('so', 'som', 'som', 'som', 'Somali', 'Soomaaliga, af Soomaali'),
        array('st', 'sot', 'sot', 'sot', 'Southern Sotho', 'Sesotho'),
        array('es', 'spa', 'spa', 'spa', 'Spanish', 'español'),
        array('su', 'sun', 'sun', 'sun', 'Sundanese', 'Basa Sunda'),
        array('sw', 'swa', 'swa', 'swa', 'Swahili', 'Kiswahili'),
        array('ss', 'ssw', 'ssw', 'ssw', 'Swati', 'SiSwati'),
        array('sv', 'swe', 'swe', 'swe', 'Swedish', 'svenska'),
        array('ta', 'tam', 'tam', 'tam', 'Tamil', 'தமிழ்'),
        array('te', 'tel', 'tel', 'tel', 'Telugu', 'తెలుగు'),
        array('tg', 'tgk', 'tgk', 'tgk', 'Tajik', 'тоҷикӣ, toçikī, تاجیکی‎'),
        array('th', 'tha', 'tha', 'tha', 'Thai', 'ไทย'),
        array('ti', 'tir', 'tir', 'tir', 'Tigrinya', 'ትግርኛ'),
        array('bo', 'bod', 'tib', 'bod', 'Tibetan Standard, Tibetan, Central', 'བོད་ཡིག'),
        array('tk', 'tuk', 'tuk', 'tuk', 'Turkmen', 'Türkmen, Түркмен'),
        array('tl', 'tgl', 'tgl', 'tgl', 'Tagalog', 'Wikang Tagalog, ᜏᜒᜃᜅ᜔ ᜆᜄᜎᜓᜄ᜔'),
        array('tn', 'tsn', 'tsn', 'tsn', 'Tswana', 'Setswana'),
        array('to', 'ton', 'ton', 'ton', 'Tonga (Tonga Islands)', 'faka Tonga'),
        array('tr', 'tur', 'tur', 'tur', 'Turkish', 'Türkçe'),
        array('ts', 'tso', 'tso', 'tso', 'Tsonga', 'Xitsonga'),
        array('tt', 'tat', 'tat', 'tat', 'Tatar', 'татар теле, tatar tele'),
        array('tw', 'twi', 'twi', 'twi', 'Twi', 'Twi'),
        array('ty', 'tah', 'tah', 'tah', 'Tahitian', 'Reo Tahiti'),
        array('ug', 'uig', 'uig', 'uig', 'Uyghur', 'ئۇيغۇرچە‎, Uyghurche'),
        array('uk', 'ukr', 'ukr', 'ukr', 'Ukrainian', 'українська мова'),
        array('ur', 'urd', 'urd', 'urd', 'Urdu', 'اردو'),
        array('uz', 'uzb', 'uzb', 'uzb', 'Uzbek', 'Oʻzbek, Ўзбек, أۇزبېك‎'),
        array('ve', 'ven', 'ven', 'ven', 'Venda', 'Tshivenḓa'),
        array('vi', 'vie', 'vie', 'vie', 'Vietnamese', 'Việt Nam'),
        array('vo', 'vol', 'vol', 'vol', 'Volapük', 'Volapük'),
        array('wa', 'wln', 'wln', 'wln', 'Walloon', 'walon'),
        array('cy', 'cym', 'wel', 'cym', 'Welsh', 'Cymraeg'),
        array('wo', 'wol', 'wol', 'wol', 'Wolof', 'Wollof'),
        array('fy', 'fry', 'fry', 'fry', 'Western Frisian', 'Frysk'),
        array('xh', 'xho', 'xho', 'xho', 'Xhosa', 'isiXhosa'),
        array('yi', 'yid', 'yid', 'yid', 'Yiddish', 'ייִדיש'),
        array('yo', 'yor', 'yor', 'yor', 'Yoruba', 'Yorùbá'),
        array('za', 'zha', 'zha', 'zha', 'Zhuang, Chuang', 'Saɯ cueŋƅ, Saw cuengh'),
        array('zu', 'zul', 'zul', 'zul', 'Zulu', 'isiZulu')
    );
// get all available pages
$args = array(
    'sort_order' => 'asc',
    'sort_column' => 'post_title',
    'hierarchical' => 1,
    'child_of' => 0,
    'post_type' => 'page',
    'post_status' => 'publish',
    'suppress_filters' => true
); 
$available_pages = get_pages($args);
$post_status = get_post_status();

/* KDWC License Begin */
$status  = get_option( 'edd_kdwc_license_status' );
$geoStatus = get_option('edd_kdwc_geo_license_status');

$isLicenseValid = ($status !== false && $status == 'valid') ? true : false;
$isGeoLicenseValid = ($geoStatus !== false && $geoStatus == 'valid') ? true : false;

$displayClosedFeature = " *";

if ($isLicenseValid) $displayClosedFeature = "";

$freeTriggers = ["Device", "User-Behavior", "Geolocation", "UserIp", "Time-Date"];

$allowedTriggersForRecurrence = apply_filters('kdwc_allow_triggers_for_recurrence_filter',  //To allow adding recurrence to custom conditions
                                                array("AB-Testing",
                                              "advertising-platforms",
                                              "url",
                                              "referrer",
                                              "PageUrl",
                                              "PageVisit",
                                              "Utm",
                                            ));
$notAllowedTriggersForGroups = array("Device");

// $lockedConditionBox = '
//     <div class="locked-condition-box">
//         <div class="text">
//             This is locked condition. Click here to unlock all features.
//         </div>

//         <a href="#" class="unlock-button">
//             <i class="fa fa-unlock-alt" aria-hidden="true"></i> Unlock Now
//         </a>
//     </div>
// ';

/* Ifso License End */

require_once(IFSO_PLUGIN_BASE_DIR . 'services/plugin-settings-service/plugin-settings-service.class.php');

use KDWC\Services\PluginSettingsService;

$settingsServiceInstance
    = PluginSettingsService\PluginSettingsService::get_instance();

$pagesVisitedOption = 
    $settingsServiceInstance->pagesVisitedOption->get();
$pagesVisitedDurationValue = $pagesVisitedOption->get_duration_value();
$pagesVisitedDurationType = $pagesVisitedOption->get_duration_type();
$removePagesVisitedCookie = $settingsServiceInstance->removePageVisitsCookie->get();
global $pagesVisitedDurationVisualTime;
$pagesVisitedDurationVisualTime = 
    $pagesVisitedDurationValue . ' ' . $pagesVisitedDurationType;

?>

<?php

function check_for_broken_trigger_types(){
    global $data_rules;

    $data_rules_model  = new KDWC\PublicFace\Models\DataRulesModel\DataRulesModel;
    $trigger_type_list = $data_rules_model->get_trigger_types();
    $bad_versions = [];

    if(!empty($data_rules)){
        foreach($data_rules as $version=>$rule){
            if(isset($rule['trigger_type']) && !empty($rule['trigger_type'])){
                if(!in_array($rule['trigger_type'],$trigger_type_list)){
                    $bad_versions[$version] = $rule['trigger_type'];
                }
            }
        }
    }
    if(!empty($bad_versions)){
        return $bad_versions;
    }
    else
        return false;
}

function generate_version_symbol($version_number) {
    $num_of_characters_in_abc = 26;
    $base_ascii = 64;
    $version_number = intval($version_number) - $base_ascii;

    $postfix = '';
    if ($version_number > $num_of_characters_in_abc) {
        $postfix = intval($version_number / $num_of_characters_in_abc) + 1;
        $version_number %= $num_of_characters_in_abc;
        if ($version_number == 0) {
            $version_number = $num_of_characters_in_abc;
            $postfix -= 1;    
        }
    }
    
    $version_number += $base_ascii;
    return chr($version_number) . strval($postfix);
}

function generateDataAttributes($nextAttrs = []){
    $dataStr = 'cookie|new-visitor|utm-type|utm-relation|utm-value|user-ip|referrer-selection|referrer-custom|ab-testing-custom|url-custom|page-selection|AB-Testing|ab-testing-selection|custom-url-display|User-Behavior|user-behavior-returning|user-behavior-retn-custom|user-behavior-selection|time-date-selection|schedule-selection|times-dates-schedules-selections|time-date-pick-start-date|time-date-pick-end-date|user-behavior-loggedinout|user-behavior-browser-language|common-referrers|locked-box|user-behavior-logged-selection|advertising-platforms-selection|geolocation-selection|geolocation-behaviour|recurrence-field|advertising-platforms-facebook-section|page-visit-selection|page-url-selection|page-url-custom|user-behavior-device|reset-notification|groups-field|group-name|user-group-relation|user-role|user-role-relationship';
    $attrsArray = explode('|', $dataStr);
    $attrsArray = apply_filters('kdwc_custom_conditions_expand_data_reset_by_selector',$attrsArray);
    $nextAttrsStr = implode('|',$nextAttrs);

    foreach($nextAttrs as $attr){
        $attrsArray = array_diff($attrsArray,[$attr]);
    }

    $newDataStr = implode('|',$attrsArray);

    $finalStr = ' data-reset="'. $newDataStr . '"' .(empty($nextAttrsStr) ? '' :  ' data-next-field="'. $nextAttrsStr .'"');

    return $finalStr;

}

function get_rule_item($index, $rule=array(), $is_template = false) {
    global $data_versions,
           $available_pages,
           $post_status,
           $languages, 
           $lockedVersionBox, 
           $data_rules, 
           $displayClosedFeature, 
           $freeTriggers, 
           $lockedConditionBox, 
           $isLicenseValid, 
           $allowedTriggersForRecurrence,
           $notAllowedTriggersForGroups,
           $timezones,
           $broken_trigger_types,
           $removePagesVisitedCookie,
           $pagesVisitedDurationVisualTime;

    if($is_template) {
        $current_version_index = 'index_placeholder';
        $current_version_count = '{version_number}';
        $current_datetime_count = '{datetime_number}';
        $current_version_count_char = '{version_char}';
        $current_instructions = '{version_instructions}';
    }
    else {
        $current_version_index = $index; //+1; // Removed the +1
        $current_version_count = $current_version_index+1;
        $current_datetime_count = "";
        $current_version_count_char = generate_version_symbol(64 + $current_version_index+1);

        if ($index == 0) {
            // First one!
            $current_instructions = __("Select a condition. The content will be displayed only if it is met", 'kd-wc');
        } else if ($index == 1) {
            // Start by singulars
            $current_instructions = __("Select a condition. The content will be displayed only if it is met and if version A is not realized", 'kd-wc');
        } else {
            // Start by many
            $prevChar = generate_version_symbol(64 + $current_version_index);

            $current_instructions = __("Select a condition. The content will be displayed only if it is met and if versions A-".$prevChar." are not realized", 'kd-wc');
        }
    }
    $groups_service = KDWC\PublicFace\Services\GroupsService\GroupsService::get_instance();
    $groups_list = $groups_service->get_groups();
?>
    <li data-repeater-list="group-version" class="rule-item reapeater-item reapeater-item-cloned <?php echo (!$is_template) ? 'reapeater-item-cloned-loaded' : ''; ?>">
        <div class="row rule-wrap">
            <div class="col-xs-12 rule-toolbar-wrap <?php echo (!$isLicenseValid && (!$is_template && isset($rule['trigger_type']) &&  !empty($rule['trigger_type']) && !in_array($rule['trigger_type'], $freeTriggers) || (isset($rule['trigger_type']) && $rule['trigger_type'] == "User-Behavior" && isset($rule['User-Behavior']) && !in_array($rule['User-Behavior'], array('LoggedIn', 'LoggedOut', 'Logged'))))) ? '' : ''; ?><?php echo (isset($rule['freeze-mode']) && $rule['freeze-mode'] == "true") ? "freeze-overlay-active-container" : ""; ?>">
                <h3>
                    <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/IfSo_logo25X8.png'; ?>" />
                    <!--<span class="version-count"><?php echo $current_version_count; ?></span>-->
                    <span class="version-alpha"><?php echo (__('Dynamic Content').' - '.__('Version', 'kd-wc')); ?> <?php echo $current_version_count_char; ?></span> 
                </h3>
                <button type="button" data-repeater-delete class="repeater-delete btn btn-delete" title=<?php _e('Delete', 'kd-wc')?>><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                <!-- begin freeze mode section -->

                <?php if ((isset($rule['freeze-mode']) &&
                          $rule['freeze-mode'] == "true")): ?>
                    <div class="btn btn-freeze kdwc-freezemode" title=<?php _e('Activate/Deactivate', 'kd-wc')?>>
                        <span class="text"><i class="fa fa-play" aria-hidden="true"></i></span>
                    </div>
                <?php else: ?>
                    <div class="btn btn-freeze kdwc-freezemode" title=<?php _e('Activate/Deactivate', 'kd-wc')?>>
                        <span class="text"><i class="fa fa-pause" aria-hidden="true"></i></span>
                    </div>
                <?php endif; ?>

                <input type="hidden" class="freeze-mode-val" name="repeater[<?php echo $current_version_index; ?>][freeze-mode]" <?php echo isset($rule['freeze-mode']) ? "value='{$rule['freeze-mode']}'" : ''; ?> />
                <!-- end freeze mode section -->

                <!-- begin draggable section -->
                <div class="btn kdwc-btn-drag" title=<?php _e('Draggable', 'kd-wc')?>>
                    <span class="text"><i class="fa fa fa-arrows-alt kdwc-draggable-icon" aria-hidden="true"></i></span>
                </div>
                <!-- end draggable section -->

                <div class="col-xs-12 locked-condition-box-container referrer-custom <?php echo (!$isLicenseValid && (!$is_template && isset($rule['trigger_type']) && !in_array($rule['trigger_type'], $freeTriggers) || (isset($rule['trigger_type']) && $rule['trigger_type'] == "User-Behavior" && isset($rule['User-Behavior']) && !in_array($rule['User-Behavior'], array('LoggedIn', 'LoggedOut', 'Logged')))) && !empty($rule['trigger_type'])) ? 'show-selection' : '' ?>" data-field="locked-box">

                    <?php if(!$isLicenseValid): ?>

                        <?php echo '<div id="conditionbox_target"></div>'; ?>

                    <?php endif; ?>

                </div>
            </div>
            <div class="col-md-3">
                <p class="versioninstructions"><?php echo $current_instructions; ?>:</p>
                <div class="kdwc-form-group">
                    <!--<label for="repeatable_editor_repeatable_editor_content"><?php _e( 'Trigger', 'kd-wc' ); ?></label><br>-->
                    <select name="repeater[<?php echo $current_version_index; ?>][trigger_type]" class="form-control trigger-type" autocomplete="off">
                        <option value="" <?php echo generateDataAttributes(); ?> ><?php _e('Select a Condition', 'kd-wc'); ?></option>


                        <option value="Device" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Device') ? 'SELECTED' : '';echo generateDataAttributes(['user-behavior-device']); ?>><?php _e('Device', 'kd-wc'); ?></option>

                        <option value="User-Behavior" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'User-Behavior') ? 'SELECTED' : '';echo generateDataAttributes(['user-behavior-selection','user-behavior-logged-selection','recurrence-field','groups-field']); ?>>
                            <?php _e('User Behavior', 'kd-wc'); ?>
                        </option>

                        <option value="referrer" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'referrer') ? 'SELECTED' : '';echo generateDataAttributes(['referrer-selection','referrer-custom','locked-box','recurrence-field','groups-field']); ?>>
                            <?php echo (__('Referrer Source', 'kd-wc') . $displayClosedFeature); ?>
                        </option>

                        <option value="PageUrl" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'PageUrl') ? 'SELECTED' : '';echo generateDataAttributes(['page-url-custom','locked-box','recurrence-field','groups-field']);?> >
                            <?php echo (__('Page Url', 'kd-wc') . $displayClosedFeature); ?>
                        </option>

                        <option value="advertising-platforms" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'advertising-platforms') ? 'SELECTED' : '';echo generateDataAttributes(['advertising-platforms-selection','advertising-platforms-google-section','locked-box','recurrence-field','groups-field']); ?>>
                            <?php echo (__('Advertising Platforms', 'kd-wc').$displayClosedFeature); ?>
                        </option>
                        <option value="url" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'url') ? 'SELECTED' : '';echo generateDataAttributes(['url-custom','locked-box','recurrence-field','groups-field']); ?>>
                            <?php echo (__('Dynamic Link', 'kd-wc').$displayClosedFeature); ?>
                        </option>
                        <option value="AB-Testing" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'AB-Testing') ? 'SELECTED' : '';echo generateDataAttributes(['ab-testing-selection','locked-box','recurrence-field','groups-field']); ?>>
                            <?php echo (__('A/B Testing', 'kd-wc').$displayClosedFeature); ?>

                        <option value="Time-Date" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Time-Date') ? 'SELECTED' : '';echo generateDataAttributes(['time-date-pick-start-date','time-date-pick-end-date','time-date-selection','times-dates-schedules-selections','groups-field']); ?>>
                            <?php echo (__('Time & Date', 'kd-wc')); ?>
                        </option>

                        <option value="Geolocation" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Geolocation') ? 'SELECTED' : '';echo generateDataAttributes(['geolocation-selection','geolocation-behaviour','groups-field']); ?>>
                            <?php _e('Geolocation', 'kd-wc'); ?>
                        </option>

                        <option value="PageVisit" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'PageVisit') ? 'SELECTED' : '';echo generateDataAttributes(['page-visit-selection','locked-box','recurrence-field','groups-field']); ?>>
                            <?php echo (__('Pages Visited', 'kd-wc').$displayClosedFeature); ?>
                        </option>

                        <option value="Cookie" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Cookie') ? 'SELECTED' : '';echo generateDataAttributes(['cookie','locked-box','groups-field']); ?>>
                            <?php echo (__('Cookie', 'kd-wc') . $displayClosedFeature); ?>
                        </option>


                        <option value="UserIp" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'UserIp') ? 'SELECTED' : '';echo generateDataAttributes(['user-ip','groups-field']); ?>>
                            <?php echo (__('User IP', 'kd-wc')); ?>
                        </option>

                        <option value="Utm" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Utm') ? 'SELECTED' : '';echo generateDataAttributes(['utm-type','utm-relation','utm-value','locked-box','recurrence-field','groups-field']); ?>>
                            <?php echo (__('UTM', 'kd-wc') . $displayClosedFeature); ?>
                        </option>

                        <option value="Groups" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Groups') ? 'SELECTED' : '';echo generateDataAttributes(['group-name', 'user-group-relation', 'locked-box','recurrence-field','groups-field']); ?>>
                            <?php echo (__('Audiences', 'kd-wc') . $displayClosedFeature); ?>
                        </option>

                        <option value="userRoles" <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'userRoles') ? 'SELECTED' : '';echo generateDataAttributes(['user-role','user-role-relationship', 'locked-box', 'groups-field']); ?> >
                            <?php echo (__('User Role', 'kd-wc')) . $displayClosedFeature; ?>
                        </option>

                        <?php do_action('kdwc_custom_conditions_ui_selector',$rule); ?>

                    </select>

                </div>
                <div class="kdwc-form-group">
                    <select name="repeater[<?php echo $current_version_index; ?>][trigger]" data-field="referrer-selection" class="form-control referrer-selection <?php echo (isset($rule['trigger']) && !empty($rule['trigger']) && isset($rule['trigger_type']) && $rule['trigger_type'] == 'referrer') ? 'show-selection' : ''; ?>">
                        <!--<option value="" data-reset="referrer-custom|url-custom|page-selection|common-referrers"><?php _e('Choose a Referrer', 'kd-wc'); ?></option>-->
                        <option value="custom" <?php echo (isset($rule['trigger']) && $rule['trigger'] == 'custom') ? 'SELECTED' : ''; ?> data-reset="common-referrers|page-selection|url-custom" data-next-field="referrer-custom|locked-box"><?php _e('URL', 'kd-wc'); ?></option>
                        <option value="page-on-website" <?php echo (isset($rule['trigger']) && $rule['trigger'] == 'page-on-website') ? 'SELECTED' : ''; ?> data-next-field="page-selection|locked-box" data-reset="common-referrers|referrer-custom|url-custom"><?php _e('Page on your website', 'kd-wc'); ?></option>
                        <option value="common-referrers" <?php echo (isset($rule['trigger']) && $rule['trigger'] == 'common-referrers') ? 'SELECTED' : ''; ?> data-next-field="common-referrers|locked-box" data-reset="referrer-custom|url-custom|page-selection"><?php _e('Common Referrers', 'kd-wc'); ?></option>
                    </select>
                </div>
                <div class="kdwc-form-group">
                    <select name="repeater[<?php echo $current_version_index; ?>][AB-Testing]" data-field="ab-testing-selection" class="form-control ab-testing <?php echo (isset($rule['AB-Testing']) && !empty($rule['AB-Testing']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'AB-Testing')) ? 'show-selection' : ''; ?>">
                        <option value="50%" <?php echo (isset($rule['AB-Testing']) && $rule['AB-Testing'] == '50%') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection"><?php _e('50% of the sessions', 'kd-wc'); ?></option>
                        <option value="25%" <?php echo (isset($rule['AB-Testing']) && $rule['AB-Testing'] == '25%') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection"><?php _e('25% of the sessions', 'kd-wc'); ?></option>
                        <option value="33%" <?php echo (isset($rule['AB-Testing']) && $rule['AB-Testing'] == '33%') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection"><?php _e('33% of the sessions', 'kd-wc'); ?></option>
                        <option value="75%" <?php echo (isset($rule['AB-Testing']) && $rule['AB-Testing'] == '75%') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection
                        "><?php _e('75% of the sessions', 'kd-wc'); ?></option>
                        <option value="100%" <?php echo (isset($rule['AB-Testing']) && $rule['AB-Testing'] == '100%') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection
                        "><?php _e('100% (control group)', 'kd-wc'); ?></option>
                    </select>

                    <div class="ab-testing-custom-sessions-display <?php echo (isset($rule['AB-Testing']) && !empty($rule['AB-Testing']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'AB-Testing')) ? 'show-selection' : ''; ?>" data-field="ab-testing-selection">
                        <p><?php echo (__('Limit','kd-wc') . __('Optional', 'kd-wc') . ':'); ?></p>
                    </div>

                    <input type="hidden" name="repeater[<?php echo $current_version_index; ?>][saved_number_of_views]" <?php echo ((!empty($rule['number_of_views']) || (isset($rule['number_of_views']) && $rule['number_of_views'] == 0)) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'AB-Testing')) ? "value='{$rule['number_of_views']}'" : ''; ?> />
                    
                    <select name="repeater[<?php echo $current_version_index; ?>][ab-testing-sessions]" data-field="ab-testing-selection" class="form-control ab-testing <?php echo (isset($rule['ab-testing-sessions']) && !empty($rule['ab-testing-sessions']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'AB-Testing')) ? 'show-selection' : ''; ?>">
                        <option value="Unlimited" data-reset="common-referrers|referrer-custom|url-custom|page-selection|ab-testing-custom"><?php _e('Unlimited', 'kd-wc'); ?></option>
                        <option value="100" <?php echo (isset($rule['ab-testing-sessions']) && $rule['ab-testing-sessions'] == '100') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|ab-testing-custom"><?php _e('100 Sessions', 'kd-wc'); ?></option>
                        <option value="200" <?php echo (isset($rule['ab-testing-sessions']) && $rule['ab-testing-sessions'] == '200') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|ab-testing-custom"><?php _e('200 Sessions', 'kd-wc'); ?></option>
                        <option value="500" <?php echo (isset($rule['ab-testing-sessions']) && $rule['ab-testing-sessions'] == '500') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|ab-testing-custom"><?php _e('500 Sessions', 'kd-wc'); ?></option>
                        <option value="1000" <?php echo (isset($rule['ab-testing-sessions']) && $rule['ab-testing-sessions'] == '1000') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|ab-testing-custom"><?php _e('1000 Sessions', 'kd-wc'); ?></option>
                        <option value="2000" <?php echo (isset($rule['ab-testing-sessions']) && $rule['ab-testing-sessions'] == '2000') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|ab-testing-custom"><?php _e('2000 Sessions', 'kd-wc'); ?></option>
                        <option value="Custom" <?php echo (isset($rule['ab-testing-sessions']) && $rule['ab-testing-sessions'] == 'Custom') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|ab-testing-custom" data-next-field="ab-testing-custom|locked-box"><?php _e('Custom', 'kd-wc'); ?></option>
                    </select>
                </div>

                <?php if (!isset($_COOKIE['kdwc_hide_abt_notice'])): ?>
                    <div class="kdwc-form-group">
                        <div data-field="ab-testing-selection" class="noticebox-container  <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'AB-Testing') ? 'show-selection' : ''; ?>">
                            <div class="abt-noticebox purple-noticebox"><span class="closeX">X</span><p><?php _e('Use the recurrence option and create a control group to conduct a proper split testing.', 'kd-wc');?>  <a target="_blank" href="https://www.kd-wc.com/help/documentation/ab-testing/?utm_source=Plugin&utm_medium=Instructions&utm_campaign=abtesting&utm_term=abt"><?php _e('Learn More','kd-wc'); ?></a> </p></div>
                        </div>
                    </div>
                <?php endif; ?>


                <div class="kdwc-form-group">
                    <select name="repeater[<?php echo $current_version_index; ?>][Time-Date-Schedule-Selection]" data-field="times-dates-schedules-selections" class="form-control ab-testing kdwc-pick-start-date <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Time-Date') ? 'show-selection' : ''; ?>">
                        <option value="Start-End-Date" <?php echo (isset($rule['Time-Date-Schedule-Selection']) && $rule['Time-Date-Schedule-Selection'] == 'Start-End-Date') ? 'SELECTED' : ''; ?> data-reset="locked-box|common-referrers|referrer-custom|url-custom|page-selection|ab-testing-custom|schedule-selection"
                            data-next-field="time-date-pick-start-date|time-date-pick-end-date"><?php _e('Start/End Date', 'kd-wc'); ?></option>
                       <option value="Schedule-Date" <?php echo (isset($rule['Time-Date-Schedule-Selection']) && $rule['Time-Date-Schedule-Selection'] == 'Schedule-Date') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|ab-testing-custom|time-date-selection|time-date-pick-start-date|time-date-pick-end-date" data-next-field="schedule-selection|locked-box"><?php echo(__('Schedule', 'kd-wc') . $displayClosedFeature); ?></option>
                    </select>
                </div>


                <div class="kdwc-form-group">

                    <div class="ab-testing-custom-sessions-display kdwc-start-at-date <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Time-Date' && isset($rule['Time-Date-Schedule-Selection']) && $rule['Time-Date-Schedule-Selection'] == "Schedule-Date") ? 'show-selection' : ''; ?>" data-field="schedule-selection">
                        <input type="hidden" class="schedule-input" name="repeater[<?php echo $current_version_index; ?>][Date-Time-Schedule]" value="" />
                        <p class="instruction"><i class="fa fa-info-circle" aria-hidden="true"></i><?php _e('Press a column > drag > click again', 'kd-wc'); ?></p>
                        <div class="date-time-schedule" id="<?php echo "schedule-" . $current_version_index ?>"></div>
                        
                        <?php

                            if (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Time-Date' &&
                                isset($rule['Time-Date-Schedule-Selection']) && $rule['Time-Date-Schedule-Selection'] == "Schedule-Date") {
                                // Deserialize data
                                ?>

                                <script type="text/javascript">
                                    (function( $ ) {
                                            $(document).ready(function(){
                                                var scheduleData = JSON.parse('<?php _e($rule["Date-Time-Schedule"]); ?>');
                                                var scheduleId = '<?php _e("#schedule-".$current_version_index) ?>';

                                                $(scheduleId).data('artsy.dayScheduleSelector').deserialize(scheduleData);
                                            });
                                    })( jQuery );
                                </script>

                        <?php } ?>
                    
                    </div>


                </div>


                <div class="kdwc-form-group">

<!-- itshere $rule['User-Behavior'] == 'NewUser' -->
                    <div class="ab-testing-custom-sessions-display kdwc-start-at-date <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Time-Date' && $rule["Time-Date-Schedule-Selection"] !== "Schedule-Date") ? 'show-selection' : ''; ?>" data-field="time-date-pick-start-date">
                        <p class="start-end-date-headers"><?php _e('Start displaying content from:', 'kd-wc'); ?></p>
                    </div>

                </div>

                <div class="kdwc-form-group">

                <div class="kdwc-trigger-selection kdwc-autocomplete-selection-display <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'PageVisit') ? 'show-selection' : '';?>" data-field="page-visit-selection">

                            <?php 
                                $areThereAnySelections = (isset($rule['page_visit_data']) &&
                                                      !empty($rule['page_visit_data']));

                            ?>

                            <div class="kdwc-autocomplete-fields-container <?php echo ($areThereAnySelections) ? "shown":""; ?>">
                            
                             <div class="locations-description <?php echo (!$areThereAnySelections) ? "hide-field" : "";?>">
                                <div class="kdwc-pages-visited-settings-explain">
                                    This version will be displayed if the visitor has visited one of the following pages in the last <a href="<?php echo admin_url( 'admin.php?page=' . EDD_IFSO_PLUGIN_SETTINGS_PAGE ); ?>" target="_blank"><?php echo $pagesVisitedDurationVisualTime; ?>&nbsp;<i class="fa fa-edit"><!--icon--></i></a>.
                                </div>
                            </div>

                                <?php 
                                    if ($areThereAnySelections) {
                                        $page_visit_data = utf8_decode($rule['page_visit_data']);
                                        $page_visit_data = str_replace('\\', '', $page_visit_data);
                                        ?>
                                        <input class="kdwc-autocomplete-data-field" type="hidden" name="repeater[<?php echo $current_version_index; ?>][page_visit_data]" value="<?php echo $page_visit_data; ?>" />

                                        <?php

                                        $splitted_page_visit_data = explode("^^", $page_visit_data);
                                        $i = 0;

                                        foreach ($splitted_page_visit_data as $key => $value) {
                                            if ($value != "1") {
                                                $explodedData = explode("!!", $value);

                                                if (is_numeric($explodedData[2])) {
                                                    $pageUrl = get_permalink($explodedData[2]);

                                                    $pageUrl = trim($pageUrl, '/');
                                                    $pageUrl = str_replace('https://', '', $pageUrl);
                                                    $pageUrl = str_replace('http://', '', $pageUrl);
                                                    $pageUrl = str_replace('www.', '', $pageUrl);
                                                    $operator = "url is";
                                                } else {
                                                    $pageUrl = $explodedData[1];
                                                    $operator = $explodedData[2];
                                                }


                                                ?>
                                                <div class="locationField">
                                                <span class="kdwc-page-visit-operator-field"><?php echo $operator; ?>: </span>
                                                </br>
                                                <span class="specific-location"><?php echo $pageUrl; ?></span>
                                                    <button class="remove-autocomplete" data-pos="<?php echo $i; ?>"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
                                                </div>
                                                <?php
                                                $i += 1;
                                            }
                                        }

                                    } else {
                                        ?>
                                        <input class="kdwc-autocomplete-data-field" type="hidden" name="repeater[<?php echo $current_version_index; ?>][page_visit_data]" />
                                        <?php
                                    }
                                ?>
                            </div>

                        <div class="select-locations-container">
                               <div class="selection-title">
                                   <div class="none-selected <?php echo ($areThereAnySelections) ? "hide-field" : ""; ?>">
                                       Choose a page
                                   </div>

                                   <div class="multiple-selected <?php echo (!$areThereAnySelections) ? "hide-field" : ""; ?>">
                                        Add another page
                                    </div>    

                                </div>
                        <div class="selection-inputs-container">

                            <!-- Page Visit Operator Begin -->
                            <div class="kdwc-form-group">
                                <select name="repeater[<?php echo $current_version_index; ?>][page-visit-operator]" class="form-control referrer-custom url-custom kdwc-page-visit-operator <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'PageVisit') ? 'show-selection' : ''; ?>" data-field="page-visit-selection">
                                    <option value="url is"><?php _e('Url Is', 'kd-wc'); ?></option>
                                    <option value="url contains"><?php _e('Url Contains', 'kd-wc'); ?></option>
                                    <option value="url is not"><?php _e('Url Is Not', 'kd-wc'); ?></option>
                                    <option value="url not contains"><?php _e('Url Does Not Contain', 'kd-wc'); ?></option>
                                </select>
							</div>
                            <!-- Page Visit Operator End -->

                            <input placeholder="Value" data-symbol="PAGEURL" class="page-visit-autocomplete kdwc-input-autocomplete" autocomplete="off"/>
                    </div>
                    </div>

                </div>

                </div>
                <?php if ($removePagesVisitedCookie): ?>
                    <div class="kdwc-form-group">
                        <div data-field="page-visit-selection" class="noticebox-container  <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'PageVisit') ? 'show-selection' : ''; ?>">
                            <div class="pagevisit-noticebox yellow-noticebox"><p><?php _e('The pages visited condition relies on a cookie to track the visitor\'s activity. Activate the cookie to use this condition.', 'kd-wc');?>  <a target="_blank" href="<?php echo admin_url('admin.php?page=' . EDD_IFSO_PLUGIN_SETTINGS_PAGE); ?>">Settings</a> </p></div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="kdwc-form-group">

                    <div class="kdwc-form-group">
                        <select name="repeater[<?php echo $current_version_index; ?>][geolocation-behaviour]" class="form-control referrer-custom url-custom kdwc-page-visit-operator <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Geolocation') ? 'show-selection' : ''; ?>" data-field="geolocation-behaviour">
                            <option value="is"  <?php  if(isset($rule['geolocation_behaviour']) && $rule['geolocation_behaviour'] === 'is') echo 'SELECTED'; ?>><?php _e('Is', 'kd-wc'); ?></option>
                            <option value="is-not" <?php if(isset($rule['geolocation_behaviour']) && $rule['geolocation_behaviour'] === 'is-not') echo 'SELECTED'; ?> ><?php _e('Is Not', 'kd-wc'); ?></option>
                        </select>
                    </div>


                    <div class="kdwc-autocomplete-selection-display <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Geolocation') ? 'show-selection' : ''; ?>" data-field="geolocation-selection">

                            <?php 
                                $areThereAnySelections = (isset($rule['geolocation_data']) &&
                                                      !empty($rule['geolocation_data']));

                            ?>

                            <div class="kdwc-autocomplete-fields-container <?php echo ($areThereAnySelections) ? "shown":""; ?>">
							
							 <div class="locations-description <?php echo (!$areThereAnySelections) ? "hide-field" : "";?>">
                                Targeted locations:

                            </div>

                                <?php 
                                    if ($areThereAnySelections) {
                                        $geolocation_data = utf8_decode($rule['geolocation_data']);
                                        $geolocation_data = str_replace('\\', '', $geolocation_data);
                                        ?>
                                        <input class="kdwc-autocomplete-data-field" type="hidden" name="repeater[<?php echo $current_version_index; ?>][geolocation_data]" value="<?php echo $geolocation_data; ?>" />

                                        <?php

                                        $splitted_geolocation_data = explode("^^", $geolocation_data);
                                        $i = 0;

                                        foreach ($splitted_geolocation_data as $key => $value) {
                                            if ($value != "1") {
                                                $explodedData = explode("!!", $value);

                                                // $isCountry = ($explodedData[0] == "COUNTRY");
                                                $address = $explodedData[1];

                                                ?>
                                                <div class="locationField">
                                                <span class="specific-location"><?php echo $address; ?></span>
                                                    <button class="remove-autocomplete" data-pos="<?php echo $i; ?>"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
                                                </div>
                                                <?php
                                                $i += 1;
                                            }
                                        }

                                    } else {
                                        ?>
                                        <input class="kdwc-autocomplete-data-field" type="hidden" name="repeater[<?php echo $current_version_index; ?>][geolocation_data]" />
                                        <?php
                                    }
                                ?>
                            </div>

                            <div class="select-locations-container">
                                <div class="selection-title">

                                    <div class="none-selected <?php echo ($areThereAnySelections) ? "hide-field" : ""; ?>">
                                        <i class="fa fa-map-marker map-marker-near-input" aria-hidden="true"></i>
                                        Select a location
                                    </div>    

                                    <div class="multiple-selected <?php echo (!$areThereAnySelections) ? "hide-field" : ""; ?>">
                                        <i class="fa fa-map-marker map-marker-near-input" aria-hidden="true"></i>
                                        Add another location
                                    </div>    

                                </div>

                                <div class="selection-inputs-container">
                                    
                                    <div class="kdwc-autocomplete-wrapper">
                                            <input name="kdwc-autocomplete-option" style="outline:none" checked type="radio" class="kdwc-autocomplete-opener" data-open="kdwc-autocomplete-country" />
                                            <span>Country</span>
                                        <div class="kdwc-autocomplete-container kdwc-autocomplete-country kdwc-geo-selected">
                                            <input placeholder="Country (start typing)" class="countries-autocomplete kdwc-input-autocomplete" data-symbol="COUNTRY" autocomplete="off"/>
                                        </div>
                                    </div>

                                    <!-- <div class="or-statement">- Or -</div> -->
                                    <div class="kdwc-autocomplete-wrapper">
                                            <input name="kdwc-autocomplete-option" style="outline:none"id="selected-city-radio"type="radio" class="kdwc-autocomplete-opener" data-open="select-city-container" />
                                            <span>City</span>
                                        <div class="select-city-container kdwc-autocomplete-container">
                                            <input class="autocomplete" placeholder="City (start typing)"
                                                   onFocus="" type="text">
                                        </div>
                                      

                                    </div>

                                    <!-- <div class="or-statement">- Or -</div> -->

                                    <div class="kdwc-autocomplete-wrapper">
                                            <input name="kdwc-autocomplete-option" style="outline:none" type="radio" class="kdwc-autocomplete-opener" data-open="kdwc-autocomplete-continent" />
                                            <span>Continent</span>
                                        <div class="kdwc-autocomplete-container kdwc-autocomplete-continent">
                                            <input placeholder="Continent (start typing)" class="continents-autocomplete kdwc-input-autocomplete" data-symbol="CONTINENT" autocomplete="off"/>
                                        </div>
                                    </div>
                                    
                                    <!-- <div class="or-statement">- Or -</div> -->

                                    <div class="kdwc-autocomplete-wrapper">
                                            <input name="kdwc-autocomplete-option" style="outline:none" type="radio" class="kdwc-autocomplete-opener" data-open="kdwc-autocomplete-state" />
                                            <span>State</span>
                                        <div class="kdwc-autocomplete-container kdwc-autocomplete-state">
                                            <input placeholder="State (start typing)" class="states-autocomplete kdwc-input-autocomplete" data-symbol="STATE" autocomplete="off"/>
                                        </div>
                                    </div>

                                    <!-- <div class="or-statement">- Or -</div> -->

                                    <div class="kdwc-autocomplete-wrapper">
                                            <input name="kdwc-autocomplete-option" style="outline:none" type="radio" class="kdwc-autocomplete-opener" data-open="kdwc-autocomplete-timezone" />
                                            <span>Time zone</span>
                                        <div class="kdwc-autocomplete-container kdwc-autocomplete-timezone">
                                             <select class="geo-timezone-selection" id="geo-timezone-selection">

                                                <option>Timezone (select)</option>

                                                <?php foreach($timezones as $timezone): ?>
                                                
                                                    <option value="<?php echo $timezone; ?>">
                                                        <?php _e($timezone, 'kd-wc'); ?>
                                                    </option>

                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <?php if (!isset($_COOKIE['set_geo_instructions'])): ?>
                        <div class="kdwc-form-group">
                            <div data-field="" class="geo-info-container nodisplay <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Geolocation') ? 'show-selection' : ''; ?>">
                                <div class="setgeoinstructions purple-noticebox"><span class="closeXGeo">X</span><p><?php _e('Dynamic content at the city level might not be 100% accurate.','kd-wc'); ?> <a href="https://www.kd-wc.com/geo-targeting#geoAccuracy" target="_blank"><?php _e('Read more','kd-wc') ?> >></a></p></div>
                            </div>
                        </div>
                    <?php endif; ?>
                            </div>
<!--                            <div class="geolocation-add-container">
                                <button class="gelocation-add-btn">Add location</button>
                            </div>
-->

                    </div>                    

                </div>
                <?php if($broken_trigger_types){
                    if(array_key_exists($current_version_index,$broken_trigger_types)){
                        ?>
                        <div data-field="reset-notification" class="kdwc-form-group">
                            <div class="broken-condition-notification">
                                The following condition relies on a deactivated extension. Note that updating the trigger will reset the condition settings.<br>* Missing condition: <?php echo $broken_trigger_types[$current_version_index]; ?>
                            </div>
                        </div>
                <?php }}?>

                <!-- Statistics Vals Begin -->
                <div class ="kdwc-form-group">
                    <?php
                        $statisticsCounter = 0;
                        if (isset($rule['statistics_counter'])) {
                            $statisticsCounter = $rule['statistics_counter'];
                        }
                    ?>
                    
                    <input type="hidden" name="repeater[<?php echo $current_version_index; ?>][statistics_counter]" 
                        value="<?php echo $statisticsCounter; ?>" />
                </div>
                <!-- Statistics Vals End -->

                    <div class="kdwc-form-group">
                        <div data-field="time-date-pick-start-date" class="pick-date-container <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Time-Date' && $rule["Time-Date-Schedule-Selection"] !== "Schedule-Date") ? 'show-selection' : ''; ?>" >
                            <input type="text" name="repeater[<?php echo $current_version_index; ?>][time-date-start-date]" data-field="time-date-pick-start-date" placeholder="<?php _e('Click to pick a Date', 'kd-wc'); ?>" class="form-control user-behavior-returning-custom datetimepickercustom-<?php echo $current_datetime_count; ?> kdwcdatetimepicker <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Time-Date') ? 'show-selection' : ''; ?>" <?php echo (isset($rule['time-date-start-date']) && !empty($rule['time-date-start-date']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Time-Date')) ? "value='{$rule['time-date-start-date']}'" : ''; ?>/>
                        </div>
                    </div>

                    <div class="kdwc-form-group">

                    <div class="ab-testing-custom-sessions-display kdwc-end-at-date <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Time-Date' && $rule["Time-Date-Schedule-Selection"] !== "Schedule-Date") ? 'show-selection' : ''; ?>" data-field="time-date-pick-end-date">
                        <p class="start-end-date-headers"><?php _e('Stop displaying content from:', 'kd-wc'); ?></p>
				    </div>

                </div>


                    <div class="kdwc-form-group">
                        <div data-field="time-date-pick-end-date" class="pick-date-container <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Time-Date' && $rule["Time-Date-Schedule-Selection"] !== "Schedule-Date") ? 'show-selection' : ''; ?>">
                            <input type="text" name="repeater[<?php echo $current_version_index; ?>][time-date-end-date]" data-field="time-date-pick-end-date" placeholder="<?php _e('Click to pick a Date', 'kd-wc'); ?>" class="form-control user-behavior-returning-custom kdwcdatetimepicker datetimepickercustom-<?php echo $current_datetime_count; ?> <?php echo ((isset($rule['trigger_type']) && $rule['trigger_type'] == 'Time-Date') && isset($rule['Time-Date-Schedule-Selection'])) ? 'show-selection' : ''; ?>" <?php echo (!empty($rule['time-date-end-date']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Time-Date')) ? "value='{$rule['time-date-end-date']}'" : ''; ?>
                            />
                        </div>
						
                    </div>

                    <?php if (!isset($_COOKIE['kdwc_hide_settime_instructions'])): ?>
                        <div class="kdwc-form-group">
                            <div data-field="times-dates-schedules-selections" class="set-time-info-container <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Time-Date') ? 'show-selection' : ''; ?>">
                                <div class="settimeinstructions purple-noticebox"><span class="closeX">X</span><p><?php _e('This condition relies on the local time of your site', 'kd-wc'); ?> (<?php echo  current_time('h:i A') . ((date_default_timezone_get()) ? ', ' . date_default_timezone_get() : ''); ?>) <a href="<?php echo admin_url('options-general.php') ?>" target="_blank"><?php _e('edit', 'kd-wc'); ?></a></p></div>
                            </div>
                        </div>
                    <?php endif; ?>


                <div class="kdwc-form-group">
                    <input type="text" name="repeater[<?php echo $current_version_index; ?>][ab-testing-custom-no-sessions]" data-field="ab-testing-custom" placeholder="<?php _e('Max no. of sessions', 'kd-wc'); ?>" class="form-control ab-testing-custom <?php echo (!empty($rule['ab-testing-custom-no-sessions']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'AB-Testing')) ? 'show-selection' : ''; ?>" <?php echo (!empty($rule['ab-testing-custom-no-sessions']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'AB-Testing')) ? "value='{$rule['ab-testing-custom-no-sessions']}'" : ''; ?> />
                </div>

                <div class="kdwc-form-group">
                    <select name="repeater[<?php echo $current_version_index; ?>][User-Behavior]" data-field="user-behavior-selection" class="form-control ab-testing <?php echo (!empty($rule['User-Behavior']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'User-Behavior')) ? 'show-selection' : ''; ?>">

                        <option value="Logged" <?php echo (isset($rule['User-Behavior']) && $rule['User-Behavior'] == 'Logged') ? 'SELECTED' : ''; ?> data-reset="new-visitor|common-referrers|referrer-custom|url-custom|page-selection|user-behavior-returning|user-behavior-retn-custom|user-behavior-loggedinout|user-behavior-browser-language|user-behavior-device|locked-box" data-next-field="user-behavior-logged-selection|recurrence-field" ><?php _e('User is logged in', 'kd-wc'); ?></option>
                        <option value="NewUser" <?php echo (isset($rule['User-Behavior']) && $rule['User-Behavior'] == 'NewUser') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|user-behavior-returning|user-behavior-retn-custom|user-behavior-loggedinout|user-behavior-browser-language|user-behavior-device|user-behavior-logged-selection|recurrence-field" data-next-field="new-visitor|locked-box"><?php echo (__('New Visitor', 'kd-wc') . $displayClosedFeature); ?></option>
                        <option value="Returning" <?php echo (isset($rule['User-Behavior']) && $rule['User-Behavior'] == 'Returning') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|user-behavior-returning|user-behavior-retn-custom|user-behavior-loggedinout|user-behavior-browser-language|user-behavior-device|user-behavior-logged-selection|recurrence-field"
                         data-next-field="new-visitor|user-behavior-returning|locked-box"><?php echo (__('Returning Visitor', 'kd-wc') . $displayClosedFeature); ?></option>
                        <option value="BrowserLanguage" <?php echo (isset($rule['User-Behavior']) && $rule['User-Behavior'] == 'BrowserLanguage') ? 'SELECTED' : ''; ?> data-reset="new-visitor|common-referrers|referrer-custom|url-custom|page-selection|user-behavior-returning|user-behavior-retn-custom|user-behavior-loggedinout|user-behavior-logged-selection|recurrence-field"
                         data-next-field="user-behavior-browser-language|locked-box"><?php echo (__('Browser Language', 'kd-wc') . $displayClosedFeature); ?></option>

                    </select>



                    <div class="kdwc-form-group">

                            <div data-field="user-behavior-device" class="devices-container user-behavior-device <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Device') ? 'show-selection' : ''; ?>">

                                <div class="device-container">
                                <p class="deviceinstructions">Load content on:</p>
                                    <input type="checkbox" name="repeater[<?php echo $current_version_index; ?>][user-behavior-device-mobile]"  class="form-control deviceformcontrol" <?php echo (!empty($rule['user-behavior-device-mobile']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Device') && isset($rule['user-behavior-device-mobile']) && $rule['user-behavior-device-mobile'] == "true") ? "checked" : ''; ?> />
                                    <span>Mobile</span>
                                </div>
                                <div class="device-container">
                                    <input type="checkbox" name="repeater[<?php echo $current_version_index; ?>][user-behavior-device-tablet]"  class="form-control deviceformcontrol" <?php echo (!empty($rule['user-behavior-device-tablet']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Device') && $rule['user-behavior-device-tablet'] == "true") ? "checked" : ''; ?> />
                                    <span>Tablet</span>
                                </div>
                                <div class="device-container">
                                    <input type="checkbox" name="repeater[<?php echo $current_version_index; ?>][user-behavior-device-desktop]"  class="form-control deviceformcontrol" <?php echo (!empty($rule['user-behavior-device-desktop']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Device') && $rule['user-behavior-device-desktop'] == "true") ? "checked" : ''; ?> />
                                    <span>Desktop</span>
                                </div>

                            </div>


                    </div>

                    <div class="kdwc-form-group">

                        <select name="repeater[<?php echo $current_version_index; ?>][user-behavior-logged]" class="form-control referrer-custom <?php echo (isset($rule['trigger_type']) && $rule["trigger_type"] == "User-Behavior" && isset($rule['User-Behavior']) && $rule['User-Behavior'] == "Logged") ? 'show-selection' : ''; ?>" data-field="user-behavior-logged-selection">

                            <option value="logged-in" <?php echo (isset($rule['user-behavior-logged']) && $rule['user-behavior-logged'] == 'logged-in') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|user-behavior-retn-custom"><?php _e('Yes', 'kd-wc'); ?></option>
                            <option value="logged-out" <?php echo (isset($rule['user-behavior-logged']) && $rule['user-behavior-logged'] == 'logged-out') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|user-behavior-retn-custom"><?php _e('No', 'kd-wc'); ?></option>


                        </select>

                    </div>

                    <div class="kdwc-form-group">


                    <div class="ab-testing-custom-sessions-display <?php echo (isset($rule['User-Behavior']) && $rule['User-Behavior'] == "Returning") ? 'show-selection' : ''; ?>" data-field="user-behavior-returning">
                        <p class="instructionabovefield"><?php _e('Show this content after:', 'kd-wc'); ?></p>
                    </div>

                        <select name="repeater[<?php echo $current_version_index; ?>][user-behavior-returning]" class="form-control referrer-custom <?php echo (isset($rule['User-Behavior']) && $rule['User-Behavior'] == "Returning") ? 'show-selection' : ''; ?>" data-field="user-behavior-returning">

                            <option value="first-visit" <?php echo (isset($rule['user-behavior-returning']) && $rule['user-behavior-returning'] == 'first-visit') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|user-behavior-retn-custom"><?php _e('First Visit', 'kd-wc'); ?></option>
                            <option value="second-visit" <?php echo (isset($rule['user-behavior-returning']) && $rule['user-behavior-returning'] == 'second-visit') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|user-behavior-retn-custom"><?php _e('2 Visits', 'kd-wc'); ?></option>
                            <option value="three-visit" <?php echo (isset($rule['user-behavior-returning']) && $rule['user-behavior-returning'] == 'three-visit') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|user-behavior-retn-custom"><?php _e('3 Visits', 'kd-wc'); ?></option>
                            <option value="custom" <?php echo (isset($rule['user-behavior-returning']) && $rule['user-behavior-returning'] == 'custom') ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|user-behavior-retn-custom"
                            data-next-field="user-behavior-retn-custom"><?php _e('Custom', 'kd-wc'); ?></option>

                        </select>




                        <div class="kdwc-form-group" style="margin-top: 1em;">
                            <input type="text" name="repeater[<?php echo $current_version_index; ?>][user-behavior-retn-custom]" data-field="user-behavior-retn-custom" placeholder="<?php _e('Choose no. of visits', 'kd-wc'); ?>" class="form-control user-behavior-returning-custom <?php echo (!empty($rule['user-behavior-retn-custom']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'User-Behavior')) ? 'show-selection' : ''; ?>" <?php echo (!empty($rule['user-behavior-retn-custom']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'User-Behavior')) ? "value='{$rule['user-behavior-retn-custom']}'" : ''; ?> />
                        </div>


                    </div>




                </div>
                <?php if (!isset($_COOKIE['kdwc_hide_newuser_notice'])): ?>
                    <div class="kdwc-form-group">
                        <div data-field="new-visitor" class="set-time-info-container <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'User-Behavior' && $rule['User-Behavior'] == 'NewUser') ? 'show-selection' : ''; ?>">
                            <div class="newusernotice"><span class="closeX">X</span><p><?php _e('Content will be displayed according to the number of times a given visitor has encountered the trigger.', 'kd-wc');?> <a target="_blank" href="https://www.kd-wc.com/help/documentation/new-and-returning-visitors?utm_source=Plugin&utm_medium=Micro&utm_campaign=NewReturning"><?php _e('Learn more', 'kd-wc');?></a> </p></div>
                        </div>
                    </div>
                <?php endif; ?>
                
<!--                <input type="text" class="kdwcdatetimepicker"> -->

                    <div class="kdwc-form-group">

                        <select name="repeater[<?php echo $current_version_index; ?>][user-behavior-browser-language]" class="form-control referrer-custom <?php echo (isset($rule['User-Behavior']) && $rule['User-Behavior'] == "BrowserLanguage") ? 'show-selection' : ''; ?>" data-field="user-behavior-browser-language">

                            <?php foreach ($languages as $language): ?>
                            
                                <option value="<?php echo $language[0]; ?>" <?php echo (isset($rule['user-behavior-browser-language']) && $rule['user-behavior-browser-language'] == $language[0]) ? 'SELECTED' : ''; ?> data-reset="common-referrers|referrer-custom|url-custom|page-selection|user-behavior-retn-custom"><?php _e($language[4], 'kd-wc'); ?></option>

                            <?php endforeach; ?>
                        </select>

                        <div class="kdwc-hide-me <?php echo (isset($rule['User-Behavior']) && $rule['User-Behavior'] == "BrowserLanguage") ? 'show-selection' : ''; ?>" data-field="user-behavior-browser-language">
                            <input class="kdwc-primary-lang-checkbox" type="checkbox" <?php echo (isset($rule['user-behavior-browser-language-primary-lang']) && $rule['user-behavior-browser-language-primary-lang'] == 'true') ? "checked":""; ?> name="repeater[<?php echo $current_version_index; ?>][user-behavior-browser-language-primary-lang]" value="true"> 
                            <div class="kdwc-primary-lang-container">Primary language only<a href="https://www.kd-wc.com/help/documentation/browser-language/?kdwc=primery&utm_source=Plugin&utm_medium=Instructions&utm_campaign=Browser%20language" target="_blank" class="kdwc-tip kdwc_tooltip">?</a>
                        </div></div>

                    </div>

                <div class="kdwc-form-group">
                    <select name="repeater[<?php echo $current_version_index; ?>][page]" class="form-control referrer-custom <?php echo (!empty($rule['page'])) ? 'show-selection' : ''; ?>" data-field="page-selection">
                        <option value=""><?php _e('Select page', 'kd-wc'); ?></option>
                        <?php if(!empty($available_pages)): ?>
                            <?php foreach($available_pages as $available_page): ?>
                                <option value="<?php echo $available_page->ID; ?>" <?php echo (isset($rule['page']) && $rule['page'] == $available_page->ID) ? 'SELECTED' : ''; ?>><?php echo $available_page->post_title; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <input type="hidden" name="repeater[<?php echo $current_version_index; ?>][custom]" value="url" />
                    <!-- to be added in the future for other types of custom referrers -->

                <div class="kdwc-form-group">
                    <input placeholder="<?php _e('Type a Cookie Name', 'kd-wc'); ?>" name="repeater[<?php echo $current_version_index; ?>][CookieVal]" data-symbol="PAGEURL" class="page-visit-autocomplete kdwc-input-autocomplete form-control referrer-custom <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Cookie') ? 'show-selection' : ''; ?>" novalidate data-field="cookie" autocomplete="off"
                    <?php echo (isset($rule['cookie-input']) && !empty($rule['cookie-input']) && ($rule['trigger_type'] == 'Cookie')) ? "value='{$rule['cookie-input']}'" : ''; ?>/>
                </div>

                <div class="kdwc-form-group">
                    <input placeholder="<?php _e('Type a Cookie Value', 'kd-wc'); ?>" name="repeater[<?php echo $current_version_index; ?>][CookieValueVal]" data-symbol="PAGEURL" class="page-visit-autocomplete kdwc-input-autocomplete form-control referrer-custom <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Cookie') ? 'show-selection' : ''; ?>" novalidate data-field="cookie" autocomplete="off"
                    <?php echo (isset($rule['cookie-value-input']) && !empty($rule['cookie-value-input']) && ($rule['trigger_type'] == 'Cookie')) ? "value='{$rule['cookie-value-input']}'" : ''; ?>/>
                </div>


                <div class="kdwc-form-group">
                <select name="repeater[<?php echo $current_version_index; ?>][UserIp]" class="form-control referrer-custom <?php echo (!empty($rule['ip-input'])) ? 'show-selection' : ''; ?>" data-field="user-ip">
                    <option value="is" <?php echo (isset($rule['ip-values']) && $rule['ip-values'] == 'is') ? 'SELECTED' : ''; ?>><?php _e('IP Is', 'kd-wc'); ?></option>
                    <option value="contains" <?php echo (isset($rule['ip-values']) && $rule['ip-values'] == 'contains') ? 'SELECTED' : ''; ?>><?php _e('IP Contains', 'kd-wc'); ?></option>
                    <option value="is-not" <?php echo (isset($rule['ip-values']) && $rule['ip-values'] == 'is-not') ? 'SELECTED' : ''; ?>><?php _e('IP Is Not', 'kd-wc'); ?></option>
                    <option value="not-contains" <?php echo (isset($rule['ip-values']) && $rule['ip-values'] == 'not-contains') ? 'SELECTED' : ''; ?>><?php _e('IP Does Not Contain', 'kd-wc'); ?></option>
                </select>
                </div>
                <input placeholder="<?php _e('Type an IP Address', 'kd-wc'); ?>" name="repeater[<?php echo $current_version_index; ?>][IpVal] data-symbol="PAGEURL" class="page-visit-autocomplete kdwc-input-autocomplete form-control referrer-custom <?php echo (!empty($rule['ip-input'])) ? 'show-selection' : ''; ?>" data-field="user-ip" autocomplete="off"
                <?php echo (!empty($rule['ip-input']) && ($rule['trigger_type'] == 'UserIp')) ? "value='{$rule['ip-input']}'" : ''; ?>/>


                <!-- to be added in the future for other types of custom referrers
                <!--<select name="repeater[<?php echo $current_version_index; ?>][custom]" class="form-control referrer-custom <?php echo (!empty($rule['custom'])) ? 'show-selection' : ''; ?>" data-field="referrer-custom">
                    <option value=""></option>
                    <option value="url" <?php echo (isset($rule['custom']) && $rule['custom'] == 'url') ? 'SELECTED' : ''; ?>><?php _e('Url', 'kd-wc'); ?></option>
                </select>-->
                <div class="kdwc-form-group">
                    <select name="repeater[<?php echo $current_version_index; ?>][operator]" class="form-control referrer-custom url-custom <?php echo (!empty($rule['operator']) && isset($rule['trigger_type']) && $rule['trigger_type'] == 'referrer' && isset($rule['trigger']) && $rule['trigger'] == 'custom') ? 'show-selection' : ''; ?>" data-field="referrer-custom">
                        <!--<option value=""><?php _e('Choose an operator', 'kd-wc'); ?></option>-->
                        <option value="is" <?php echo (isset($rule['operator']) && $rule['operator'] == 'is') ? 'SELECTED' : ''; ?>><?php _e('Url Is', 'kd-wc'); ?></option>
                        <option value="contains" <?php echo (isset($rule['operator']) && $rule['operator'] == 'contains') ? 'SELECTED' : ''; ?>><?php _e('Url Contains', 'kd-wc'); ?></option>
                        <option value="is-not" <?php echo (isset($rule['operator']) && $rule['operator'] == 'is-not') ? 'SELECTED' : ''; ?>><?php _e('Url Is Not', 'kd-wc'); ?></option>
                        <option value="not-containes" <?php echo (isset($rule['operator']) && $rule['operator'] == 'not-containes') ? 'SELECTED' : ''; ?>><?php _e('Url Does Not Contain', 'kd-wc'); ?></option>
                    </select>
                </div>

                <div class="kdwc-form-group">
                    <select name="repeater[<?php echo $current_version_index; ?>][advertising_platforms_option]" class="form-control advertising-platforms-option referrer-custom url-custom <?php echo (!empty($rule['advertising_platforms_option']) && isset($rule['trigger_type']) && $rule['trigger_type'] == 'advertising-platforms') ? 'show-selection' : ''; ?>" data-field="advertising-platforms-selection">
                        <option value="google" <?php echo (isset($rule['advertising_platforms_option']) && $rule['advertising_platforms_option'] == 'google') ? 'SELECTED' : ''; ?> data-reset="advertising-platforms-facebook-section" data-next-field="advertising-platforms-google-section"><?php _e('Google Ads', 'kd-wc'); ?></option>
                        <option value="facebook" <?php echo (isset($rule['advertising_platforms_option']) && $rule['advertising_platforms_option'] == 'facebook') ? 'SELECTED' : ''; ?> data-reset="advertising-platforms-google-section" data-next-field="advertising-platforms-facebook-section"><?php _e('Facebook Ads', 'kd-wc'); ?></option>
                    </select>
                </div>

                <div class="kdwc-form-group">

                    <input type="text" name="repeater[<?php echo $current_version_index; ?>][advertising_platforms]" data-field="advertising-platforms-selection" placeholder="<?php _e('Query name', 'kd-wc'); ?>" class="form-control url-custom <?php echo (!empty($rule['advertising_platforms']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'advertising-platforms')) ? 'show-selection' : ''; ?>" <?php echo (!empty($rule['advertising_platforms']) && ($rule['trigger_type'] == 'advertising-platforms')) ? "value='{$rule['advertising_platforms']}'" : ''; ?> />

                    <div class="instructions" style="<?php echo (!empty($rule['advertising_platforms']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'advertising-platforms')) ? 'display:block;' : ''; ?>" data-field="advertising-platforms-selection">
                        <p>
                            <div class="ab-testing-custom-sessions-display <?php echo (isset($rule['advertising_platforms_option']) && $rule['advertising_platforms_option'] == "google") ? 'show-selection' : ''; ?>" data-field="advertising-platforms-google-section">
                            <?php echo __('Paste the following string into the "tracking template" field (in Adwords)', 'kd-wc').':<a href="https://www.kd-wc.com/help/documentation/search-term-based-content?utm_source=Plugin&utm_medium=Instructions&utm_campaign=Advertising%20platforms&utm_term=Google" target="_blank" class="kdwc-tipGrey kdwc_tooltip">?</a>'; ?>
                            </div>
                            <div  class="ab-testing-custom-sessions-display <?php echo (isset($rule['advertising_platforms_option']) && $rule['advertising_platforms_option'] == "facebook") ? 'show-selection' : ''; ?>" data-reset="advertising-platforms-google-section" data-field="advertising-platforms-facebook-section">
                            <?php echo __('Paste this string into the "URL parameters" field (in the ad editing section on Facebook)', 'kd-wc') . ':<a href="https://www.kd-wc.com/help/documentation/advertising-platforms/?utm_source=Plugin&utm_medium=Instructions&utm_campaign=Advertising%20platforms&utm_term=Facebook" target="_blank" class="kdwc-tipGrey kdwc_tooltip">?</a>'; ?>
                            </div></p><pre class="advPlatformsCode"><code><span class="platform-symbol"><?php echo (empty($rule['advertising_platforms_option']) || (isset($rule['advertising_platforms_option']) && $rule['advertising_platforms_option'] == 'google')) ? "{lpurl}?":""; ?></span>kdwc=<b><?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == "advertising-platforms" && !empty($rule['advertising_platforms'])) ? $rule['advertising_platforms']:"name-you-choose"; ?></b></code></pre>
                    </div>

                </div>

                <div class="kdwc-form-group">
                    <input type="text" name="repeater[<?php echo $current_version_index; ?>][compare_referrer]" data-field="referrer-custom" placeholder="<?php _e('https://your-referrer.com', 'kd-wc'); ?>" class="form-control referrer-custom <?php echo (!empty($rule['compare']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'referrer')) ? 'show-selection' : ''; ?>" <?php echo (!empty($rule['compare']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'referrer')) ? "value='{$rule['compare']}'" : ''; ?> />
                    <input type="text" name="repeater[<?php echo $current_version_index; ?>][compare_url]" data-field="url-custom" placeholder="<?php _e('Name your query string', 'kd-wc'); ?>" class="form-control url-custom <?php echo (!empty($rule['compare']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'url')) ? 'show-selection' : ''; ?>" <?php echo (!empty($rule['compare']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'url')) ? "value='{$rule['compare']}'" : ''; ?> />
                    <?php /*if(!empty($rule['compare']) && ($rule['trigger_type'] == 'url')): ?>
                        <span class="" data-field="url-custom"><input type="text" onfocus="this.select();" readonly="readonly" value='<?php echo $rule['compare']; ?>' class="large-text code"></span>
                    <?php endif;*/ ?>
                    <div class="instructions" data-field="url-custom">
                        <p><?php _e('Add the following string to the end of your page URL to display the content:', 'kd-wc'); ?></p>
                        <pre class="kdwc-dynamic-link-code"><code>?kdwc=<b>your-query-string</b></code></pre>
                        <p class="query-example"><?php _e('I.e., www.url.com?kdwc=your-query-string', 'kd-wc'); ?></p>
                    </div>
                    <div class="custom-url-display instructions" <?php echo (!empty($rule['compare']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'url')) ? 'style="display:block;"' : ''; ?> data-field="custom-url-display">
                        <p><?php _e('Add the following parameter at the end of the page URL', 'kd-wc'); ?>:</p>
                        <pre class="advPlatformsCode"><code>?kdwc=<b><?php echo (!empty($rule['compare'])) ? $rule['compare'] : ''; ?></b></code></pre>
                        <p class="query-example"><?php _e('I.e., www.url.com?kdwc=your-query-string', 'kd-wc'); ?></p>
                    </div>
                </div>

                <div class="kdwc-form-group">
                    <select name="repeater[<?php echo $current_version_index; ?>][chosen-common-referrers]" class="form-control referrer-custom url-custom <?php echo (isset($rule['trigger']) && $rule['trigger'] == 'common-referrers' && !empty($rule['chosen-common-referrers'])) ? 'show-selection' : ''; ?>" data-field="common-referrers">
                        <option value="google" <?php echo (isset($rule['chosen-common-referrers']) && $rule['chosen-common-referrers'] == 'google') ? 'SELECTED' : ''; ?>><?php _e('Google', 'kd-wc'); ?></option>
                        <option value="facebook" <?php echo (isset($rule['chosen-common-referrers']) && $rule['chosen-common-referrers'] == 'facebook') ? 'SELECTED' : ''; ?>><?php _e('Facebook', 'kd-wc'); ?></option>
                    </select>
                </div>

                <!-- Page Url Begin -->

                <div class="kdwc-form-group">
                    <select name="repeater[<?php echo $current_version_index; ?>][page-url-operator]" class="form-control referrer-custom url-custom <?php echo (!empty($rule['page-url-operator']) && isset($rule['trigger_type']) && $rule['trigger_type'] == 'PageUrl') ? 'show-selection' : ''; ?>" data-field="page-url-custom">
                        <!--<option value=""><?php _e('Choose an operator', 'kd-wc'); ?></option>-->
                        <option value="is" <?php echo (isset($rule['page-url-operator']) && $rule['page-url-operator'] == 'is') ? 'SELECTED' : ''; ?>><?php _e('Url Is', 'kd-wc'); ?></option>
                        <option value="contains" <?php echo (isset($rule['page-url-operator']) && $rule['page-url-operator'] == 'contains') ? 'SELECTED' : ''; ?>><?php _e('Url Contains', 'kd-wc'); ?></option>
                        <option value="is-not" <?php echo (isset($rule['page-url-operator']) && $rule['page-url-operator'] == 'is-not') ? 'SELECTED' : ''; ?>><?php _e('Url Is Not', 'kd-wc'); ?></option>
                        <option value="not-containes" <?php echo (isset($rule['page-url-operator']) && $rule['page-url-operator'] == 'not-containes') ? 'SELECTED' : ''; ?>><?php _e('Url Does Not Contain', 'kd-wc'); ?></option>
                    </select>
                </div>

                <!-- Page UTM begin -->
                <div class="kdwc-form-group">
                    <select name="repeater[<?php echo $current_version_index; ?>][utm-type]" class="form-control referrer-custom <?php echo (isset($rule['utm-value']) && !empty($rule['utm-value']) && isset($rule['trigger_type']) && $rule['trigger_type'] == 'Utm') ? 'show-selection' : '' ?>" data-field="utm-type">
                        <!--<option value=""><?php _e('Choose an operator', 'kd-wc'); ?></option>-->
                        <option value="source" <?php echo (isset($rule['utm-type']) && $rule['utm-type'] == 'source') ? 'SELECTED' : ''; ?>><?php _e('source', 'kd-wc'); ?></option>
                        <option value="medium" <?php echo (isset($rule['utm-type']) && $rule['utm-type'] == 'medium') ? 'SELECTED' : ''; ?>><?php _e('medium', 'kd-wc'); ?></option>
                        <option value="campaign" <?php echo (isset($rule['utm-type']) && $rule['utm-type'] == 'campaign') ? 'SELECTED' : ''; ?>><?php _e('campaign', 'kd-wc'); ?></option>
                        <option value="term" <?php echo (isset($rule['utm-type']) && $rule['utm-type'] == 'term') ? 'SELECTED' : ''; ?>><?php _e('term', 'kd-wc'); ?></option>
                        <option value="content" <?php echo (isset($rule['utm-type']) && $rule['utm-type'] == 'content') ? 'SELECTED' : ''; ?>><?php _e('content', 'kd-wc'); ?></option>
                    </select>
                </div>
                <div class="kdwc-form-group">
                    <select name="repeater[<?php echo $current_version_index; ?>][utm-relation]" class="form-control referrer-custom <?php echo (isset($rule['utm-value']) && !empty($rule['utm-value']) && isset($rule['trigger_type']) && $rule['trigger_type'] == 'Utm') ? 'show-selection' : '' ?>" data-field="utm-relation">
                        <option value="is" <?php echo (isset($rule['utm-relation']) && $rule['utm-relation'] == 'is') ? 'SELECTED' : ''; ?>><?php _e('Is', 'kd-wc'); ?></option>
                        <option value="contains" <?php echo (isset($rule['utm-relation']) && $rule['utm-relation'] == 'contains') ? 'SELECTED' : ''; ?>><?php _e('Contains', 'kd-wc'); ?></option>
                        <option value="is-not" <?php echo (isset($rule['utm-relation']) && $rule['utm-relation'] == 'is-not') ? 'SELECTED' : ''; ?>><?php _e('Is Not', 'kd-wc'); ?></option>
                    </select>
                </div>
                <div class="kdwc-form-group">
                    <div class="utm-value-wrap referrer" data-field="utm-value">
                        <input class="showhide_input utm_input page-visit-autocomplete kdwc-input-autocomplete form-control referrer-custom <?php echo ((isset($rule['utm-value']) && !empty($rule['utm-value']) && isset($rule['trigger_type']) && $rule['trigger_type'] == 'Utm') ? 'show-selection' : '');?>" value="<?php if(isset($rule['utm-value']) && !empty($rule['utm-value'])) echo $rule['utm-value']; ?>" type="text" name="repeater[<?php echo $current_version_index; ?>][utm-value]"  placeholder="<?php _e('UTM tag value', 'kd-wc'); ?>" data-field="utm-value">
                        <div data-field="utm-value"  class="yellow-noticebox utm-noticebox showhide_container nodisplay <?php echo ((isset($rule['utm-value']) && !empty($rule['utm-value']) && isset($rule['trigger_type']) && $rule['trigger_type'] == 'Utm') ? 'show-selection' : '');?>" value="<?php if(isset($rule['utm-value']) && !empty($rule['utm-value'])) echo $rule['utm-value']; ?>">
                            <span class="closingX utm-closingx">X</span>
                            <?php _e('This field is case-sensitive', 'kd-wc'); ?>
                        </div>

                    </div>
                </div>




                <!-- Page UTM end -->


                <!-- If-so groups begin -->
                <div class="kdwc-form-group">
                    <div class="kdwc-form-group">
                        <select name="repeater[<?php echo $current_version_index; ?>][user-group-relation]" class="form-control referrer-custom <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Groups') ? 'show-selection' : '' ?>" data-field="user-group-relation">
                            <option value="in" <?php echo (isset($rule['user-group-relation']) && $rule['user-group-relation'] == 'in') ? 'SELECTED' : ''; ?>><?php _e('is', 'kd-wc'); ?></option>
                            <option value="out" <?php echo (isset($rule['user-group-relation']) && $rule['user-group-relation'] == 'out') ? 'SELECTED' : ''; ?>><?php _e('is not', 'kd-wc'); ?></option>
                        </select>
                    </div>
                    <div class="kdwc-form-group">
                        <select name="repeater[<?php echo $current_version_index; ?>][group-name]" class="form-control referrer-custom <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Groups') ? 'show-selection' : '' ?>" data-field="group-name">
                            <option value=""><?php if(isset($groups_list)) _e('Select an audience','kd-wc');?></option>
                            <?php
                                if(isset($groups_list) && is_array($groups_list)){
                                    foreach($groups_list as $group){
                                        echo  "<option value='{$group}' " . ((isset($rule['group-name']) && $rule['group-name'] == $group) ? 'SELECTED' : '') . ">{$group}</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <?php if (!isset($_COOKIE['kdwc_hide_no_groups_notice']) && empty($groups_list)): ?>
                    <div class="kdwc-form-group">
                        <div data-field="user-group-relation" class="noticebox-container  <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'Groups' && empty($groups_list)) ? 'show-selection' : ''; ?>">
                            <div class="nogroups_noticebox yellow-noticebox"><p><?php _e('You haven\'t created any audiences yet', 'kd-wc');?>  <a target="_blank" href="<?php echo admin_url('admin.php?page=' . EDD_IFSO_PLUGIN_GROUPS_PAGE); ?>">Create an audience</a> </p></div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- If-so groups end -->

                <!-- USER ROLES UI BEGIN !-->
                <div class="kdwc-form-group">
                    <select class="page-visit-autocomplete kdwc-input-autocomplete form-control referrer-custom <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'userRoles') ? 'show-selection' : '';?>"  name="repeater[<?php echo $current_version_index; ?>][user-role-relationship]"  data-field="user-role-relationship">
                        <option value="is" <?php if(!empty($rule['user-role-relationship']) && $rule['user-role-relationship'] == 'is-not') echo 'SELECTED'; ?> ><?php _e('Is','kd-wc');?></option>
                        <option value="is-not" <?php if(!empty($rule['user-role-relationship']) && $rule['user-role-relationship'] == 'is-not') echo 'SELECTED'; ?> ><?php _e('Is not','kd-wc');?></option>
                    </select>
                </div>

                <div class="kdwc-form-group">
                    <select class="page-visit-autocomplete kdwc-input-autocomplete form-control referrer-custom <?php echo (isset($rule['trigger_type']) && $rule['trigger_type'] == 'userRoles') ? 'show-selection' : '';?>"  name="repeater[<?php echo $current_version_index; ?>][user-role]"  data-field="user-role" >
                        <?php
                        global $wp_roles;
                        $roles = $wp_roles->roles;
                        if(is_array($roles)){
                            foreach($roles as $roleKey => $role){
                                $roleName = $role['name'];
                                $selected = (!empty($rule['user-role']) && strcasecmp($rule['user-role'],$roleKey)===0) ? 'SELECTED' : '';
                                echo "<option value='{$roleKey}' {$selected} >{$roleName}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- TRIGGER ROLES UI END !-->

                <?php do_action('kdwc_custom_conditions_ui_data_inputs',$rule,$current_version_index);?>



                <div class="kdwc-form-group">
                    <input type="text" name="repeater[<?php echo $current_version_index; ?>][page-url-compare]" data-field="page-url-custom" placeholder="<?php _e('Value', 'kd-wc'); ?>" class="form-control referrer-custom <?php echo (!empty($rule['page-url-compare']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'PageUrl')) ? 'show-selection' : ''; ?>" <?php echo (!empty($rule['page-url-compare']) && (isset($rule['trigger_type']) && $rule['trigger_type'] == 'PageUrl')) ? "value='{$rule['page-url-compare']}'" : ''; ?> />
                </div>

                <!-- Page Url End -->

                <!-- Recurrence Begin -->

                <div class="form-gorup">

                    <div class="recurrence-container <?php echo (!$is_template && isset($rule['trigger_type']) && in_array($rule['trigger_type'], $allowedTriggersForRecurrence) || (isset($rule['trigger_type']) && $rule['trigger_type'] == "User-Behavior" && isset($rule['User-Behavior']) && in_array($rule['User-Behavior'], array('LoggedIn', 'LoggedOut', 'Logged')))) ? 'show-selection' : '' ?>" data-field="recurrence-field">

                        <?php 
                        
                            $recurrenceOption = isset($rule['recurrence_option']) ? 
                                                $rule['recurrence_option'] :
                                                "none";

                            if (!trim($recurrenceOption)) { 
                                $recurrenceOption = "none";
                            }

                            /* Custom Handling */
                            $recurrenceCustomUnits = "1";
                            $recurrenceCustomValue = "day";

                            if ($recurrenceOption == "custom") {
                                $recurrenceCustomUnits = $rule['recurrence_custom_units'];
                                $recurrenceCustomValue = $rule['recurrence_custom_value'];
                            }

                            $toShowRecurrenceType = "";
                            $toShowRecurrenceType = $recurrenceOption;
                            if ($recurrenceOption == "all-session") {
                                $toShowRecurrenceType = "session";
                            }
                        ?>

                        <div class="recurrence-title">
                            <span class="recurrence-expander">+</span>
                            Recurrence (<span class="current-recurrence-type"><?php echo ucwords($toShowRecurrenceType); ?></span>)<a href="#" onclick="return false;" title="After the condition is met for the first time, the version will be displayed each time the visitor encounters the trigger. Recurrence only works when the visitor revisits the website from the same device and browser without deleting cookies." class="general-tool-tip recurrence-tip kdwc_tooltip">?</a>
                        </div>             

                        <div class="recurrence-selection">



                            <div class="recurrence-option">
                                <input type="radio" class="recurrence-radio" name="repeater[<?php echo $current_version_index; ?>][recurrence-option]" value="none" <?php echo ($recurrenceOption == "none") ? "checked" : "" ?> />
                                <div class="recurrence-option-title"><?php _e('None', 'kd-wc'); ?></div>
                            </div>

                            <div class="recurrence-option">
                                <input type="radio" class="recurrence-radio" name="repeater[<?php echo $current_version_index; ?>][recurrence-option]" value="all-session" <?php echo ($recurrenceOption == "all-session") ? "checked" : "" ?> />
                                <div class="recurrence-option-title">Single session <a href="#" onclick="return false;" title="A session ends when the visitor closes the browser (not the tab)" class="general-tool-tip kdwc_tooltip">?</a></div>
                            </div>

                            <div class="recurrence-option">
                                <input type="radio" class="recurrence-radio" name="repeater[<?php echo $current_version_index; ?>][recurrence-option]" value="always" <?php echo ($recurrenceOption == "always") ? "checked" : "" ?> />
                                <div class="recurrence-option-title"><?php _e('Always', 'kd-wc'); ?> </div>
                            </div>

                            <div class="recurrence-option">
                                <input type="radio" class="recurrence-radio recurrence-custom-radio" name="repeater[<?php echo $current_version_index; ?>][recurrence-option]" data-next-field="recurrence-custom-selection" value="custom" <?php echo ($recurrenceOption == "custom") ? "checked" : "" ?> />

                                <div class="recurrence-option-title">
                                    Custom
                                    <div class="recurrence-custom-selection-container <?php echo ($recurrenceOption == "custom") ? "show-selection" : ""; ?>" data-field="recurrence-custom-selection">
                                        <select name="repeater[<?php echo $current_version_index; ?>][recurrence-custom-value]">
                                            <?php for ($i = 1; $i <= 52; $i++) { ?>
                                                <option value="<?php echo $i; ?>" <?php echo ($i == $recurrenceCustomValue) ? "selected" : ""; ?>><?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>

                                        <select name="repeater[<?php echo $current_version_index; ?>][recurrence-custom-units]">
                                            <option value="day" <?php echo ($recurrenceCustomUnits == "day") ? "selected" : ""; ?>><?php _e('Days', 'kd-wc'); ?></option>
                                            <option value="week" <?php echo ($recurrenceCustomUnits == "week") ? "selected" : ""; ?>><?php _e('Weeks', 'kd-wc'); ?></option>
                                            <option value="month" <?php echo ($recurrenceCustomUnits == "month") ? "selected" : ""; ?>><?php _e('Months', 'kd-wc'); ?></option>
                                            <option value="minute" <?php echo ($recurrenceCustomUnits == "minute") ? "selected" : ""; ?>><?php _e('Minutes', 'kd-wc'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="recurrence-override">
                                <input type="checkbox" class="recurrence-checkbox" name="repeater[<?php echo $current_version_index; ?>][recurrence-override]" <?php echo (isset($rule['recurrence-override']) && $rule['recurrence-override'] == true) ? 'checked' : '';?> >
                                <label>Recurrence override<a href="#" onclick="return false;" title="If the condition is met, this version will be displayed despite recurrence being active on another" class="general-tool-tip kdwc_tooltip">?</a></label>
                            </div>

                        </div>

                    </div>
                </div>

                <!-- Recurrence End -->
                <!-- Groups start -->
                <div class="<?php echo (!$is_template && isset($rule['trigger_type']) && !empty($rule['trigger_type']) && !in_array($rule['trigger_type'], $notAllowedTriggersForGroups)) ? 'show-selection' : ''?> kdwc-form-group kdwc-groups-container" data-field="groups-field">
                        <div class="groups-title">
                            <span class="groups-expander">+</span>
                            Audiences <a href="#" onclick="return false;" title="Segment users into audiences based on the dynamic version that was displayed to them; Set an “Audiences” condition to trigger dynamic content to users belonging to that audience." class="general-tool-tip recurrence-tip kdwc_tooltip">?</a>
                        </div>

                        <div class="groups-selection">
                            <p>
                                Add or remove users from the following audiences when the version is displayed.
                                 <a target="_blank" href="https://www.kd-wc.com/help/documentation/segments?utm_source=Plugin&amp;utm_medium=Micro&amp;utm_campaign=groups"><?php _e('Learn More','kd-wc'); ?></a>
                            </p>
                            <?php if (is_array($groups_list) && !empty($groups_list)): ?>
                            <div class="add_to_groups">
                                <p class="group_action_subtitle">
                                    Add to audiences:
                                </p>
                                <div class="groups_to_add">
                                <?php
                                    if(isset($groups_list) && is_array($groups_list)){
                                        foreach($groups_list as $group){
                                            $check = (isset($rule['add_to_group']) && in_array($group,$rule['add_to_group'])) ? 'CHECKED' : '';
                                            echo '<input name="repeater['. $current_version_index .'][add_to_group][]" ' . $check . ' class="group_name_checkbox" type="checkbox" value="' . $group . '"><label>'. $group .'</label><br>';
                                        }
                                    }
                                ?>
                                </div>
                            </div>
                            <div class="remove_from_groups">
                                <p class="group_action_subtitle">
                                    Remove from audiences:
                                </p>
                                <div class="groups_to_remove">
                                <?php
                                    if(isset($groups_list) && is_array($groups_list)){
                                        foreach($groups_list as $group){
                                            $check = (isset($rule['remove_from_group']) && in_array($group,$rule['remove_from_group'])) ? 'CHECKED' : '';
                                            echo '<input name="repeater['. $current_version_index .'][remove_from_group][]" ' . $check . ' class="group_name_checkbox" type="checkbox" value="' . $group . '"><label>'. $group .'</label><br>';
                                        }
                                    }
                                ?>
                                </div>
                            </div>
                            <?php else: ?>
                                <p class="no_groups_notice">You haven't created any audiences</p>
                            <?php endif;?>
                            <p></p><a target="_blank" href="<?php echo admin_url('admin.php?page=' . EDD_IFSO_PLUGIN_GROUPS_PAGE); ?>"><span class="dashicons dashicons-edit"></span> Manage audiences</a></p>
                        </div>
                </div>
                <!-- Groups end -->


            </div>



            <div class="col-md-9">
                <?php if($is_template): ?>
                    <div class="repeater-editor-wrap"></div>
                <?php else: ?>
                    <?php $version_content = (!empty($data_versions[$index])) ? $data_versions[$index] : ''; ?>
                    <?php wp_editor( $version_content, 'repeatable_editor_content'.$current_version_index, array(
                        'wpautop'       => true,
                        'textarea_name' => 'repeater['.$current_version_index.'][repeatable_editor_content]',
                        'textarea_rows' => 10,
                    ) ); ?>
                <?php endif; ?>
            </div>

            <!-- begin testing mode section -->
 
            <?php if ( ( ($is_template       &&
                         !empty($data_rules) && 
                         isset($data_rules[0]['testing-mode'])) ||
                      (isset($rule['testing-mode']) && (!is_numeric($rule['testing-mode']))) ||
                      ( isset($rule['testing-mode'])      && 
                        is_numeric($rule['testing-mode']) &&
                        strval($rule['testing-mode']-2) != strval($current_version_index))) ||
                      empty($data_rules)): ?>
 
                <div class="col-md-1 kdwc-tm-section">
 
                    <a href="#" onclick="return false;" title="See how this version will look on your website. Press Update/ Publish for changes to take effect." class="tm-tip kdwc_tooltip">?</a>
                    <span class="text"><?php _e('Testing Mode', 'kd-wc'); ?> </span>
                    <div  class="kdwc-tm circle"></div>
 
                </div>
 
            <?php else: ?>
 
                <div class="col-md-1 kdwc-tm-section">
 
                    <a href="#" onclick="return false;" title="Testing mode allows you to see how this version will look on your website (activation/ deactivation will only occur when the trigger has been updated)." class="tm-tip">?</a>
                    <span class="text"><?php _e('Testing Mode', 'kd-wc'); ?> </span>
                    <div  class="kdwc-tm circle circle-active"></div>
 
                </div>
 
            <?php endif; ?>
 
            <!-- end testing mode section -->

        </div>

            <?php if ( ($is_template &&
                        !empty($data_rules) && isset($data_rules[0]['testing-mode']) &&
                        is_numeric($data_rules[0]['testing-mode'])) ||
                      (isset($rule['testing-mode']) &&
                        is_numeric($rule['testing-mode']) &&
                      strval($rule['testing-mode']-2) != strval($current_version_index))): ?>
                <div class="kdwc-tm-overlay">
                <span class="text"><?php _e('Testing Mode', 'kd-wc'); ?> <br/><br/> <span class="cancel-freezemode"> <?php _e('Another version is forced to be displayed', 'kd-wc'); ?></span> </span>
                </div>
        <?php endif; ?>

        <?php if ((isset($rule['freeze-mode']) &&
                  $rule['freeze-mode'] == "true")): ?>
            <div class="kdwc-freeze-overlay kdwc_tooltip">
                <span class="text"><?php _e('Version is inactive', 'kd-wc'); ?></span>
            </div>
        <?php endif; ?>

    </li> <!-- end of rule-item -->
<?php
}
?>

<?php if (!$isLicenseValid): ?>


<?php echo '<div id="versionbox_target" class="'.(($isGeoLicenseValid) ? 'nodisplay' : '').'"></div>' ?>

<?php endif; ?>

<!-- KDWC Modal for first use : Begin -->
<?php 
    if ( !isset($_COOKIE['kdwc_is_first_use']) ):
        setcookie('kdwc_is_first_use', true, time() + (10 * 365 * 24 * 60 * 60), "/");
?>
<div id="kdwc-modal-first-use">
    <a href="<?php echo plugin_dir_url( __FILE__ ); ?>/assets/images/how-it-works.gif" data-group="kdwc-first-use-images" class="kdwc-first-use-images"></a>
  
</div>
<?php endif; ?>
<!-- KDWC Modal for first use : End -->

<!-- KDWC Modal for caching plugins interop : Begin -->
<?php
$current_post_id = get_the_ID();
$published = (get_post_status( $current_post_id ) == 'publish' );
$user = wp_get_current_user();
if(isset($user->ID) && 0!== $user->ID)     //If user is logged in(even though they should be at this point)
    $user_name = get_user_meta($user->ID)['first_name'][0];

if (!isset($_COOKIE['kdwc_hide_caching_modal']) && defined('WP_CACHE') && WP_CACHE && $published):    //If "Never Show Again" wasn't pressed, the cache is on and the trigger is published
    ?>
    <div class="kdwc-modal" id="kdwc-modal-caching-compat">
        <div class="content">
            <p style='margin-left:-10px;'>Good News <?php echo (!empty($user_name)) ? $user_name : ''; ?>!</p>
            <p>Starting version 1.5.0 Kd-Wc is compatible with page caching! Dynamic content can be loaded using Ajax after the page loads from the cache.</p>
			<p>Since you are using cache on your website, we recommend enabling the Ajax loading by default for all the triggers.<p>
			<p><a href="<?php echo admin_url( 'admin.php?page=' . EDD_IFSO_PLUGIN_SETTINGS_PAGE ); ?>" target='_blank'>Click here </a>to visit the plugin settings and enable the option.</p>
        </div>
        <div class="buttons">
            <button type="button" class="button neverAgain">Never Show Again</button>
            <button type="button" class="button cls">Close</button>
        </div>
        <img class="guyImg" src="<?php echo IFSO_PLUGIN_DIR_URL . 'admin/images/Help.png';?>">

    </div>
<?php endif; ?>
<!-- IfSoIfSo Modal for caching plugins interop : End -->

<div class="admin-trigger-wrap"><!--wrap-->
    <div id="repeater-template">
        <?php get_rule_item(null, array(), true); ?>
    </div>

    <!-- repeater -->
    <div class="repeater">
        <!--
            The value given to the data-repeater-list attribute will be used as the
            base of rewritten name attributes.  In this example, the first
            data-repeater-item's name attribute would become group-a[0][text-input],
            and the second data-repeater-item would become group-a[1][text-input]
        -->
        <?php // echo "<pre>".print_r($data_rules, true)."</pre>"; ?>
        <?php // echo "<pre>".print_r($data_versions, true)."</pre>"; ?>
        
        <div id="kdwc-versions-container">
            <ul class="kdwc-versions-sortable">
                <?php if(!empty($data_rules)): ?>
                    <?php foreach($data_rules as $index => $rule): ?>
                        <?php get_rule_item($index, $rule); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php get_rule_item(0); ?>
                <?php endif; ?>
            </ul>
        </div>

        <input type="hidden" id="post_id" value="<?php echo $post->ID; ?>" />
        
        <!-- Default Statistics Count -->
        <?php
            $data_default_statistics_count = 0;

            if ( isset($data_default_metadata['statistics_count']) ) {
                $data_default_statistics_count = $data_default_metadata['statistics_count'];
            }
        ?>

        <input type="hidden" id="tm-input" name="testing-mode" value="<?php echo (!empty($data_rules) && isset($data_rules[0]['testing-mode'])) ? $data_rules[0]['testing-mode'] : ''; ?>" />
        <button type="button" id="reapeater-add" class="btn-add"><i class="fa fa-plus highlighted" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<?php _e('Add Another Version', 'kd-wc'); ?></button>
        <?php if(!isset($_COOKIE['hide_too_many_conditions_notif'])):?>
            <div class="too-many-conditions-notif yellow-noticebox nodisplay">
                <span class="closingX">X</span>
                <?php _e("Kd-Wc allows you to create as many versions as you want. Depending on your server configuration, some of the versions may disappear after updating a trigger with a lot of versions. In case you'll face this issue, you can fix it by simply increasing the \"max_input_vars\" value in your php.ini.",'kd-wc');?> <a href="https://www.kd-wc.com/?post_type=faq-items&p=32007" target="_blank"><?php _e('Learn More','kd-wc');?></a>.
            </div>
        <?php endif; ?>
    </div>

    <div class="rule-item default-repeater-item">
        <div class="row rule-wrap default-rule-wrap">
            <div class="col-md-3">
                <h3>
                    <!--<span class="version-count">1</span>-->
                    <?php _e('Default Content', 'kd-wc'); ?>
                </h3>
                <p><?php _e('Default content appears when none of the conditions are met.', 'kd-wc'); ?><br><br> <?php _e('Leave this blank if you', 'kd-wc'); ?> <strong><?php _e('do not', 'kd-wc'); ?></strong> <?php _e('want to display anything by default', 'kd-wc'); ?>.</p>
            </div>

            <div class="col-md-9">
                <?php 
                $default_value = (!empty($data_default)) ? $data_default : '';
                wp_editor( $default_value, 'trigger-default', array(
                    'wpautop'       => true,
                    'textarea_name' => 'kdwc_default',
                    'textarea_rows' => 10,
                ));
                ?>
            </div>

           

        </div>
        
        <?php if (!empty($data_rules) && isset($data_rules[0]['testing-mode']) &&
                  is_numeric($data_rules[0]['testing-mode']) &&
                  strval($data_rules[0]['testing-mode']) != "0"): ?>
                <div class="kdwc-tm-overlay">
                    <span class="text"><?php _e('Testing Mode', 'kd-wc'); ?> <br/><br/> <span class="cancel-freezemode"> <?php _e('Another version is forced to be displayed', 'kd-wc'); ?></span> </span>
                </div>
        <?php endif; ?>

    </div>

    <div class="container submit-bottom-wrap">
        <div class="row">
            <div class="col-xs-12">
                <?php $submit_text = ($post_status == 'publish') ? __('Update', 'kd-wc') : __('Publish', 'kd-wc'); ?>
                <?php if($post_status != 'publish'): ?>
                <p class="reminder">
                    <b class="highlighted"><?php _e('Finished', 'kd-wc'); ?>?</b>
                    <?php _e('Press publish to receive a shortcode to use on your website.', 'kd-wc'); ?>
                    &nbsp;<!--<span class="submit-btn-wrap"><?php submit_button( $submit_text, 'primary', 'submit', false, NULL ); ?></span>-->
                </p>

                <?php endif; /*else: ?>
                <span class="submit-btn-wrap"><?php submit_button( $submit_text, 'primary', 'submit', false, NULL ); ?></span>
                <?php endif; */?>
            </div>
        </div>
    </div>
</div><!-- /.wrap -->
<?php if($broken_trigger_types):?>
<script>
    var broken_types_str = '<?php echo json_encode($broken_trigger_types);?>';
    var broken_types = JSON.parse(broken_types_str);
    alert('One of this trigger’s version has a missing condition. The issue is likely to happen due to a deactivated extension.\nNOTE! Clicking “Update” will delete the settings of the missing condition/s!');
</script>
<?php endif; ?>

