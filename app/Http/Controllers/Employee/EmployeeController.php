<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use \Intervention\Image\Facades\Image;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        Employee::create([
            'name'          =>  $request->name,
            'national_id'   =>  $request->national_id,
            'video_path'    =>  self::storeVideoFile($request->file('video_file')),
            'image_path'    =>  self::storeImageFile($request->file('image_file')),
        ]);
        return response()->json(['employee created success',201]);
    }
    /**
     * Store image file to storage.
     *
     * @param  string  $file
     * @return string
     */
    public static function storeImageFile($file){
        $fileName = time() . '.' . $file->extension();
            $destinationPath = storage_path('app/public/images');
            $img = Image::make($file->path());

            //create storage path if not exist
            File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);

            //check original dimensions
            if( ($img->width()*$img->height()) > (300*300) )
                $img->resize(300, 300);
            $img->insert(public_path('logo.png','bottom-right',10,10))
                ->save($destinationPath . '/' . $fileName);
        return "storage/images/$fileName";
    }

    /**
     * Store Video file to storage.
     *
     * @param  string  $file
     * @return string
     */
    public static function storeVideoFile($file){
        $fileName = time() . '.' . $file->extension();
            $destinationPath = storage_path('app/public/videos');
            $file->move($destinationPath,$fileName);

        return "storage/videos/$fileName";
    }

    /**
     * Display the specified resource.
     *
     * @param  Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee  $employee)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
