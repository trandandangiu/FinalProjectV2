 <ul>
     @for ($i = 1; $i <= 5; $i++)
         @if ($i <= floor($product->average_rating))
             {{-- Sao đầy --}}
             <li><a href="javascript:void(0)"><i class="fas fa-star text-warning"></i></a></li>
         @elseif ($i == ceil($product->average_rating) && $product->average_rating - floor($product->average_rating) >= 0.5)
             {{-- Nửa sao --}}
             <li><a href="javascript:void(0)"><i class="fas fa-star-half-alt text-warning"></i></a></li>
         @else
             {{-- Sao rỗng --}}
             <li><a href="javascript:void(0)"><i class="far fa-star text-warning"></i></a></li>
         @endif
     @endfor
     <li class="review-total"> <a href="javascript:void(0)"> (
             {{ $product->reviews->count() }} )</a></li>
 </ul>
