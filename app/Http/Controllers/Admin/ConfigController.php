<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateConfigRequest;
use App\Models\Admin\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laracasts\Flash\Flash;

class ConfigController extends Controller
{
    public function edit(){
        $config = Config::first();
        if (empty($config)){
            return redirect(route('admin.home'));
        }
        return view('admin.config.edit', compact('config'));
    }

    public function update(UpdateConfigRequest $request){
        $config = Config::first();
        if (empty($config)){
            return redirect(route('admin.home'));
        }
        $input = $request->all();
        $config['text_code'] = $input['text_code'];
        $config['save_path'] = trim($input['save_path']);
        $config['log_search'] = (isset($input['log_search']) && $input['log_search']) == 1 ? 1 : 0;
        $config['log_view'] = (isset($input['log_view']) && $input['log_view']) == 1 ? 1 : 0;
        $config['log_download'] = (isset($input['log_download']) && $input['log_download']) == 1 ? 1 : 0;
        $config['log_comment'] = (isset($input['log_comment']) && $input['log_comment']) == 1 ? 1 : 0;

        try {
            $config->save();
            if (!in_array($config['save_path'],['attachments', '/attachments', 'attachments/', '/attachments/'])){
                Storage::makeDirectory($config['save_path']);
                Flash::success('Lưu thành công');
                return redirect(route('admin.config.edit'));
            }
        }
        catch (\Exception $e){
            Flash::success('Có lỗi xảy ra. Vui lòng thử lại');
            return redirect()->back()->withInput($input);
        }
    }
}
