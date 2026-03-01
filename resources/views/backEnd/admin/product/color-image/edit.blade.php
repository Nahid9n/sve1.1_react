 @if (count($existing_item_images) > 0)
     @foreach ($existing_item_images as $attribute_id => $attribute)
         {{-- @dd($attribute) --}}
         <div class="col-md-3">
             <h6 class="text-capitalize" style="font-size:  14px;">
                 {{ DB::table('attributes')->where('id', $attribute_id)->first()->name }}
             </h6>
             @foreach ($attribute as $key2 => $attr)
                 {{-- @dd($key2,$attr) --}}
                 <div class="form-group mb-2">
                     <label for="attribute_images{{ $attribute_id }}_{{ $key2 }}"
                         class="form-label">{{ $attr['name'] }}</label>
                     <input type="hidden" name="attribute_images_old[{{ $attribute_id }}][{{ $key2 }}]"
                         value="{{ $attr['image_old'] ?? '' }}">

                     <img id="colorPreview-{{ $attribute_id }}-{{ $key2 }}" class="img-fluid mb-2"
                         style="max-height:50px;" src="{{ isset($attr['image']) ? asset($attr['image']) : '' }}">
                     <input type="file" class="form-control color-image-input"
                         id="attribute_images{{ $attribute_id }}_{{ $key2 }}" data-attr="{{ $attribute_id }}"
                         data-item="{{ $key2 }}"
                         name="attribute_images[{{ $attribute_id }}][{{ $key2 }}]">
                 </div>
             @endforeach
         </div>
     @endforeach
 @endif

 <script>
     $('.color-image-input').change(function() {
         //  alert('ok');
         let attr = $(this).data('attr');
         let item = $(this).data('item');
         console.log(attr, item);
         let reader = new FileReader();
         reader.onload = function(e) {
             $('#colorPreview-' + attr + '-' + item).attr('src', e.target.result);
         }
         reader.readAsDataURL(this.files[0]);
     });
 </script>
