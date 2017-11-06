<div class="block-header bg-primary-dark">
    <h3 class="block-title">Terms &amp; Conditions</h3>
    <div class="block-options">
        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
            <i class="si si-close"></i>
        </button>
    </div>
</div>
<div class="block-content">
    {{ Form::open(['url'=>$url_send_form,'method'=>'POST']) }}
        <div class="form-group row">
            <label class="col-lg-3 col-form-label" for="example-hf-email">Email</label>
            <div class="col-lg-7">
                <input type="email" class="form-control" id="example-hf-email" name="example-hf-email" placeholder="Enter Email..">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-3 col-form-label" for="example-hf-password">Password</label>
            <div class="col-lg-7">
                <input type="password" class="form-control" id="example-hf-password" name="example-hf-password" placeholder="Enter Password..">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-9 ml-auto">
                <button type="submit" class="btn btn-alt-primary">Login</button>
            </div>
        </div>
	{{ Form::close() }}
</div>
