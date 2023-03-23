<?php

namespace App\Console\Commands;

use App\Models\City;
use Illuminate\Support\Str;
use voku\helper\HtmlDomParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use \ForceUTF8\Encoding;

class ImportCitiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import cities data from e-obce.sk';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $map = array(
            'è' => 'č',
            '' => 'š',
            '¾' => 'ľ',
            'ò' => 'ň',
            '¼' => 'ľ',
            '' => 'ľ',
            '' => 'ť',
            'ť' => 'š',

        );

        $html = HtmlDomParser::file_get_html('https://www.e-obce.sk/kraj/NR.html');
        $linkOkres = $html->find('a')->find('.okreslink');
        foreach ($linkOkres as $okres) {

            $okresUrl = HtmlDomParser::file_get_html($okres->href);
            $townUrls = $okresUrl->find('td[align="left"]')->find('a');

            foreach ($townUrls as $townUrl) {
                $nazovObce = strtr($townUrl->plaintext, $map);
                $cityWeb = HtmlDomParser::file_get_html($townUrl->href);

                //----------------------Email and address
                $token = "Email";
                $tables = $cityWeb->find('table');
                $emailSearch = [];
                $adresaSearch = [];
                foreach ($tables as $table) {
                    foreach ($table->find('tr') as $row) {
                        if (strpos($row->find('div', 0)->plaintext, $token) !== false) {
                            $emailSearch[] = $row->find('td')->find('a')->plaintext;
                            $adresaSearch[] = $row->find('td:first-of-type')->plaintext;
                        }
                    }
                }

                if (!isset($emailSearch[0][0])) {
                    $email = "Not added";
                } else {
                    $email = $emailSearch[0][0];
                }

                if (!isset($adresaSearch[0][0])) {
                    $address = "Not added";
                } else {
                    $address = strtr($adresaSearch[0][0], $map);
                }

                //---------------------Web
                $token = "Web";
                $tables = $cityWeb->find('table');
                $webSearch = [];
                foreach ($tables as $table) {
                    foreach ($table->find('tr') as $row) {
                        if (strpos($row->find('div', 0)->plaintext, $token) !== false) {
                            $webSearch[] = $row->find('td')->find('a')->plaintext;
                        }
                    }
                }

                if (!isset($webSearch[0][0])) {
                    $web = "Not added";
                } else {
                    $web = $webSearch[0][0];
                }


                //---------------------Mayor_name
                $token = "Starosta";
                $tables = $cityWeb->find('table');
                $starostaSearch = [];
                foreach ($tables as $table) {
                    foreach ($table->find('tr') as $row) {
                        if (strpos($row->find('td', 0)->plaintext, $token) !== false) {
                            $starostaSearch[] = $row->find('td:last-of-type')->plaintext;
                        }
                    }
                }

                if (!isset($starostaSearch[0][0])) {
                    $starosta = "Not added";
                } else {
                    $starosta = strtr($starostaSearch[0][0], $map);
                }

                //---------------------Tel. number
                $token = "Tel:";
                $tables = $cityWeb->find('table');
                $PhoneSearch = [];
                foreach ($tables as $table) {
                    foreach ($table->find('tr') as $row) {
                        if (strpos($row->find('div', 0)->plaintext, $token) !== false) {
                            $PhoneSearch[] = $row->find('table[border="0"]')->find("td")->plaintext;
                        }
                    }
                }
                if (!isset($PhoneSearch[0][0])) {
                    $phone = "Not added";
                } else {
                    $phone = $PhoneSearch[0][0];
                }

                //---------------------Fax
                $token = "Fax:";
                $tables = $cityWeb->find('table');
                $FaxSearch = [];
                foreach ($tables as $table) {
                    foreach ($table->find('tr') as $row) {
                        if (strpos($row->find('div', 0)->plaintext, $token) !== false) {
                            $FaxSearch[] = $row->find('td:last-of-type')->plaintext;
                        }
                    }
                }
                if (!isset($FaxSearch[0][0])) {
                    $fax = "Not added";
                } else {
                    $fax = $FaxSearch[0][0];
                }
                //---------------------Image (coat of arms)
                $token = "Obecný úrad";
                $tables = $cityWeb->find('table');
                $imageSearch = [];
                foreach ($tables as $table) {
                    foreach ($table->find('tr') as $row) {
                        if (strpos($row->find('strong', 0)->plaintext, $token) !== false) {
                            $imageSearch[] = $row->find('td')->find('img')->src;
                        }
                    }
                }
                if (!isset($imageSearch[0][8])) {
                    $token = "Mestský úrad";
                    foreach ($tables as $table) {
                        foreach ($table->find('tr') as $row) {
                            if (strpos($row->find('strong', 0)->plaintext, $token) !== false) {
                                $imageSearch[] = $row->find('td')->find('img')->src;
                            }
                        }
                    }
                }

                $image = $imageSearch[0][8];

                Storage::disk('public')->put('erby/' . Str::slug($nazovObce) . '-erb.gif', file_get_contents($image));
                $imagePath = 'erby/' . Str::slug($nazovObce) . '-erb.gif';


                // AIzaSyCNAZGuhXt6e_lhoWweMHF-2VImJJm4u3k
                $city = City::updateOrCreate(
                    ['name' => $nazovObce],
                    [
                        'Mayor_name' => $starosta,
                        'City_hall_address' => $address,
                        'Phone' => $phone,
                        'Fax' => $fax,
                        'E_mail' => $email,
                        'Web_address' => $web,
                        'Image' => $imagePath,
                    ]
                );
            }
        }


        $this->info('Success');
    }
}
