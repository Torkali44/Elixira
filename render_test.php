<?php
try {
    $item = App\Models\Item::first();
    $html1 = view('menu.show', ['item' => $item, 'privateOfferQuantities' => [], 'relatedItems' => collect([])])->render();
    file_put_contents('test_menu.html', $html1);
    
    $brand = App\Models\Brand::first();
    $html2 = view('brands.show', ['brand' => $brand])->render();
    file_put_contents('test_brand.html', $html2);
    
    echo "SUCCESS\n";
} catch (\Throwable $e) {
    file_put_contents('test_error.txt', $e->getMessage() . "\n" . $e->getTraceAsString());
    echo "ERROR\n";
}
