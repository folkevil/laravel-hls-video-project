@extends('admin.layouts.admin')

@section('title')
  Add Video
@endsection

@section('styles')
@parent
<style>

/* Absolute Center Spinner */
#loading {
    position: fixed;
    z-index: 999;
    height: 2em;
    width: 2em;
    /*overflow: show;*/
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    display: none;
}

/* Transparent Overlay */
#loading:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.3);
}

/* :not(:required) hides these rules from IE9 and below */
#loading:not(:required) {
    /* hide "loading..." text */
    font: 0/0 a;
    color: transparent;
    text-shadow: none;
    background-color: transparent;
    border: 0;
}

#loading:not(:required):after {
    content: '';
    display: block;
    font-size: 10px;
    width: 1em;
    height: 1em;
    margin-top: -0.5em;
    -webkit-animation: spinner 1500ms infinite linear;
    -moz-animation: spinner 1500ms infinite linear;
    -ms-animation: spinner 1500ms infinite linear;
    -o-animation: spinner 1500ms infinite linear;
    animation: spinner 1500ms infinite linear;
    border-radius: 0.5em;
    -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
    box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
@-moz-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
@-o-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
@keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
</style>
@stop

@section('scripts')
@parent
<script>
	$(function() {
		$('.btn-get-video-data').click(function() {
			var button = $(this);
			parent_div = button.closest('.videoDivInner');
			source_3rd = parent_div.find('.source-3rd').val().trim();
			
			if (parent_div.find('select.sourceType').val() == 1 && source_3rd !== '')
			{
				$.ajax({
						url: '{{ route('admin.get_data_video_source') }}',
						type: 'POST',
						data: 'sid=new&source_3rd=' + source_3rd,
						dataType: 'json',
						headers: {
							'X-CSRF-TOKEN': '{{ csrf_token() }}'
						},
						beforeSend: function() {
							$('#loading').show();
							button.attr('disabled', 'disabled');
						},
						success: function(result){
							$('#loading').hide();
							button.removeAttr('disabled');
							
							if (result.success == false)
							{
								alert('Error: Could not get data, please try again.');
							} else {
								$('#mythumbnail').val(result.thumb);
								dt = new Date();
								$('#addVideoThumb').attr('src', result.thumb + '?' + dt.getTime());
								parent_div.find('input.duration-3rd').val(result.duration);
								$('#duration').val(result.duration);
								parent_div.find('input.thumbnail-3rd').val(result.original_thumb);
								
								if (result.title !== '')
								{
									$('#name').val(result.title);
								}
							}
						}, 
						error: function (jqXHR, textStatus, errorThrown) {
							alert('Unexpected Error.');
							location.reload(); 
						}
				});
			}
		});
	});
