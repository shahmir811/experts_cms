<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Product;
use App\Models\Store;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = Product::with('store')
            ->when($search, function($query) use ($search) {
                return $query->where('title', 'like', "%{$search}%")
                            ->orWhere('asin', 'like', "%{$search}%");
            })
            ->paginate(10);

        // Proper AJAX response check
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('pages.products.partials.products_rows', ['products' => $products])->render(),
                'next_page' => $products->nextPageUrl()
            ]);
        }

        return view('pages.products.index', compact('products'));
    }

    public function upload()
    {
        $stores = Store::all();
        return view('pages.products.upload', compact('stores'));
    }

    public function showDetail(Product $product)
    {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'html' => view('pages.products.partials.product_detail', ['product' => $product])->render()
            ]);
        }
        
        return view('pages.products.detail', compact('product'));
    }    


    public function processUpload(Request $request)
    {

        // dd("Hello");

            // Temporary debug code
        logger()->info('Upload request received', [
            'store_id' => $request->store_id,
            'has_file' => $request->hasFile('excel_file'),
            'all_input' => $request->all()
        ]);

                
        
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|exists:stores,id',
            // 'excel_file' => 'required|file|mimes:xlsx,xls'
            'excel_file' => 'required|file'
        ], [
            'store_id.required' => 'Please select a store.',
            'store_id.exists' => 'The selected store is invalid.',
            'excel_file.required' => 'Please select an Excel file to upload.',
            'excel_file.file' => 'The uploaded file is invalid.',
            'excel_file.mimes' => 'Only Excel files (.xlsx, .xls) are allowed.',
            'excel_file.max' => 'The file may not be greater than 2MB.'
        ]);

        // Manually check file presence (double validation)
        // if (!$request->hasFile('excel_file')) {
        //     return redirect()
        //         ->back()
        //         ->withErrors(['excel_file' => 'Please select an Excel file to upload.'])
        //         ->withInput();
        // }

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        

        $store = Store::findOrFail($request->store_id);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('excel_file');

        try {
            $data = Excel::toArray([], $file)[0]; // Get first sheet data
            
            if (count($data) < 2) {
                return redirect()
                    ->back()
                    ->with('error', 'Excel file is empty or has no data rows.');
            }

            $headers = $data[0]; // First row is headers
            $rows = array_slice($data, 1); // Data starts from second row
            
            $columnMapping = [
                'Image' => 'image',
                'Title' => 'title',
                'Buy Box: % Amazon 30 days' => 'buy_box_percentage_amazon_30_days',
                'Buy Box Eligible Offer Count: New FBA' => 'buy_box_eligible_offer_count_new_fba',
                'Amazon: Current' => 'amazon_current_price',
                'Amazon: Stock' => 'amazon_stock',
                'List Price: Current' => 'list_price_current',
                'List Price: 30 days avg.' => 'list_price_30_days_avg',
                'Count of retrieved live offers: New, FBA' => 'live_offers_fba',
                'Count of retrieved live offers: New, FBM' => 'live_offers_fbm',
                'URL: Amazon' => 'url_amazon',
                'Categories: Root' => 'categories_root',
                'Categories: Sub' => 'categories_sub',
                'Categories: Tree' => 'categories_tree',
                'Launchpad' => 'launchpad',
                'ASIN' => 'asin',
                'Manufacturer' => 'manufacturer',
                'Unit Count: Unit Value' => 'unit_count_value',
                'Unit Count: Unit Type' => 'unit_count_type',
                'Material' => 'material',
                'Item Type' => 'item_type',
                'Number of Items' => 'number_of_items',
                'Video Count' => 'video_count',
                'Has Main Video' => 'has_main_video',
                'Main Videos' => 'main_videos',
                'Additional Videos' => 'additional_videos',
                'Package: Dimension (cm³)' => 'package_dimension_cm3',
                'Package: Weight (g)' => 'package_weight_g',
                'Package: Quantity' => 'package_quantity',
                'Item: Dimension (cm³)' => 'item_dimension_cm3',
                'Item: Length (cm)' => 'item_length_cm',
                'Item: Width (cm)' => 'item_width_cm',
                'Item: Height (cm)' => 'item_height_cm',
                'Item: Weight (g)' => 'item_weight_g',
                'Batteries Included' => 'batteries_included',
                'Hazardous Materials' => 'hazardous_materials',
            ];

            $headerIndices = [];
            foreach ($headers as $index => $header) {
                if (isset($columnMapping[$header])) {
                    $headerIndices[$columnMapping[$header]] = $index;
                }
            }

            $newCount = 0;
            $updatedCount = 0;
            $existingAsins = Product::where('store_id', $store->id)->pluck('asin')->toArray();

            foreach ($rows as $row) {
                $asin = $row[$headerIndices['asin']] ?? null;
                
                if (empty($asin)) {
                    continue;
                }

                $productData = [
                    'store_id' => $store->id,
                    'title' => $row[$headerIndices['title']] ?? null,
                    'buy_box_percentage_amazon_30_days' => $row[$headerIndices['buy_box_percentage_amazon_30_days']] ?? null,
                    'buy_box_eligible_offer_count_new_fba' => $this->parseInteger($row[$headerIndices['buy_box_eligible_offer_count_new_fba']] ?? null),
                    'amazon_current_price' => $this->parseDecimal($row[$headerIndices['amazon_current_price']] ?? null),
                    'amazon_stock' => $this->parseInteger($row[$headerIndices['amazon_stock']] ?? null),
                    'list_price_current' => $this->parseDecimal($row[$headerIndices['list_price_current']] ?? null),
                    'list_price_30_days_avg' => $this->parseDecimal($row[$headerIndices['list_price_30_days_avg']] ?? null),
                    'live_offers_fba' => $this->parseInteger($row[$headerIndices['live_offers_fba']] ?? null),
                    'live_offers_fbm' => $this->parseInteger($row[$headerIndices['live_offers_fbm']] ?? null),
                    'url_amazon' => $row[$headerIndices['url_amazon']] ?? null,
                    'categories_root' => $row[$headerIndices['categories_root']] ?? null,
                    'categories_sub' => $row[$headerIndices['categories_sub']] ?? null,
                    'categories_tree' => $row[$headerIndices['categories_tree']] ?? null,
                    'launchpad' => $row[$headerIndices['launchpad']] ?? null,
                    'asin' => $asin,
                    'manufacturer' => $row[$headerIndices['manufacturer']] ?? null,
                    'unit_count_value' => $row[$headerIndices['unit_count_value']] ?? null,
                    'unit_count_type' => $row[$headerIndices['unit_count_type']] ?? null,
                    'material' => $row[$headerIndices['material']] ?? null,
                    'item_type' => $row[$headerIndices['item_type']] ?? null,
                    'number_of_items' => $this->parseInteger($row[$headerIndices['number_of_items']] ?? null),
                    'video_count' => $this->parseInteger($row[$headerIndices['video_count']] ?? null),
                    'has_main_video' => $this->parseBoolean($row[$headerIndices['has_main_video']] ?? null),
                    'main_videos' => $row[$headerIndices['main_videos']] ?? null,
                    'additional_videos' => $this->cleanText($row[$headerIndices['additional_videos']] ?? null),
                    'package_dimension_cm3' => $this->parseDecimal($row[$headerIndices['package_dimension_cm3']] ?? null),
                    'package_weight_g' => $this->parseDecimal($row[$headerIndices['package_weight_g']] ?? null),
                    'package_quantity' => $this->parseInteger($row[$headerIndices['package_quantity']] ?? null),
                    'item_dimension_cm3' => $this->parseDecimal($row[$headerIndices['item_dimension_cm3']] ?? null),
                    'item_length_cm' => $this->parseDecimal($row[$headerIndices['item_length_cm']] ?? null),
                    'item_width_cm' => $this->parseDecimal($row[$headerIndices['item_width_cm']] ?? null),
                    'item_height_cm' => $this->parseDecimal($row[$headerIndices['item_height_cm']] ?? null),
                    'item_weight_g' => $this->parseDecimal($row[$headerIndices['item_weight_g']] ?? null),
                    'batteries_included' => $this->parseBoolean($row[$headerIndices['batteries_included']] ?? null),
                    'hazardous_materials' => $row[$headerIndices['hazardous_materials']] ?? null,
                    'image' => $this->truncateUrl($row[$headerIndices['image']] ?? null),
                ];

                if (in_array($asin, $existingAsins)) {
                    // Update existing product
                    Product::where('asin', $asin)
                        ->where('store_id', $store->id)
                        ->update([
                            'amazon_current_price' => $productData['amazon_current_price'],
                            'amazon_stock' => $productData['amazon_stock'],
                            'list_price_current' => $productData['list_price_current'],
                            'list_price_30_days_avg' => $productData['list_price_30_days_avg'],
                        ]);
                    $updatedCount++;
                } else {
                    // Create new product
                    $productData['slug'] = Str::slug($productData['title']);
                    Product::create($productData);
                    $newCount++;
                }
            }

            return redirect()
                ->route('stores.show', $store->slug)
                ->with('status', "Successfully added $newCount new products and updated $updatedCount existing products.");
            
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error processing file: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function parseDecimal($value)
    {
        if (is_null($value)) {
            return null;
        }
        
        if (is_numeric($value)) {
            return (float) $value;
        }
        
        if (is_string($value)) {
            $value = str_replace(['$', ',', ' '], '', $value);
            if (is_numeric($value)) {
                return (float) $value;
            }
        }
        
        return null;
    }

    private function parseInteger($value)
    {
        if (is_null($value)) {
            return null;
        }
        
        if (is_numeric($value)) {
            return (int) $value;
        }
        
        return null;
    }

    private function parseBoolean($value)
    {
        if (is_null($value)) {
            return null;
        }
        
        if (is_bool($value)) {
            return $value;
        }
        
        if (is_string($value)) {
            $value = strtolower(trim($value));
            if (in_array($value, ['true', 'yes', '1', 't', 'y'])) {
                return true;
            }
            if (in_array($value, ['false', 'no', '0', 'f', 'n'])) {
                return false;
            }
        }
        
        if (is_numeric($value)) {
            return (bool) $value;
        }
        
        return null;
    }

    private function truncateUrl($url, $maxLength = 500)
    {
        if (empty($url)) {
            return null;
        }
        
        return strlen($url) > $maxLength ? substr($url, 0, $maxLength) : $url;
    }

    private function cleanText($text)
    {
        if (empty($text)) {
            return null;
        }
        
        return preg_replace('/[^\x00-\x7F]+/', '', $text);
    }    
}
