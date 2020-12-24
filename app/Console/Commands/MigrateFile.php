<?php

namespace App\Console\Commands;

use App\Models\Admin\Attachment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class MigrateFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:MigrateFile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * Execute the console command.
     *
     * @return int
     */
//    public function handle()
//    {
//        $attachments = Attachment::where('store_name', 'AAA')->get();
//        $count_success = 0;
//        $count_failed = 0;
//        $not_exist = 0;
//        foreach ($attachments as $attachment){
//            $storage = Storage::disk('public');
//            $sub_folder = $attachment->file_path;
//            $current_filename = str_replace('/', "\\", $sub_folder . $attachment->file_name);
//            if (\File::exists($this->replace_path($current_filename))){
//                try {
//                    $uuid = Uuid::uuid4();
//                    $ext = strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION));
////                    $size = $storage->size($current_filename);
//                    $size = \File::size($this->replace_path($current_filename));
//                    $dest = $sub_folder . $uuid . '.' . $ext;
//
//
//                    $attachment->store_name = $uuid;
//                    $attachment->size = $size;
//                    $attachment->extension = $ext;
//                    $attachment->file_path = $dest;
//                    rename($this->replace_path($current_filename), $this->replace_path($dest));
////                    $storage->move($current_filename, $attachment->file_path);
//                    $attachment->save();
//                    $count_success++;
//                }
//                catch (\Exception $e){
//                    print_r('Failed: ' . $current_filename . "(".$e->getMessage().")\n");
//                    $count_failed ++;
//                }
//            }
//            else {
//                print_r('File not exist: ' . $current_filename . "\n");
//                $not_exist++;
//            }
//        }
//        print_r("Success: " . $count_success);
//        print_r("\n");
//        print_r("Failed: " . $count_failed);
//        print_r("\n");
//        print_r("Not found: " . $not_exist);
//    }
//
//    public function replace_path($path){
//        return str_replace("/", "\\", storage_path('app/public/'.$path));
//    }

    public function handle(){
        $folders = ['di2020', 'di2019', 'den2020', 'den2019'];
        $found = 0;
        $not_found = 0;
        foreach ($folders as $folder){
//            $files = Storage::disk('public')->files("attachments/" . $folder . "/");
            $files = scandir('D:\projects\outsource\thainguyen\storage\app\public\attachments\\'. $folder);
            foreach ($files as $file){
                if ($file != ""){
                    $fileName = str_replace("attachments/" . $folder . "/", "", $file);
                    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $name = trim(str_replace("." . $ext, "", $fileName));
                    if ($name != "" && !$this->isValidUuid($name)){
                        $attachment = Attachment::where('file_name', 'LIKE' ,$name."%")->where("store_name", "AAA");
                        dd($attachment->getBindings());
//                    if ($attachment){
//                        dd($name);
////                        $uuid = Uuid::uuid4();
////                        $attachment->store_name = $uuid;
////                        $attachment->size = \File::size(storage_path('app/public/' . $file));
////                        $attachment->extension = $ext;
////                        $attachment->file_path = "attachments/" . $folder . "/" . $uuid . "." . $ext;
////                        rename(storage_path('app/public/' . $file), storage_path('app/public/' . "attachments/" . $folder . "/" . $uuid . "." . $ext));
////                        $attachment->save();
//                        $found++;
//                    }
//                    else {
//                        $not_found ++;
//                    }
                    }
                }
            }
        }
        print_r($found);
        print_r("\n");
        print_r($not_found);
    }

    public function isValidUuid( $uuid ) {

        if (!is_string($uuid) || (preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $uuid) !== 1)) {
            return false;
        }

        return true;
    }

    function strigToBinary($string)
    {
        $characters = str_split($string);

        $binary = [];
        foreach ($characters as $character) {
            $data = unpack('H*', $character);
            $binary[] = base_convert($data[1], 16, 2);
        }

        return implode(' ', $binary);
    }
}
