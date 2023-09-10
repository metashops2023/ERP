<style>
    .set-height{
        position: relative;
    }
    .data__table thead tr th{
        font-weight: 400 !important;
        border-bottom: 0.1px solid #ff7e27 !important;
    }
</style>
<div class="set-height">
    <div class="data_preloader submit_preloader">
        <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
    </div>
    <div class="table-responsive" style="margin-top:0px;border-radius:15px">
        <table class="table data__table modal-table table-sm sale-product-table" style="background-color:#ff7e27;color:#FFF;font-weight:300">
            <thead>
                <tr>
                    <th scope="col" style="font-size:14px;background-color:#ff7e27;text-align:center;padding:10px;">SL</th>
                    <th scope="col" style="font-size:14px;background-color:#ff7e27;text-align:center;">Name</th>
                    <th scope="col" style="font-size:14px;background-color:#ff7e27;text-align:center;">Qty/Weight</th>
                    <th scope="col" style="font-size:14px;background-color:#ff7e27;text-align:center;">Unit</th>
                    <th scope="col" style="font-size:14px;background-color:#ff7e27;text-align:center;">Price.Inc.Tax</th>
                    <th scope="col" style="font-size:14px;background-color:#ff7e27;text-align:center;">Subtotal</th>
                    <th scope="col" style="font-size:14px;background-color:#ff7e27;text-align:center;"><i class="fas fa-trash-alt"></i></th>
                </tr>
            </thead>

            <tbody id="product_list"></tbody>
        </table>
    </div>
</div>
