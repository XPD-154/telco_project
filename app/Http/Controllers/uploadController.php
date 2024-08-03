<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class uploadController extends Controller
{
    //

    public function index(Request $request)
    {
        return view("/upload");
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $fileContents = file($file->getPathname());

        foreach ($fileContents as $line) {
            $data = str_getcsv($line);

            /*
            //view data in array format
            echo "<pre>";
            print_r($data);
            echo "</pre>";
            */

            /*
            //pass csv data into model
            Product::create([
                'name' => $data[0],
                'price' => $data[1],
                // Add more fields as needed
            ]);
            */

            echo "THE BEGINNING";
            echo "<br>";
            echo "sku: " . $data[0];
            echo "<br>";
            echo "imel: " . $data[2];
            echo "<br>";
            echo "imsi: " . $data[1];
            echo "<br>";
            echo "sim: " . $data[2];
            echo "<br>";
            echo "pin1: " . $data[3];
            echo "<br>";
            echo "puk1: " . $data[4];
            echo "<br>";
            echo "pin2: " . $data[5];
            echo "<br>";
            echo "puk2: " . $data[6];
            echo "<br>";
            echo "box: " . $data[7];
            echo "<br>";
            echo "THE END";
            echo "<br>";
        
        }

        //return redirect()->back()->with('success', 'CSV file imported successfully.');
        
    }
}
