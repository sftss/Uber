<?php
namespace App\Http\Controllers;
 
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;
 
class BotManController extends Controller
{
 
 public function handle()
 {
 $botman = app('botman');
 
 $botman->hears('{message}', function($botman, $message) {
    if ($message == 'Bonjour' || $message == 'bonjour') {
        $this-> askQuestion($botman);
        }
        
        else{
        $botman->reply("Commencez la conversasion par un Bonjour");
        }
        
        });
        
        $botman->listen();
        }
        
        /**
        * Place your BotMan logic here.
        */
        public function askQuestion($botman)
        {
        $botman->ask('Bonjour, comment puis-je vous aider aujourd’hui ?',
       function(Answer $answer) {
        
        $question = $answer->getText();
        
        $this->say('Votre question est bien la suivante : '.$question);
        });
        }
       }
       
