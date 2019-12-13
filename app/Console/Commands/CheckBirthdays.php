<?php

namespace App\Console\Commands;

use App\Member;
use Illuminate\Console\Command;
use \Nexmo\Client\Credentials\Basic;
use \Nexmo\Client;

class CheckBirthdays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for birthdays and sends texts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $members = Member::all();
        $birthdayToday = [];
        foreach ($members as $member){
            $check = substr($member->birthday,0, -5);
            $month = date('m');
            $day = date('d');
            $now = $month."/".$day;
            if($now == $check){
                array_push($birthdayToday,$member);
            }
        }
        if(!empty($birthdayToday)){
            $message = "";
            foreach($birthdayToday as $person){
                $date = new \DateTime($person->birthday);
                $now = new \DateTime();
                $age = $now->diff($date);
                $message .= ' '.$person->name.' turned '.$age->y.' today! ';
            }
            $message .= ' Wish them a Happy Birthday!';
            $this->sendMessage($message);
        }
    }

    /**
     * @param string $message
     * @throws \Nexmo\Client\Exception\Exception
     * @throws \Nexmo\Client\Exception\Request
     * @throws \Nexmo\Client\Exception\Server
     */
    public function sendMessage($message){
        $numbers = [
            "12182805085", // Me
            "15034386036", // Alia
            "14063811446", // Mom
            "12182807450", // Suzy
            "16125996516", // Aunt Elena
            "16126166965", // Aunt Liza
            "15035511775", // Aunt Domnica
            "15038812338", // Frosia
            "12182805123", // Alosha
            "16122697471", // Aunt Anastasia
            "15039496070", // Aunt Hina
            "19079829787", // Aunt Vassa
            "15038052790", // Aunt Vivea
            "19072991404", // Aunt Walle
            "15035807273", // Paul
            "15032015866", // Uncle Mike
            "15035801961", // Uncle Pete
            "17632386169", // Uncle Savva
            "16126553822", // Uncle Steve
            "12184310214", // Vanner Banner
            "19073398512", // Aunt Alla
            "12185210677", // Julie
            "15039848010", // Seanna
            "16129685103", // Aunt Suzanna
            "16127101558", // Aunt Zina
            "12182808511", // Baba
            "12182802262", // Deda
            "12182800489", // Emela
        ];
        $basic  = new Basic(env("NEXMO_API_KEY"), env("NEXMO_API_SECRET"));
        $client = new Client($basic);
        foreach($numbers as $number) {
            $client->message()->send([
                'to' => $number,
                'from' => '12109619101',
                'text' => $message
            ]);
        }
    }
}
