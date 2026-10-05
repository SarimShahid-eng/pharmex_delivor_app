
    <div class="row">
      <div class="col-md-6">
          <div class="form-group">
              <label for="">Product Code</label>
              <input type="text" class="form-control" disabled value="{{ intVal($stock_detail->product_id) }}">
          </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
            <label for="">Product Title</label>
            <input type="text" class="form-control"disabled  value="{{ $stock_detail->product_title }}">
        </div>
    </div>
  </div>
  <div class="row">
      <div class="col-md-6">
          <div class="form-group">
              <label for="">Batch Code</label>
              <input type="text" class="form-control" value="{{ $stock_detail->batch_no }}" name="batch_no" required>
          </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
            <label for="">Expiry Date</label>
            <input type="date" class="form-control" value="{{ $stock_detail->expiry_date }}" name="expiry_date" required>
        </div>
    </div>
  </div>
  <div class="row">
      <div class="col-md-6">
          <div class='form-group'>
              <label for="">Qty</label>
              <input type="number" class="form-control" value="{{ $stock_detail->qty }}" name="qty" required>
          </div>
      </div>
      <div class="col-md-6">
          <div class="form-group">
              <label for="">Rate</label>
              <input type="number" class="form-control" value="{{ $stock_detail->rate }}" name="rate" step="any" required>
          </div>
      </div>
  </div>
  <input type="submit" class="btn btn-primary float-right">
  <input type="hidden" value="{{ $stock_detail->hashid }}" name="stock_id">
