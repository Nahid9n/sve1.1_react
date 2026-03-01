 @if (count($colorImages) > 0)
     @foreach ($colorImages as $key => $attribute)
         <div class="col-md-3">
             <h5 class="text-capitalize">
                 {{ DB::table('attributes')->where('id', $key)->value('name') }}
             </h5>
             @foreach ($attribute as $value)
                 <div class="form-group mb-2">
                     <label for="attribute_images{{ $value }}"
                         class="form-label">{{ DB::table('attribute_items')->where('id', $value)->value('name') }}</label>

                     <img id="colorPreview-{{ $key }}-{{ $value }}" class="img-fluid mb-2"
                         style="max-height:50px;">
                     <input type="file" class="form-control color-image-input" id="attribute_images{{ $value }}"
                         name="attribute_images[{{ $key }}][{{ $value }}]"
                         data-attr="{{ $key }}" data-item="{{ $value }}">
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
