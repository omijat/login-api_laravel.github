<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Facades\Response;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::select('id', 'title', 'description', 'image','status')->get();
        return response()->json(['products' => $products]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image'
        ]);

        try {
            $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('product/images', $request->file('image'), $imageName);

            $product = new Product([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'image' => $imageName,
            ]);

            $product->save();

            return response()->json(['message' => 'Product created successfully']);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['message' => 'Something went wrong while creating a product'], 500);
        }
    }

    public function show(Product $product)
    {
        
        return response()->json([
            'product' => $product

        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|image'
        ]);

        try {
            $product->title = $request->input('title');
            $product->description = $request->input('description');

            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($product->image) {
                    Storage::disk('public')->delete('product/images/' . $product->image);
                }

                $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('product/images', $request->file('image'), $imageName);
                $product->image = $imageName;
            }

            $product->save();

            return response()->json(['message' => 'Product updated successfully']);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['message' => 'Something went wrong while updating the product'], 500);
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Delete the product image if it exists
            if ($product->image) {
                Storage::disk('public')->delete('product/images/' . $product->image);
            }

            $product->delete();

            return response()->json(['message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['message' => 'Something went wrong while deleting the product'], 500);
        }
    }
    public function productviews()
    {
        $datass = Product::get();
        return view('Product',compact('datass'));
    }

    public function exportPDF()
    {
            // Fetch your data here (e.g., from a database)
            $datass = Product::all();
            
            // Load the Blade view and pass the data
            $pdf = PDF::loadView('Product', ['datass' => $datass]);
            
            // Return the PDF as a download
            return  $pdf->download('exported-data.pdf');
             
    }       
    
    


    public function ProductText(Request $request) // Product list CSV File Downlode
    {
        // Fetch data from the ProductModel
        $products = Product::all();

        // Prepare an array to store the CSV data
        $csvData = [];

        // Add a header row
        $csvData[] = ["ID", "Title", "Description", "Image"];

        // Add data rows
        foreach ($products as $product) {
            $csvData[] = [
                $product->id,
                $product->title,
                $product->description,
                $product->image,
            ];
        }

        // Create and write to the CSV file
        $file = fopen("Product.txt", "w");

        foreach ($csvData as $line) {
            fputcsv($file, $line);
        }

        fclose($file);

        // Provide the generated CSV file as a download response
        return response()->download("Product.txt")->deleteFileAfterSend(true);
    }


    public function ProductExcel()
    {
        $exportData = Product::all();
        $fileName = "export_data" . rand(1, 100) . ".xls";

        if ($exportData->isEmpty()) {
            return redirect()->back()->with('error', 'No data to export.');
        }

        $response = Response::stream(function () use ($exportData) {
            $file = fopen('php://output', 'w');

            // Output the header row
            fputcsv($file, array_keys($exportData[0]->toArray()), "\t");

            // Output the data rows
            foreach ($exportData as $row) {
                fputcsv($file, $row->toArray(), "\t");
            }

            fclose($file);
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);

        return $response;
    }



    public function ProductStatus($id){          ////************* status Function
        $product=Product::select('status')->where('id',$id)->first();

        if($product->status=='1'){
            $status='0';

        }
        else{
            $status='1';
        }
        $product=array('status'=>$status);
        Product::where('id',$id)->update($product);
        return redirect('http://localhost:3000/list');
       }

}

