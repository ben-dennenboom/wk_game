<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScrapeFixturesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //https://www.google.com/search?q=world+cup+qatar+fixtures+2022&rlz=1C5CHFA_enBE919BE919&sxsrf=ALiCzsY3Y_Rl1FyBtK8WnChcuVP1btPQhg%3A1663273551262&ei=T4ojY86lD_WE9u8P3Mq84AQ&oq=world+cup+qatar+fixtures+&gs_lcp=Cgxnd3Mtd2l6LXNlcnAQAxgAMgUIABCABDIFCAAQgAQyBQgAEIAEMgYIABAeEBYyBggAEB4QFjIGCAAQHhAWMgYIABAeEBYyBggAEB4QFjIGCAAQHhAWMgYIABAeEBY6CggAEEcQ1gQQsAM6BAgjECc6BggjECcQEzoFCAAQkQI6CwgAEIAEELEDEIMBOggIABCxAxCDAToLCC4QgAQQsQMQgwE6EQguEIAEELEDEIMBEMcBENEDOhMILhCABBCHAhCxAxCDARDUAhAUOgoIABCABBCHAhAUOggILhCABBCxAzoKCAAQsQMQgwEQQzoNCC4QsQMQgwEQ1AIQQzoLCC4QsQMQgwEQ1AI6DgguEIAEELEDEMcBEK8BOg4ILhCABBCxAxCDARDUAjoICAAQgAQQsQNKBAhBGABKBAhGGABQwwpYkiFgjC1oBHAAeACAAYIBiAGPA5IBAzIuMpgBAKABAcgBCMABAQ&sclient=gws-wiz-serp#sie=lg;/m/0fp_8fm;2;/m/030q7;mt;fp;1;;;
        return 0;
    }
}
