<div class="row">
    <div class="col-md-4">
        @if($product->image)
            <img src="{{ $product->image }}" alt="{{ $product->title }}" class="img-fluid mb-3">
        @else
            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                <span class="text-muted">No image available</span>
            </div>
        @endif
    </div>
    <div class="col-md-8">
        <h4>{{ $product->title }}</h4>
        <table class="table table-sm">
            <tbody>
                <tr>
                    <th>ASIN</th>
                    <td>{{ $product->asin }}</td>
                </tr>
                <tr>
                    <th>Store</th>
                    <td>{{ $product->store->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Current Price</th>
                    <td>${{ number_format($product->amazon_current_price, 2) }}</td>
                </tr>
                <tr>
                    <th>List Price</th>
                    <td>${{ number_format($product->list_price_current, 2) }}</td>
                </tr>
                <tr>
                    <th>Stock</th>
                    <td>{{ $product->amazon_stock ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Manufacturer</th>
                    <td>{{ $product->manufacturer ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Buy Box % (30 days)</th>
                    <td>{{ $product->buy_box_percentage_amazon_30_days ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="accordion mt-3" id="productAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDimensions">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDimensions" aria-expanded="false" aria-controls="collapseDimensions">
                        Dimensions & Weight
                    </button>
                </h2>
                <div id="collapseDimensions" class="accordion-collapse collapse" aria-labelledby="headingDimensions" data-bs-parent="#productAccordion">
                    <div class="accordion-body">
                        <table class="table table-sm">
                            <tr>
                                <th>Package Dimensions (cm³)</th>
                                <td>{{ $product->package_dimension_cm3 ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Package Weight (g)</th>
                                <td>{{ $product->package_weight_g ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Item Dimensions (cm³)</th>
                                <td>{{ $product->item_dimension_cm3 ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Item Weight (g)</th>
                                <td>{{ $product->item_weight_g ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAdditional">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdditional" aria-expanded="false" aria-controls="collapseAdditional">
                        Additional Information
                    </button>
                </h2>
                <div id="collapseAdditional" class="accordion-collapse collapse" aria-labelledby="headingAdditional" data-bs-parent="#productAccordion">
                    <div class="accordion-body">
                        <table class="table table-sm">
                            <tr>
                                <th>Material</th>
                                <td>{{ $product->material ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Item Type</th>
                                <td>{{ $product->item_type ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Batteries Included</th>
                                <td>{{ $product->batteries_included ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <th>Hazardous Materials</th>
                                <td>{{ $product->hazardous_materials ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="{{ $product->url_amazon }}" target="_blank" class="btn btn-primary">
                <i class="bi bi-box-arrow-up-right"></i> View on Amazon
            </a>
        </div>
    </div>
</div>