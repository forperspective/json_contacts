<?php

namespace App\Jobs;

use App\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use File;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateContactQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path)
    {
        //
        $this->path=$path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $items = [];
        $parser = new \JsonCollectionParser\Parser();
        $parser->parse($this->path, function (array $item) use (&$items) {
            $names=str_replace('[','',str_replace(']','',$item['names']));
            $array_names=explode(',',str_replace('"','',$names));
            $hits=str_replace('[','',str_replace(']','',$item['hits']));
            $hist_array=explode(',',str_replace('"','',$hits));

            $current_lang=$translate->getLastDetectedSource();
            $translate = new GoogleTranslate('ar','en',[
                'timeout' => 1000
            ]);

            if($current_lang=='ar'){
                $translate->setTarget('ar');
                foreach($array_names as $key=>$name) {
                    Contact::create([
                        'names'=>$translate->translate($name),
                        'hits'=>$hist_array[$key],
                        'lang'=>"ar"
                    ]);
                }
            }else{
                $translate->setTarget('en');
                foreach($array_names as $key=>$name) {
                    Contact::create([
                        'names'=>$translate->translate($name),
                        'hits'=>$hist_array[$key],
                        'lang'=>"en"
                    ]);
                }
            }

        });

    }
}