</script>
@stop
@section('js')
  <script>
    $(function () {
      $('#name').on('change', function(){
        var vidTitle = ($('#name').val());
        $('#description').text( vidTitle + ' {{$config->video_description or ''}}');
      })
    })
  </script>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <form method="POST" action="/admin/videos/addVideo" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal form-label-left">
        {{csrf_field()}}
        <div style="color: red">{{($errors->first())}}</div>
        
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Video Name
            <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input id="name" type="text" value="{{old('name')}}" class="form-control col-md-7 col-xs-12 " name="name" required="" >
          </div>
        </div>
        
        
        {{--<div class="form-group">--}}
          {{--<label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">--}}
            {{--Video Title--}}
            {{--<span class="required">*</span>--}}
          {{--</label>--}}
          {{--<div class="col-md-6 col-sm-6 col-xs-12">--}}
            {{--<input id="title" type="text" value="" class="form-control col-md-7 col-xs-12 " name="title" required="">--}}
          {{--</div>--}}
        {{--</div>--}}
        
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">
            Video Thumbnail
            <span class="required">*</span>
          </label>
          <div class="col-sm-2">
            <a class="fancybox" rel="group" href="{{asset('filemanager/dialog.php')}}??type=1&field_id=mythumbnail&relative_url=0">
              {{--<i class="fa fa-upload" aria-hidden="true"></i>--}}
              <button type="button" class="btn btn-primary">
                <span class="">Select Image</span>
              </button>
            </a>
          </div>
          <div class="col-md-4 col-sm-4 col-xs-12">
            {{--<input id="title"  class="form-control col-md-7 col-xs-12 " name="mythumbnail" >--}}
            <input id="mythumbnail" name="mythumbnail" type="text" value="" class="form-control" style="display: none">
            <img src="" alt="Thumbnail of Video Image" style="width: 100%;" id="addVideoThumb" >
          </div>

        </div>
        
        
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">
            Featured Video
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="checkbox" class="" name="featured">
          </div>
        </div>
        
        
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">
            Video Description
            <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea name="description" id="description" cols="30" rows="10"  class="form-control col-md-7 col-xs-12 ">Description = {{$config->video_description or ''}}</textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">
            Video Categories
            <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="category" id="category" class="form-control">
              <option value="">Select Category</option>
              @foreach($categories as $val)
                <option value="{{$val->id}}">{{$val->category_title}}</option>
              @endforeach
            </select>
          </div>
        </div>
        
        <div class="form-group" id="subcategories">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">
            Video Sub Categories
            <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="subcategory" id="subcategory" class="form-control">
              <option value="">Select Sub Category</option>
              <span id="subcategoriesvalues"></span>
            </select>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">
            Video Tags
            <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="tags[]" id="" class="js-example-basic-multiple"  multiple="multiple" style="width: 100%">
              @foreach($tags as $val)
                <option value="{{$val->id}}">{{$val->tag}}</option>
              @endforeach
            </select>
          </div>
        </div>

        @if(isset($celebrity_module) && $celebrity_module)
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="celebrities">
            Video Celebrities
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="celebrities[]" id="" class="js-example-basic-multiple"  multiple="multiple" style="width: 100%">
              @foreach($celebrities as $val)
                <option value="{{$val->id}}">{{$val->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
        @endif

        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">
            Publish Video
            <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="checkbox" class="" name="publish" checked>
          </div>
        </div>
  
        
  
        <hr style="border-color: #999;width: 77%;">
  

        
        <div class="form-group" id="videoDiv">
          <div class="col-md-11 col-md-offset-1 videoDivInner" id="videoDivInner">
            <div class="col-md-1">
              <label for="" class="label label-info">Add Video</label>
            </div>
            <div class="col-md-3 sourceTypeDiv">
              <select name="sourceType[]" id="sourceType" class="form-control sourceType">
                <option value="">Select Method</option>
                <option value="1" selected>3rd Party</option>
                <option value="2">Own Upload</option>
              </select></div>
            <div class="col-md-3 showHideClass">
              <span class="local">
                <input type="text" name="textSource[]" placeholder="i.e. youtube.com/abcxyz" class="form-control source-3rd">
				<input type="hidden" class="duration-3rd" name="duration[]">
				<input type="hidden" class="thumbnail-3rd" name="thumbnail[]">
              </span>
              <span class="outsource"  style="display: none">
                <input type="file" name="uploadFile[]">
              </span>
            </div>

            <div class="col-md-2">
				<button type="button" class="form-control btn btn-info btn-get-video-data">Get Video Data</button>
			</div>
            <!-- <div class="col-md-1">
              <input type="button"  value="Remove" class="form-control btn btn-danger removeMe" >
            </div> -->
            <div class="col-md-2">
            <button type="submit" class="btn btn-success form-control" onclick="$.LoadingOverlay('show');" style="float: right; margin-right: 10px" > Save Video</button>
          </div>
          </div>
        </div>
		
        <!-- <div class="form-group" >
          {{--<p href="" style="float: right" class="addMore"><u><b>Add More Videos</b></u></p>--}}
          <input type="button" class="addMore btn btn-primary pull-right" value="Add More Videos" style="margin-right: 10%">
        </div> -->
        {{--<div id="videoSource">--}}
          {{--<div class="form-group">--}}
            {{--<label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">--}}
              {{--Videos--}}
              {{--<span class="required">*</span>--}}
            {{--</label>--}}
            {{--<div class="col-md-6 col-sm-6 col-xs-12">--}}
              {{--<select name="sourceType" id="sourceType" class="form-control">--}}
                {{--<option value="">Select Method</option>--}}
                {{--<option value="1">3rd Party</option>--}}
                {{--<option value="2">Own Upload</option>--}}
              {{--</select>--}}
            {{--</div>--}}
          {{--</div>--}}
          {{----}}
          {{--<div class="form-group local" style="display: none">--}}
            {{--<label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">--}}
             {{----}}
              {{--<span class="required">*</span>--}}
            {{--</label>--}}
            {{--<div class="col-md-6 col-sm-6 col-xs-12">--}}
              {{--<input type="file" >--}}
            {{--</div>--}}
          {{--</div>--}}
          {{----}}
          {{--<div class="form-group outsource" style="display: none">--}}
            {{--<label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">--}}
             {{----}}
              {{--<span class="required">*</span>--}}
            {{--</label>--}}
            {{--<div class="col-md-6 col-sm-6 col-xs-12">--}}
              {{--<input type="text"  value="" placeholder="i.e. youtube.com/abcxyz" class="form-control">--}}
            {{--</div>--}}
          {{--</div>--}}
        {{--</div>--}}
        {{----}}
          {{--<div class="form-group actions" style="display: none">--}}
            {{--<label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">--}}
              {{----}}
              {{--<span class="required"></span>--}}
            {{--</label>--}}
            {{--<div class="col-md-3 col-sm-3 col-xs-12">--}}
              {{--<input type="button"  value="Add More" class="form-control btn btn-primary addVideo">--}}
            {{--</div>--}}
            {{--<div class="col-md-3 col-sm-3 col-xs-12">--}}
              {{--<input type="button"  value="Remove" class="form-control btn btn-danger">--}}
            {{--</div>--}}
          {{--</div>--}}
        {{----}}
      
        
        <br/>
        <br/>
        <br/>
        <br/>

  
        <style>
          .modal-backdrop {
            background-color: transparent;
          }
        </style>
        
      </form>
    </div>
  </div>
@endsection
